<?php
// Подключение к базе данных
$host = 'localhost'; // Хост
$dbname = 'project_1'; // Имя базы данных
$username = 'root'; // Имя пользователя базы данных
$password = ''; // Пароль базы данных

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

// Обработка данных формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $birth = $_POST['birth'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Хэширование пароля

    // Проверка, существует ли пользователь с таким email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
        echo "Пользователь с таким email уже зарегистрирован!";
    } else {
        // Добавление нового пользователя
        $stmt = $conn->prepare("INSERT INTO users (name, surname, email, birth, phone, password) VALUES (:name, :surname, :email, :birth, :phone, :password)");
        $stmt->execute([
            'name' => $name,
            'surname' => $surname,
            'email' => $email,
            'birth' => $birth,
            'phone' => $phone,
            'password' => $password
        ]);

        echo "Регистрация успешна! Теперь войдите.";
        header("Location: login.html"); // Перенаправление на страницу входа
    }
}
?>