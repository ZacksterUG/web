<?php
include "database.php"; // Включаем файл с логикой базы данных

session_start(); // Запускаем сессию для отслеживания состояния входа пользователя

if (!array_key_exists('IS_AUTH', $_SESSION)) { // Проверяем авторизацию пользователя
    header('Location: authorization.php'); // Перенаправляем на страницу авторизации, если не авторизован
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') { // Обработка GET-запроса
    if ($_GET["GET_TYPE"] === "RESTAURANTS") { // Если запрос для получения ресторанов
        $sql = '
            select r."ID",
                   r."NAME"
              from "RESTAURANTS" r'; // Формируем SQL-запрос для получения списка ресторанов

        $result = json_encode(execute_query($sql, [], true)); // Выполняем запрос и сериализуем результат в JSON
    } else if ($_GET["GET_TYPE"] === "EXTRAS") { // Если запрос для получения дополнительных элементов
        $sql = '
            select r."ID",
                   r."NAME"
              from "EXTRA" r'; // Формируем SQL-запрос для получения списка дополнительных элементов

        $result = json_encode(execute_query($sql, [], true)); // Выполняем запрос и сериализуем результат в JSON
    } else { // Другой тип запроса
        $sql = '
         select m."ID",
                m."EXTRA_ID",
                e."NAME" "EXTRA_NAME",
                m."VALUE",
                m."DATE_END",
                m."DISPOSABLE",
                m."RESTAURANT_ID",
                r."NAME" "RESTAURANT_NAME"
           from "MAIN" m
                left join "EXTRA" e on e."ID" = m."EXTRA_ID"
                join "RESTAURANTS" r on r."ID" = m."RESTAURANT_ID"'; // Формируем SQL-запрос для получения данных

        $result = json_encode(execute_query($sql, [], true)); // Выполняем запрос и сериализуем результат в JSON
    }

    header("Content-Type: application/json"); // Устанавливаем заголовок ответа как JSON
    echo $result; // Выводим JSON-результат
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') { // Обработка DELETE-запроса
    $MAIN_ID = $_GET["ID"]; // Получаем ID из параметров запроса

    $sql = '
        delete from "MAIN"
        where "MAIN"."ID" = :ID'; // Формируем SQL-запрос для удаления записи

    $params = [
        "ID" => $MAIN_ID
    ]; // Подготавливаем параметры запроса

    $res = execute_query_dml($sql, $params); // Выполняем SQL-запрос

    if (!$res) { // Если запрос выполнен с ошибкой
        $response = json_encode(["ERROR" => "Ошибка в бд"]); // Формируем ответ об ошибке
    } else { // Если запрос выполнен успешно
        $response = json_encode(["OK" => "true"]); // Формируем успешный ответ
    }

    header("Content-Type: application/json"); // Устанавливаем заголовок ответа как JSON
    echo $response; // Выводим ответ в формате JSON
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Обработка POST-запроса
    $MAIN_ID = $_GET["ID"]; // Получаем ID из параметров запроса

    if ($_GET["TYPE"] === "add") { // Если тип запроса - добавление записи
        $sql = '
            insert into "MAIN" ("EXTRA_ID", "VALUE", "DATE_END", "DISPOSABLE", "RESTAURANT_ID")
            values (:EXTRA_ID, :VALUE, :DATE_END, :DISPOSABLE, :RESTAURANT_ID)'; // Формируем SQL-запрос для добавления записи

        $params = [
            "EXTRA_ID" => $_GET["EXTRA_ID"] === "null" ? null : $_GET["EXTRA_ID"],
            "VALUE" => $_GET["VALUE"],
            "DATE_END" => $_GET["DATE_END"],
            "DISPOSABLE" => $_GET["DISPOSABLE"] === "false" ? 0 : 1,
            "RESTAURANT_ID" => $_GET["EXTRA_ID"] !== '1' ? $_GET["RESTAURANT_ID"] : '1'
        ]; // Подготавливаем параметры для SQL-запроса

        $res = execute_query_dml($sql, $params); // Выполняем SQL-запрос

        if (!$res) { // Если запрос выполнен с ошибкой
            $response = json_encode(["ERROR" => "Ошибка в бд"]); // Формируем ответ об ошибке
        } else { // Если запрос выполнен успешно
            $response = json_encode(["OK" => "true"]); // Формируем успешный ответ
        }

        header("Content-Type: application/json"); // Устанавливаем заголовок ответа как JSON
        echo $response; // Выводим ответ в формате JSON
    } else if ($_GET["TYPE"] === "edit") {
        $sql = '
         update "MAIN"
            set "EXTRA_ID" = :EXTRA_ID, 
                "VALUE" = :VALUE,
                "DATE_END" = :DATE_END,
                "DISPOSABLE" = :DISPOSABLE,
                "RESTAURANT_ID" = :RESTAURANT_ID
          where "ID" = :ID';

        $params = [
            "ID" => $MAIN_ID,
            "EXTRA_ID" => $_GET["EXTRA_ID"] === "null" ? null : $_GET["EXTRA_ID"],
            "VALUE" => $_GET["VALUE"],
            "DATE_END" => $_GET["DATE_END"],
            "DISPOSABLE" => $_GET["DISPOSABLE"] === "false" ? 0 : 1,
            "RESTAURANT_ID" => $_GET["EXTRA_ID"] !== 1 ? $_GET["RESTAURANT_ID"] : 1
        ];

        $res = execute_query_dml($sql, $params);

        if (!$res) {
            $response = json_encode(["ERROR" => "Ошибка в бд"]);
        } else {
            $response = json_encode(["OK" => "true"]);
        }

        header("Content-Type: application/json");
        echo $response;
    }
}
