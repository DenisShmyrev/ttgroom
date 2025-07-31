@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Все бронирования</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            ← Назад в панель
        </a>
    </div>
    <div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.bookings') }}">
            <div class="row">
                <div class="col-md-3">
                    <label>Статус</label>
                    <select name="status" class="form-select">
                        <option value="">Все</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Подтверждено</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Отменено</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Дата от</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label>Дата до</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3 align-self-end">
                    <button type="submit" class="btn btn-primary w-100">Фильтровать</button>
                </div>
            </div>
        </form>
    </div>
</div>
    <div class="card border-primary">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="border-end sortable" data-sort="user.name">Пользователь</th>
                        <th class="border-end">Комната</th>
                        <th class="border-end">Дата и время</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                    <tr class="{{ $loop->odd ? 'table-light' : 'table-secondary' }}">
                        <td class="border-end">{{ $booking->user->name }}</td>
                        <td class="border-end">{{ $booking->room->name }}</td>
                        <td class="border-end">
                            {{ $booking->start_time->format('d.m.Y H:i') }}<br>
                            <small>{{ $booking->end_time->format('H:i') }}</small>
                        </td>
                        <td>
                            @if($booking->status == 'confirmed')
                                <span class="badge bg-success">Подтверждено</span>
                            @elseif($booking->status == 'cancelled')
                                <span class="badge bg-danger">Отменено</span>
                            @else
                                <span class="badge bg-warning text-dark">{{ $booking->status }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="btn btn-sm btn-primary me-2">
                                Редактировать
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4 d-flex justify-content-center">
    {{ $bookings->links() }}
    </div>

</div>

<style>
    .table {
        border: 1px solid #dee2e6;
    }
    .table th, .table td {
        padding: 12px;
        vertical-align: middle;
    }
    .border-end {
        border-right: 1px solid #dee2e6 !important;
    }
</style>
@endsection