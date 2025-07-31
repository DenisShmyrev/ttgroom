<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TTG Room - Бронирование комнат для настольных игр</title>
        <!-- Добавляем favicon -->
    <link rel="icon" href="/images/icon.png" sizes="16x16 32x32 48x48" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1585504198199-20277593b94f?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') no-repeat center center fixed;
            background-size: cover;
            color: white;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
        }
        .hero-section {
            background-color: rgba(0, 0, 0, 0.6);
            padding: 3rem;
            border-radius: 15px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="hero-section text-center mt-5">
            <h1 class="display-4 fw-bold">TTG Room</h1>
            <p class="lead">Бронируйте комнаты для D&D, Монополии и других настольных игр</p>
            
            <div class="mt-4">
                @auth
                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg">На основную страницу</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg me-2">Войти</a>
                    <a href="{{ route('register') }}" class="btn btn-success btn-lg">Регистрация</a>
                @endauth
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-4">
                <div class="card bg-dark text-white p-3">
                    <h3>D&D</h3>
                    <p>Игровые столы с экранами для карт и миниатюр</p>
                    <img src="https://as1.ftcdn.net/v2/jpg/04/85/65/36/1000_F_485653694_4izFhyzxV4Su7mYYq8lOYxOD5fQUaWuG.jpg">
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-dark text-white p-3">
                    <h3>Монополия</h3>
                    <p>Классические и редкие издания</p>
                    <img src="https://as2.ftcdn.net/v2/jpg/03/91/34/15/1000_F_391341512_WD9f6xB7hgHUh67pMN6Q2YBv0IATF79b.jpg">
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-dark text-white p-3">
                    <h3>Мафия</h3>
                    <p>Звукоизолированные комнаты для ролевых игр</p>
                    <img src="https://as1.ftcdn.net/v2/jpg/11/89/16/56/1000_F_1189165645_0yOqTEh3C6IYKBOxXWbp34rHEDGGPpnR.jpg">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>