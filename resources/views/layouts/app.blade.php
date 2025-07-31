<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Бронирование игровых комнат</title>
    <!-- Добавляем favicon -->
    <link rel="icon" href="/images/icon.png" sizes="16x16 32x32 48x48" type="image/png">
    <!-- Подключаем Bootstrap для стилей -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1585504198199-20277593b94f') no-repeat center center fixed;
            background-size: cover;
            color: white;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
            min-height: 100vh;
        }
        .hero-section {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 2rem;
            border-radius: 15px;
            backdrop-filter: blur(5px);
        }
        .card {
            background-color: rgba(0, 0, 0, 0.6);
            border: none;
        }
        .form-control {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid #444;
        }
        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }
        .cardio {
        background-color: rgba(0, 0, 0, 0.6);
        }
        .footer {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 1rem 0;
            text-align: center;
            position: fixed; /* Фиксируем футер внизу */
            bottom: 0;
            width: 100%;
        }
        .footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <!-- Шапка сайта -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="/">
                    <!--На главную страницу-->
                    <img src="{{asset('images/icon.ico')}}" alt="logo" width="40">TTG Room
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}">Главная</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('rooms.index') }}" class="nav-link">Комнаты</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('profile') }}">Личный кабинет</a>
                            </li>
                            <li class="nav-item">
                                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="nav-link btn btn-link" style="display: inline; padding: 0; border: none; background: none;">
                                        Выйти
                                    </button>
                                </form>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Войти</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Регистрация</a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

    <!-- Основное содержимое страницы -->
    <div class="container mt-4">
        @yield('content') <!-- Сюда будет подставляться контент других страниц -->
    </div>
    <!-- Футер -->
    <footer class="footer">
        <div class="container">
        <p>
            <a href="{{ route('about') }}" class="text-white me-3">О нас</a>
            <a href="{{ route('contact') }}" class="text-white me-3">Контакты</a>
            <a href="{{ route('policy') }}" class="text-white">Политика конфиденциальности</a>
        </p>
        </div>
    </footer>

    <!-- Подключаем JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>