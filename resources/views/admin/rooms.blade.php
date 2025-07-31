@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Управление комнатами</h1>
    <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary mb-3">Добавить комнату</a>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
    ← Назад в панель
    </a>
    <table class="table cardio">
        <thead style="color: white">
            <tr>
                <th>Название</th>
                <th>Описание</th>
                <th>Вместимость</th>
                <th>Цена за час</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody style="color: white">
            @foreach($rooms as $room)
            <tr>
                <td>{{ $room->name }}</td>
                <td>{{ $room->description }}</td>
                <td>{{ $room->capacity }}</td>
                <td>{{ $room->price_per_hour }} руб/час</td>
                <td>
                    <a href="{{ route('admin.rooms.edit', $room) }}" class="btn btn-sm btn-secondary">Редактировать</a>
                    <form method="POST" action="{{ route('admin.rooms.destroy', $room) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection