<?php
include "roles.php";
include "user.php";

session_start();

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
    <title>Основная страница</title>
</head>
<body>
    <div class="main">
        <div class="header">
            <div class="header-item">
                <h2>Вариант 1</h2>
            </div>
            <div class="right-element">
                <div class="header-item">
                    <a>Пользователь: </a>
                    <a class="bold">
                        <?php
                        echo $_SESSION['login'];
                        ?>
                    </a>
                </div>
                <div class="header-item" style="place-self: right;">
                    <form action='logout.php'>
                        <input type='submit' value='Выход'>
                    </form>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="content-container">
                <div class="content-box bordered">
                    <table style="width: 100%;">
                        <thead>
                            <th>Код купон</th>
                            <th>Дата окончания действия</th>
                            <th>Одноразовый</th>
                            <th>Ресторан</th>
                            <th>Город</th>
                            <th style="width: 30px;"></th>
                        </thead>
                        <tbody class="content-rows">
                        </tbody>
                    </table>
                </div>
                <div class="content-editor">
                    <div class="content-editor-panel bordered">
                        <div class="title text-centered">
                            <a>Изменение записи</a>
                        </div>
                        <p/>
                        <div class="input-content" >
                            <label>Код купона:</label>
                            <input type="text" name="value" placeholder="7070"/>
                            <br>
                            <label>Дата окончания действия:</label>
                            <input type="date" name="dateEnd"/>
                            <br>
                            <label>Одноразовый:</label>
                            <input type="checkbox" style="width: 15px;" name="disposable">
                            <br>
                            <label>Ресторан:</label>
                            <select type="text" name="restaurantId"></select>
                            <br>
                            <label>Город</label>
                            <select type="text" name="extraId"></select>
                            <br>
                            <div style="text-align: right;">
                                <button class="input-btn" onclick="hideInputData()">Отмена</button>
                                <button class="input-btn" name="addEditBtn" onclick="handleClick()">Добавить</button>
                            </div>
                        </div>
                    </div>
                    <div class="content-editor-add">
                        <button class="input-btn" onclick="showInputData()">Добавить</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="script.js"></script>
</html>
