<?php
include 'database.php'; // Подключение файла с функциями для работы с базой данных

session_start(); // Начало новой пользовательской сессии

// Проверка наличия ключа 'IS_AUTH' в пользовательской сессии
if (array_key_exists('IS_AUTH', $_SESSION)) {
    header('Location: index.php'); // Перенаправление на главную страницу, если пользователь уже авторизован
    die(); // Прекращение выполнения скрипта
}

// SQL-запрос для получения имени и идентификатора пользователя, в зависимости от введенного логина и пароля
$sql = '
    select u."NAME", 
           u."ID" 
      from "USERS" u 
     where u."LOGIN" = :login 
       and u."PASS" = :password';
       
// Параметры для подстановки в SQL-запрос
$query_params = [
    'login' => $_POST['login'],
    'password' => $_POST['password']
];

// Выполнение запроса
$row = execute_query($sql, $query_params);

// Если результат запроса не пустой
if ($row) {
    // Задание переменных сессии для хранения информации об авторизованном пользователе
    $_SESSION['IS_AUTH'] = true;
    $_SESSION['name'] = $row['NAME'];
    $_SESSION['user_id'] = $row['ID'];
    $_SESSION['login'] = $_POST['login'];

    header('Location: index.php'); // Перенаправление на главную страницу
    die(); // Прекращение выполнения скрипта
} else { // Если результат запроса пустой, вывод сообщения об ошибке авторизации
    echo '
<div class="login-page">
    <div class="login-container">
        <form action="authorization.php">
            <div class="title text-centered">
                <span>Авторизация</span>
            </div>
            <p class="error text-centered">Неверные логин или пароль!</p><br/>
            <div class="component">
                <input type="submit" value="Вернуться">
            </div>
        </form>
    </div>
</div>';
}
?>
