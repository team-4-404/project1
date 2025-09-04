<?php
// Подключение к базе данных
require_once 'config.php';

// Обработка данных формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $birth = $_POST['birth'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Хэширование пароля

    // 1. ВАЛИДАЦИЯ
    if (empty($_POST['email']) || empty($_POST['password']) || empty($_POST['name']) || empty($_POST['surname']) || empty($_POST['birth']) || empty($_POST['phone'])) {
        $error = 'Заполните все поля.';
        die($error);
    } else {
        // 2. ОЧИСТКА
        $email = strip_tags(trim($_POST['email']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $name = strip_tags(trim($_POST['name']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $surname = strip_tags(trim($_POST['surname']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $birth = strip_tags(trim($_POST['birth']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $phone = strip_tags(trim($_POST['phone']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = $_POST['password'];

        // 2.1. Проверка email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Неверный email.';
            die($error);
        }
        
        // 3. ИЩЕМ пользователя в БД
        $sql = "SELECT id, name, surname, birth, phone, password_hash FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // 4. ПРОВЕРЯЕМ пароль
        if ($user && password_verify($password, $user['password_hash'])) {
            // Пароль верный! Сохраняем данные в сессию
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_surname'] = $user['surname'];
            $_SESSION['user_birth'] = $user['birth'];
            $_SESSION['user_phone'] = $user['phone'];
            $_SESSION['user_email'] = $email;

            // Перенаправляем в личный кабинет
            header('Location: /account.php');
            exit();
        } else {
            // Неправильный email или пароль
            $error = 'Неверные email или пароль.';
            die($error);
        }
    }

    // Проверка, существует ли пользователь с таким email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
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