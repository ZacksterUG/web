<?php
function get_users() {
    session_start(); // Начало сессии для работы с пользовательской сессией

    // Проверка наличия ключа 'IS_AUTH' в пользовательской сессии и наличия определенных ролей у текущего пользователя
    if (!array_key_exists('IS_AUTH', $_SESSION) ||
        !(has_role($_SESSION['user_id'], 'ADM') ||
          has_role($_SESSION['user_id'], 'STAT'))) {
        return null; // Если текущий пользователь не авторизован или не обладает требуемыми ролями, возвращается null
    }

    $sql = '
     select u."LOGIN",
            u."NAME",
            string_agg(r."NAME", \', \') "ROLES"
        from "USERS" u
             join "USER_ROLES" ur on ur."USER_ID" = u."ID"
             join "ROLES" r ON r."ID" = ur."ROLE_ID"
        group by u."LOGIN", u."NAME"
        order by u."NAME"
    ';

    $users = execute_query($sql, [], true); // Выполнение запроса для извлечения списка пользователей

    return $users; // Возврат списка пользователей
}

?>
