<?php
function execute_query($sql, $params = [], $fetch_all = false) {
    $dsn = "pgsql:host=localhost;port=5432;dbname=restaurant;";
    $pdo = new PDO($dsn, 'postgres', '123');
    $stmt = $pdo->prepare($sql);

    if(count($params) > 0) {
        foreach ($params as $key => $value) {
            $stmt->bindValue("$key", $value);
        }
    }

    $stmt->execute();

    if ($fetch_all) {
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    return $result;
}
?>
