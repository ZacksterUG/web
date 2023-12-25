<?php
function execute_query($sql, $params = [], $fetch_all = false) {
    $dsn = "pgsql:host=localhost;port=5432;dbname=restaurant;";
    $pdo = new PDO($dsn, 'postgres', '123');
    $stmt = $pdo->prepare($sql);

    if (count($params) > 0) {
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value); // Связывание параметров запроса с их значениями
        }
    }

    $stmt->execute(); // Выполнение подготовленного запроса

    if ($fetch_all) {
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Возврат всех строк результата запроса в виде массива ассоциативных массивов
    } else {
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Возврат одной строки результата запроса в виде ассоциативного массива
    }

    return $result;
}

function execute_query_dml($sql, $params = []) {
    $dsn = "pgsql:host=localhost;port=5432;dbname=restaurant;";
    $pdo = new PDO($dsn, 'postgres', '123');
    $stmt = $pdo->prepare($sql);

    return $stmt->execute($params); // Выполнение подготовленного запроса с передачей параметров
}
?>
