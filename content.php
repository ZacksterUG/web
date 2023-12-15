<?php
include "database.php";

session_start();

if (!array_key_exists('IS_AUTH', $_SESSION)) {
    header('Location: authorization.php');
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if($_GET["GET_TYPE"] === "RESTAURANTS") {
        $sql = '
            select r."ID",
                   r."NAME"
              from "RESTAURANTS" r';
        
        $result = json_encode(execute_query($sql, [], true));
    } else if($_GET["GET_TYPE"] === "EXTRAS") {
        $sql = '
            select r."ID",
                   r."NAME"
              from "EXTRA" r';
        
        $result = json_encode(execute_query($sql, [], true));
    } else {
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
                join "RESTAURANTS" r on r."ID" = m."RESTAURANT_ID"';
  
        $result = json_encode(execute_query($sql, [], true));
    }    

    header("Content-Type: application/json");
    echo $result;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') { 
    $MAIN_ID = $_GET["ID"];
    
    $sql = '
        delete from "MAIN"
        where "MAIN"."ID" = :ID';
    
    $params = [
        "ID" => $MAIN_ID
    ];

    $res = execute_query_dml($sql, $params);

    if(!$res) {
        $response = json_encode(["ERROR" => "Ошибка в бд"]);
    } else {
        $response = json_encode(["OK" => "true"]);
    }

    header("Content-Type: application/json");
    echo $response;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $MAIN_ID = $_GET["ID"];

    if($_GET["TYPE"] === "add") {
        $sql = '
            insert into "MAIN" ("EXTRA_ID", "VALUE", "DATE_END", "DISPOSABLE", "RESTAURANT_ID")
            values (:EXTRA_ID, :VALUE, :DATE_END, :DISPOSABLE, :RESTAURANT_ID)';

        $params = [
            "EXTRA_ID" => $_GET["EXTRA_ID"] === "null" ? null : $_GET["EXTRA_ID"], 
            "VALUE" => $_GET["VALUE"], 
            "DATE_END" => $_GET["DATE_END"], 
            "DISPOSABLE" => $_GET["DISPOSABLE"] === "false" ? 0 : 1, 
            "RESTAURANT_ID" => $_GET["EXTRA_ID"] !== '1' ? $_GET["RESTAURANT_ID"] : '1'
        ];

        $res = execute_query_dml($sql, $params);

        if(!$res) {
            $response = json_encode(["ERROR" => "Ошибка в бд"]);
        } else {
            $response = json_encode(["OK" => "true"]);
        }
    
        header("Content-Type: application/json");
        echo $response;
    } else if($_GET["TYPE"] === "edit") {
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

        if(!$res) {
            $response = json_encode(["ERROR" => "Ошибка в бд"]);
        } else {
            $response = json_encode(["OK" => "true"]);
        }
    
        header("Content-Type: application/json");
        echo $response;
    }
}


?>