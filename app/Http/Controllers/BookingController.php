<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room; // Добавьте в начало файла
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function adminIndex(Request $request)
    {
        $bookings = Booking::with(['user', 'room'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->date_from, fn($q) => $q->whereDate('start_time', '>=', $request->date_from))
            ->when($request->date_to, fn($q) => $q->whereDate('start_time', '<=', $request->date_to))
            ->latest()
            ->paginate(10);
        
        return view('admin.bookings', compact('bookings'));
    }    
    //Закрытие брони
    public function cancel(Booking $booking)
    {
        if ($booking->user_id != auth()->id()) {
            abort(403);
        }

        $booking->update(['status' => 'cancelled']);
        
        return back()->with('success', 'Бронирование отменено!');
    }
    //Восстановление брони
    public function restore(Booking $booking)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Доступ запрещен!');
        }
        $booking->update(['status' => 'confirmed']);
        return back()->with('success', 'Бронирование успешно возвращено!');
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $roomId = $request->input('room_id');
        $bookingDate = $request->input('booking_date');
        $selectedRoom = $roomId ? Room::find($roomId) : null;

        // Получаем все брони для выбранной комнаты на конкретную дату
        $bookedSlots = [];
        if ($selectedRoom) {
            $bookedSlots = Booking::where('room_id', $roomId)
                ->whereDate('start_time', $bookingDate)
                ->get(['start_time', 'end_time'])
                ->map(function ($booking) {
                    return [
                        'start' => $booking->start_time->format('H:i'),
                        'end' => $booking->end_time->format('H:i')
                    ];
                })->toArray();
        }

        // Генерируем все возможные временные слоты
        $timeSlots = [];
        $workingHours = ['10:00', '22:00']; // График работы
        $interval = 60; // Интервал в минутах
        $start = Carbon::parse($workingHours[0]);
        $end = Carbon::parse($workingHours[1]);

        while ($start <= $end) {
            $time = $start->format('H:i');
            $isAvailable = true;

            // Проверяем, не пересекается ли время с существующими бронями
            foreach ($bookedSlots as $slot) {
                if ($time >= $slot['start'] && $time < $slot['end']) {
                    $isAvailable = false;
                    break;
                }
            }

            if ($isAvailable) {
                $timeSlots[] = $time;
            }

            $start->addMinutes($interval);
        }

        return view('bookings.create', [
            'rooms' => $selectedRoom ? collect([$selectedRoom]) : Room::all(),
            'selectedRoom' => $selectedRoom,
            'maxCapacity' => $selectedRoom ? $selectedRoom->capacity : Room::max('capacity'),
            'defaultDate' => $bookingDate,
            'timeSlots' => $timeSlots,
            'durationOptions' => [1, 2, 3] // Варианты длительности
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Валидация данных
        $validatedData = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $bookingTime = Carbon::parse($request->booking_date . ' ' . $value);
                    $isBooked = Booking::where('room_id', $request->room_id)
                        ->where('start_time', '<=', $bookingTime)
                        ->where('end_time', '>', $bookingTime)
                        ->exists();
                    
                    if ($isBooked) {
                        $fail('Выбранное время стало занято. Пожалуйста, выберите другое время.');
                    }
                }
            ],
            'duration' => 'required|integer|min:1|max:3',
            'participants_count' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($request) {
                    $room = Room::find($request->room_id);
                    if (!$room) {
                        $fail('Выбранная комната не найдена');
                        return;
                    }
                    if ($value > $room->capacity) {
                        $fail('Количество участников превышает вместимость комнаты (максимум: '.$room->capacity.')');
                    }
                }
            ]
        ]);

        // Получаем объекты комнаты и пользователя
        $room = Room::findOrFail($validatedData['room_id']);
        $user = auth()->user();

        // Формируем даты с учетом технического перерыва
        $cleaningInterval = 60; // 60 минут на проветривание/уборку
        $startTime = Carbon::parse($validatedData['booking_date'] . ' ' . $validatedData['start_time']);
        $endTime = $startTime->copy()->addHours($validatedData['duration']);

        // Проверка доступности комнаты с учетом техперерыва
        $conflictingBooking = Booking::where('room_id', $room->id)
            ->where(function($query) use ($startTime, $endTime, $cleaningInterval) {
                $query->where(function($q) use ($startTime, $endTime, $cleaningInterval) {
                        // Проверяем пересечение с учетом техперерыва
                        $q->where('start_time', '<', $endTime->copy()->addMinutes($cleaningInterval))
                          ->where('end_time', '>', $startTime->copy()->subMinutes($cleaningInterval));
                    });
            })
            ->exists();

        if ($conflictingBooking) {
            return back()
                ->withInput()
                ->withErrors(['start_time' => 'Комната занята в выбранное время (учтено время на уборку)']);
        }

        // Создаем бронирование
        $booking = new Booking([
            'start_time' => $startTime,
            'end_time' => $endTime,
            'participants_count' => $validatedData['participants_count'],
            'notes' => $request->input('notes', ''),
            'status' => 'confirmed'
        ]);

        $booking->room()->associate($room);
        $booking->user()->associate($user);
        $booking->save();

        // Перенаправление с детальным сообщением
        return redirect()
            ->route('profile')
            ->with([
                'alert_type' => 'success',
                'alert_message' => "Комната {$room->name} успешно забронирована на " .
                                $startTime->format('d.m.Y H:i') .
                                " (до " . $endTime->format('H:i') . ")" .
                                "\nКоличество участников: " . $validatedData['participants_count']
            ]);
    }

    public function availableTimes(Request $request){
    $validated = $request->validate([
        'room_id' => 'required|exists:rooms,id',
        'booking_date' => 'required|date|after_or_equal:today'
    ]);

    $room = Room::findOrFail($validated['room_id']);
    $bookings = Booking::where('room_id', $room->id)
        ->whereDate('start_time', $validated['booking_date'])
        ->orderBy('start_time')
        ->get(['start_time', 'end_time']);

    // График работы
    $workingHours = ['10:00', '22:00'];
    $interval = 30; // Интервал в минутах

    // Генерируем все возможные временные слоты
    $start = Carbon::parse($validated['booking_date'] . ' ' . $workingHours[0]);
    $end = Carbon::parse($validated['booking_date'] . ' ' . $workingHours[1]);

    $timeSlots = [];
    while ($start <= $end) {
        $time = $start->copy();
        $slotEnd = $time->copy()->addMinutes($interval);
        $isAvailable = true;

        // Проверяем, не пересекается ли время с существующими бронями
        foreach ($bookings as $booking) {
            $bookingStart = Carbon::parse($booking->start_time);
            $bookingEnd = Carbon::parse($booking->end_time);
            if ($time < $bookingEnd && $slotEnd > $bookingStart) {
                $isAvailable = false;
                break;
            }
        }

        if ($isAvailable) {
            $timeSlots[] = [
                'start' => $time->format('H:i'),
                'end' => $slotEnd->format('H:i')
            ];
        }

        $start->addMinutes($interval);
    }

    return response()->json([
        'timeSlots' => $timeSlots
    ]);
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        $rooms = Room::all();
        return view('admin.bookings.edit', compact('booking', 'rooms'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|in:confirmed,cancelled'
        ]);

        // Проверка доступности комнаты (исключая текущее бронирование)
        $isAvailable = !Booking::where('room_id', $validated['room_id'])
            ->where('id', '!=', $booking->id)
            ->where('start_time', '<', $validated['end_time'])
            ->where('end_time', '>', $validated['start_time'])
            ->exists();

        if (!$isAvailable) {
            return back()->withErrors(['room_id' => 'Комната уже занята в это время!']);
        }

        $booking->update($validated);

        return redirect()->route('admin.bookings')->with([
                'alert_type' => 'success',
                'alert_message' => "Бронирование обновлено!"
            ]);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();
        return redirect()->route('admin.bookings')->with([
                'alert_type' => 'success',
                'alert_message' => "Бронь успешно удалена."
            ]);

    }
}
