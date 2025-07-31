@extends('layouts.app')

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if(session('alert_message'))
<div class="alert alert-{{ session('alert_type', 'success') }} alert-dismissible fade show">
    {{ session('alert_message') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
<div class="mb-4">
    <h3>Добро пожаловать, {{ Auth::user()->name }}!</h3>
</div>
<div class="container py-5">
    <div class="hero-section">
        <h2 class="mb-4">Личный кабинет</h2>
        
        <div class="d-flex justify-content-between mb-4">
            <h3>Мои бронирования</h3>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger">Выйти</button>
            </form>
        </div>

        @if($bookings->isEmpty())
            <div class="alert alert-info">
                У вас нет бронирований.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>Комната</th>
                            <th>Дата и время</th>
                            <th>Участники</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                        <tr>
                            <td>{{ $booking->room->name }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($booking->start_time)->format('d.m.Y H:i') }} - 
                                {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                            </td>
                            <td>{{ $booking->participants_count }}</td>
                            <td>
                                @if($booking->status == 'confirmed')
                                    <span class="badge bg-success">Подтверждено</span>
                                @else
                                    <span class="badge bg-danger">Отменено</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->status == 'confirmed')
                                <form method="POST" action="{{ route('bookings.cancel', $booking->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-danger">Отменить</button>
                                </form>
                                @elseif($booking->status == 'cancelled')
                                    @if(auth()->user()->role === 'admin')
                                        <form method="POST" action="{{ route('admin.bookings.restore', $booking->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success">Вернуть</button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection