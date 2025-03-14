<?php
session_start(); // Начинаем сессию

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html"); // Перенаправление на страницу входа
    exit();
}

// Подключение к базе данных (если нужно получить дополнительные данные)
$host = 'localhost';
$dbname = 'project_1';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

// Получение данных пользователя из сессии
$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];
$name = $_SESSION['name'];
$surname = $_SESSION['surname'];
$birth = $_SESSION['birth'];
$phone = $_SESSION['phone'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.html"><img src="img/logo.svg" alt="Логотип"></a>
        </div>
        <div>
            <nav>
                <ul class="nav-links">
                    <li><a href="main.html">Главная</a></li>
                    <li><a href="about.html">О нас</a></li>
                    <li><a href="index.html">Каталог</a></li>
                    <li><a href="favorite.html">Избранное</a></li>
                    <li><a href="basket.html">Корзина</a></li>
                    <li><a href="">Контакты</a></li>
                    <li><a href="login.html">Личный кабинет</a></li>
                </ul>
            </nav>
        </div>
        <div class="burger-menu" onclick="toggleMenu()">☰</div>
    </header>

    <div class="container_acc">
        <h2>Личный кабинет</h2>
        <p class="welcome-text">Добро пожаловать, <?php echo $name; ?>!</p>

        <div class="profile-info">
            <p><strong>Имя:</strong> <?php echo $name; ?></p>
            <p><strong>Фамилия:</strong> <?php echo $surname; ?></p>
            <p><strong>Email:</strong> <?php echo $email; ?></p>
            <p><strong>Дата рождения:</strong> <?php echo $birth; ?></p>
            <p><strong>Телефон:</strong> <?php echo $phone; ?></p>
        </div>

        <form action="logout.php" method="POST">
            <button type="submit" id="logout-btn">Выйти</button>
        </form>
    </div>
</body>
</html>
