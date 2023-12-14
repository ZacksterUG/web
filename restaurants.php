<?php
if (!array_key_exists('IS_AUTH', $_SESSION)) {
    header('Location: authorization.php');
    die();
}



?>

