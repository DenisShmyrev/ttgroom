@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="hero-section text-center mb-4">
        <h1>TTG Room</h1>
        <p>Бронируйте комнаты для D&D, Монополии и других настольных игр</p>
        <p><br>Если хотите забронировать место, то переходите по <a href="{{ route('rooms.index') }}">ссылке</a>
            или через раздел в меню сверху!</p>
        @guest
            <a href="{{ route('login') }}" class="btn btn-primary me-2">Войти</a>
            <a href="{{ route('register') }}" class="btn btn-success">Регистрация</a>
        @endguest
    </div>

    <!-- Блок с информацией о бронях -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            Последние бронирования
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead style="color: white">
                    <tr>
                        <th>Комната</th>
                        <th>Дата и время</th>
                        <th>Участники</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentBookings as $booking)
                        <tr>
                            <td style="color: white">{{ $booking->room->name }}</td>
                            <td style="color: white">{{ $booking->start_time->format('d.m.Y H:i') }} - {{ $booking->end_time->format('H:i') }}</td>
                            <td style="color: white">{{ $booking->participants_count }} человек</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection