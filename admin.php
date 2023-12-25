<?php
// Подключаем необходимые файлы
include "roles.php";
include "user.php";

// Начинаем сессию
session_start();

// Проверяем, авторизован ли пользователь, иначе перенаправляем на страницу авторизации
if (!array_key_exists('IS_AUTH', $_SESSION)) {
    header('Location: authorization.php');
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Статистика</title>
</head>
<body>
    <div class="main">
        <!-- Верхняя панель (header) -->
        <div class="header">
            <div class="header-item">
                <h2>Вариант 1</h2>
            </div>
            <div class="right-element">
                <div class="header-item">
                    <!-- Отображаем имя пользователя -->
                    <a>Пользователь: </a>
                    <a class="bold">
                        <?php
                        echo $_SESSION['login'];
                        ?>
                    </a>
                </div>
                <div class="header-item" style="place-self: right;">
                    <!-- Форма для выхода из системы -->
                    <form action='logout.php'>
                        <input type='submit' value='Выход'>
                    </form>
                </div>
            </div>
        </div>
      
        <!-- Основной контент (content) -->
        <div class="content">
            <a>Роли вашего пользователя:</a><br>
            <?php
                // Получаем роли пользователя
                $roles = get_user_roles($_SESSION['user_id']);

                // Отображаем роли с возможностью выделения для ADM роли
                foreach ($roles as $key => $role) {
                    $role_name = $role['NAME'];
                    
                    if (strcmp($role['CODE'], 'ADM') == 0) {
                        echo "<a class='bold'>- $role_name</a><br>";
                    } else {
                        echo "<a>- $role_name</a><br>";
                    }
                }

                echo '<br>';

                // Если у пользователя есть ADM или STAT роли, то отображаем пользователей и статистику
                if (has_role($_SESSION['user_id'], 'ADM') || has_role($_SESSION['user_id'], 'STAT')) {
                    $users = get_users();
                    $rows = '';

                    // Формируем таблицу с информацией о пользователях
                    foreach ($users as $key => $user) {
                        $rows .= '
                        <tr>
                            <td>' . $user['LOGIN'] . '</td>
                            <td>' . $user['NAME'] . '</td>
                            <td>' . $user['ROLES'] . '</td>
                        </tr>';
                    }
                    echo '
                        <table>
                            <thead>
                                <tr>
                                    <th>Логин</th>
                                    <th>Имя</th>
                                    <th>Роль</th>
                                <tr/>
                            </thead>
                            <tbody>
                                ' . $rows . '
                            </tbody>
                        </table><br>';

                    $rows = '';
                    $stats = get_stats();

                    // Формируем таблицу с статистикой
                    foreach ($stats as $key => $row) {
                        $rows .= '
                        <tr>
                            <td>' . $row['NAME'] . '</td>
                            <td>' . $row['COUNT'] . '</td>
                        </tr>';
                    }

                    echo '
                        <table>
                            <thead>
                                <tr>
                                    <th>Поле</th>
                                    <th>Кол-во</th>
                                <tr/>
                            </thead>
                            <tbody>
                                ' . $rows . '
                            </tbody>
                        </table>';
                } else {
                    // Если у пользователя нет необходимых прав, выводим сообщение
                    echo '
                    <div class="message">
                        <div class="message-box">
                            <p>У вас недостаточно привилегий</p>
                            <p>для просмотра данной страницы</p>
                        </div>
                    </div>';
                }
            ?>
        </div>
    </div>
</body>
</html>
