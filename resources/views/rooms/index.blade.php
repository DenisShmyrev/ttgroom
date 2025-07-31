@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Наши игровые комнаты</h1>
    
    <div class="row">
        @foreach($rooms as $room)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h3 class="card-title">{{ $room->name }}</h3>
                    <p class="card-text">{{ $room->description }}</p>
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item">Вместимость: {{ $room->capacity }} чел.</li>
                        <li class="list-group-item">Цена: {{ $room->price_per_hour }} руб/час</li>
                    </ul>
                    <a href="{{ route('bookings.create', ['room_id' => $room->id]) }}" 
                       class="btn btn-primary w-100">
                        Забронировать
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection