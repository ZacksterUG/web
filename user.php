<?php
function get_users() {
    session_start();

    if (!array_key_exists('IS_AUTH', $_SESSION)
        || !(has_role($_SESSION['user_id'], 'ADM') 
             || has_role($_SESSION['user_id'], 'STAT'))) {
        return null;
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

    $users = execute_query($sql, [], true);

    return $users;
}

?>
