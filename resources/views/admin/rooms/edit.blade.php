@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Редактирование комнаты</h1>
    <form method="POST" action="{{ route('admin.rooms.update', $room->id) }}" class="cardio">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name">Название</label>
            <input type="text" name="name" value="{{ $room->name }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description">Описание</label>
            <textarea name="description" class="form-control" rows="3" required>{{ $room->description }}</textarea>
        </div>
        <div class="mb-3">
            <label for="capacity">Вместимость</label>
            <input type="number" name="capacity" value="{{ $room->capacity }}" class="form-control" min="1" required>
        </div>
        <div class="mb-3">
            <label for="price_per_hour">Цена за час</label>
            <input type="number" step="0.01" name="price_per_hour" value="{{ $room->price_per_hour }}" class="form-control" min="0" required>
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <form method="POST" action="{{ route('admin.rooms.edit', $room->id) }}">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </form>
            <a href="{{ route('admin.rooms.controll') }}" class="btn btn-secondary">Отмена</a>
            <form id="delete-form" action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
            </form>
        </div>
    </form>
</div>
@endsection