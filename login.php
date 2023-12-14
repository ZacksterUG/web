<title>Авторизация</title>
<link rel="stylesheet" type="text/css" href="styles.css">
<?php
include 'database.php';

session_start();

if (array_key_exists('IS_AUTH', $_SESSION)) {
    header('Location: index.php');
    die();
}

$sql = '
    select u."NAME", 
           u."ID" 
      from "USERS" u 
     where u."LOGIN" = :login 
       and u."PASS" = :password';
$query_params = [
    'login' => $_POST['login'],
    'password' => $_POST['password']
];

$row = execute_query($sql, $query_params);

if ($row) {
    $_SESSION['IS_AUTH'] = true;
    $_SESSION['name'] = $row['NAME'];
    $_SESSION['user_id'] = $row['ID'];
    $_SESSION['login'] = $_POST['login'];

    header('Location: index.php');
    die();
} else {
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
