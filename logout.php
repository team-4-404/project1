<?php
session_start(); // Начинаем сессию
session_destroy(); // Уничтожаем сессию
header("Location: login.html"); // Перенаправляем на страницу входа
exit();
?>