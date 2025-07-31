@extends('layouts.app')

@php
$workingHours = $workingHours ?? ['10:00', '22:00']; // Задаем значение по умолчанию, если не передано
$start = \Carbon\Carbon::parse($workingHours[0]);
$end = \Carbon\Carbon::parse($workingHours[1]);
@endphp
<!-- В начале файла bookings/create.blade.php -->
@if(count($timeSlots) === 0)
    <div class="alert alert-warning">
        Нет доступных временных слотов для выбранной даты и комнаты.
    </div>
@endif
@section('content')
<div class="container py-5">
    <div class="hero-section">
        <h2 class="text-center mb-4">Бронирование комнаты</h2>
        
        <form method="POST" action="{{ route('bookings.store') }}" id="booking-form">
            @csrf

            <!-- Выбор комнаты -->
            <div class="mb-3">
                <label class="form-label">Комната</label>
                <select name="room_id" class="form-select" required id="room-select">
                    <option value="">-- Выберите комнату --</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" 
                            {{ $selectedRoom && $selectedRoom->id == $room->id ? 'selected' : '' }}>
                            {{ $room->name }} ({{ $room->price_per_hour }} руб/час)
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Дата бронирования -->
            <div class="mb-3">
                <label class="form-label">Дата бронирования</label>
                <div class="input-group">
                    <input type="date" name="booking_date" id="booking-date" 
                        class="form-control" 
                        min="{{ now()->format('Y-m-d') }}" 
                        value="{{ $defaultDate }}" required>
                </div>
            </div>

            <!-- Визуальный выбор времени -->
            <div class="mb-3">
                <label class="form-label">Время начала</label>
                <select name="start_time" class="form-select" id="time-select" required>
                    <option value="">-- Сначала выберите дату и комнату --</option>
                    @foreach($timeSlots as $time)
                        <option value="{{ $time }}">{{ $time }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Длительность -->
            <div class="mb-3">
                <label class="form-label">Длительность (часы)</label>
                <select name="duration" class="form-select" required id="duration-select">
                    @foreach([1, 2, 3] as $hours)
                        <option value="{{ $hours }}">{{ $hours }} час{{ $hours != 1 ? 'а' : '' }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Количество участников -->
            <div class="mb-3">
                <label class="form-label">Количество участников</label>
                <input type="number" name="participants_count" class="form-control" 
                    min="1" max="{{ $maxCapacity }}" 
                    value="{{ old('participants_count', 1) }}" 
                    required>
                <small class="text-muted">
                    @if($selectedRoom)
                        Максимум: {{ $selectedRoom->capacity }} человек
                    @else
                        Выберите комнату для отображения максимальной вместимости
                    @endif
                </small>
            </div>

            <!-- Комментарий -->
            <div class="mb-3">
                <label class="form-label">Комментарий (необязательно)</label>
                <textarea name="notes" class="form-control" rows="2"></textarea>
            </div>

            <!-- Кнопка отправки -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Забронировать</button>
            </div>
        </form>
    </div>  
</div>
<!-- Модальное окно для выбора даты -->
<div class="modal fade" id="dateModal" tabindex="-1" aria-labelledby="dateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dateModalLabel">Выбор даты</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="date" id="modal-booking-date" 
                    class="form-control" 
                    min="{{ now()->format('Y-m-d') }}" 
                    value="{{ $defaultDate }}" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" onclick="applyDate()">Применить</button>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('booking-date');
    const roomSelect = document.getElementById('room-select');

    // Функция для перенаправления на новую страницу с выбранными параметрами
    function redirectToBookingPage() {
        const roomId = roomSelect.value;
        const bookingDate = dateInput.value;

        if (roomId && bookingDate) {
            const url = new URL(window.location.href);
            url.searchParams.set('room_id', roomId);
            url.searchParams.set('booking_date', bookingDate);
            window.location.href = url.toString();
        }
    }

    // Обновляем страницу при изменении даты или комнаты
    [dateInput, roomSelect].forEach(element => {
        element.addEventListener('change', redirectToBookingPage);
    });
});
</script>
<style>
    select option:disabled {
        color: #ccc;
        background-color: #f8f9fa;
    }
</style>