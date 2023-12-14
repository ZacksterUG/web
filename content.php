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
    } else if($_GET["GET_TYPE"] === "EXTRA") {
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
                join "EXTRA" e on e."ID" = m."EXTRA_ID"
                join "RESTAURANTS" r on r."ID" = m."RESTAURANT_ID"';
  
        $result = json_encode(execute_query($sql, [], true));
    }    

    header("Content-Type: application/json");
    echo $result;
}


?>