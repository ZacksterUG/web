<?php
session_start();

// Проверка наличия ключа 'IS_AUTH' в пользовательской сессии
if (array_key_exists('IS_AUTH', $_SESSION)) {
    header('Location: index.php'); // Перенаправление на главную страницу, если пользователь уже авторизован
    die(); // Прекращение выполнения скрипта
}
?>
<meta charset="utf-8" />
<title>Авторизация</title>
<link rel="stylesheet" type="text/css" href="styles.css">
<div class="login-page">
    <div class="login-container">
        <form action="login.php" method="POST">
            <div class="title text-centered">
                <span>Авторизация</span>
            </div>
            <div class="component">
                <input name="login" placeholder="Логин">
            </div>
            <div class="component">
                <input name="password" type="password" placeholder="Пароль">
            </div>
            <div class="component">
                <input type="submit" value="Ок">
            </div>
        </form>
    </div>
</div>
