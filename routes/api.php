<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Booking;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/bookings/available-times', function (Request $request) {
    $validated = $request->validate([
        'room_id' => 'required|exists:rooms,id',
        'date' => 'required|date'
    ]);

    $bookedSlots = Booking::where('room_id', $validated['room_id'])
        ->whereDate('start_time', $validated['date'])
        ->get(['start_time', 'end_time'])
        ->map(function ($booking) {
            return [
                'start' => $booking->start_time->format('H:i'),
                'end' => $booking->end_time->format('H:i')
            ];
        });

    // Генерируем все возможные временные слоты
    $workingHours = ['10:00', '22:00'];
    $interval = 60; // минут
    $availableTimes = [];
    
    $current = \Carbon\Carbon::parse($workingHours[0]);
    $end = \Carbon\Carbon::parse($workingHours[1]);
    
    while ($current <= $end) {
        $time = $current->format('H:i');
        $isAvailable = !$bookedSlots->contains(function ($slot) use ($time) {
            return $time >= $slot['start'] && $time < $slot['end'];
        });
        
        if ($isAvailable) {
            $availableTimes[] = $time;
        }
        
        $current->addMinutes($interval);
    }

    return response()->json([
        'availableTimes' => $availableTimes,
        'bookedSlots' => $bookedSlots
    ]);
});

Route::get('/api/bookings/time-slots', function (Request $request) {
    $roomId = $request->query('room_id');
    $bookingDate = $request->query('date', now()->format('Y-m-d'));

    $bookedSlots = Booking::where('room_id', $roomId)
        ->whereDate('start_time', $bookingDate)
        ->get(['start_time', 'end_time'])
        ->map(function ($booking) {
            return [
                'start' => $booking->start_time->format('H:i'),
                'end' => $booking->end_time->format('H:i')
            ];
        })->toArray();

    $workingHours = ['10:00', '22:00'];
    $interval = 60; // Интервал в минутах

    $timeSlots = [];
    $start = \Carbon\Carbon::parse($workingHours[0]);
    $end = \Carbon\Carbon::parse($workingHours[1]);

    while ($start <= $end) {
        $time = $start->format('H:i');
        $isAvailable = true;

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

    return response()->json($timeSlots);
});