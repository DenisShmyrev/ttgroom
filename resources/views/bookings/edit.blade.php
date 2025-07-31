@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Редактирование брони #{{ $booking->id }}</h2>
    
    <form method="POST" action="{{ route('bookings.update', $booking->id) }}">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label>Комната</label>
            <select name="room_id" class="form-select">
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}" {{ $booking->room_id == $room->id ? 'selected' : '' }}>
                        {{ $room->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <!-- Остальные поля формы -->
        
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>
    <form method="POST" action="{{ route('bookings.cancel', $booking->id) }}" style="display: inline;">
    @csrf
    <button type="submit" class="btn btn-sm btn-outline-danger">❌ Отменить</button>
</form>
</div>
@endsection