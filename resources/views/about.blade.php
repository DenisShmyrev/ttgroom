@extends('layouts.app')
<style>
    .img-fluid {
    max-width: 100%;
    height: auto;
}

.hero-section {
    background-color: rgba(0, 0, 0, 0.6);
    padding: 2rem;
    border-radius: 15px;
    backdrop-filter: blur(5px);
}
</style>
@section('content')
<div class="container py-5">
    <div class="hero-section">
        <h2 class="text-center mb-4">О нас</h2>
        <p>Добро пожаловать в TTG Room! Мы предлагаем вам комфортные игровые комнаты для настольных игр.</p>
        <p>Наши комнаты оборудованы всем необходимым для игры в настольные игры, такие как D&D, Монополия и Мафия.</p>
        <p>Забронируйте комнату прямо сейчас!</p>   
                <!-- Изображение маскота -->
        <img src="{{ asset('images/Picture.png') }}" alt="Маскот TTG Room" class="img-fluid my-4" style="max-width: 300px; padding: inherit; margin:auto; display: block;">

    </div>
</div>
@endsection