@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-primary">
        <div class="card-header bg-primary text-white">
            <h3>Редактирование брони #{{ $booking->id }}</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.bookings.update', $booking->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Комната</label>
                    <select name="room_id" class="form-select" required>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" 
                                {{ $booking->room_id == $room->id ? 'selected' : '' }}>
                                {{ $room->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Дата начала</label>
                        <input type="datetime-local" name="start_time" 
                               class="form-control" 
                               value="{{ $booking->start_time->format('Y-m-d\TH:i') }}" 
                               required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Дата окончания</label>
                        <input type="datetime-local" name="end_time" 
                               class="form-control" 
                               value="{{ $booking->end_time->format('Y-m-d\TH:i') }}" 
                               required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Статус</label>
                    <select name="status" class="form-select" required>
                        <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Подтверждено</option>
                        <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Отменено</option>
                    </select>
                </div>

                <!-- Кнопки сохранения и отмены -->
                <div class="d-flex justify-content-between align-items-center">
                    <form method="POST" action="{{ route('bookings.update', $booking->id) }}">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </form>
                    <a href="{{ route('admin.bookings') }}" class="btn btn-secondary">Отмена</a>

                    <!-- Кнопка удаления -->
                    <form id="delete-form" action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirmDelete();">Удалить</button>
                    </form>
                </div>            
            </form>
        </div>
    </div>
</div>
<!-- Скрипт для подтверждения удаления -->
<script>
function confirmDelete() {
    return confirm("Вы уверены, что хотите удалить эту бронь? Это действие нельзя отменить.");
}
</script>
@endsection