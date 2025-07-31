@extends('layouts.app')
@section('content')
<div class="container cardio">
    <h1>Добавление новой комнаты</h1>
    <form method="POST" action="{{ route('admin.rooms.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name">Название</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description">Описание</label>
            <textarea name="description" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="capacity">Вместимость</label>
            <input type="number" name="capacity" class="form-control" min="1" required>
        </div>
        <div class="mb-3">
            <label for="price_per_hour">Цена за час</label>
            <input type="number" step="0.01" name="price_per_hour" class="form-control" min="0" required>
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>
</div>
@endsection