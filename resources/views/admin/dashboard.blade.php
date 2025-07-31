@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Админ-панель</h1>
    <div class="row mt-4">
        <div class="col-md-4">
            <a href="{{ route('admin.rooms.controll') }}" class="btn btn-primary btn-lg w-100 py-3">
                Управление комнатами
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('admin.bookings') }}" class="btn btn-success btn-lg w-100 py-3">
            Все бронирования
            </a>
        </div>
    </div>
</div>
@endsection