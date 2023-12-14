<?php
include "database.php";

function get_user_roles($user_id) {
    $sql = '
        select r."CODE",
               r."NAME" 
          from "USER_ROLES" ur
          join "ROLES" r on ur."ROLE_ID" = r."ID" 
         where ur."USER_ID" = :user_id
         order by r."NAME"';
    $params = [
        'user_id'=> $user_id
    ];
    
    $roles = execute_query($sql, $params, true);

    return $roles;
}

function has_role($user_id, $role) {
    $user_roles = get_user_roles($user_id);

    foreach ($user_roles as $user_role) {
        if(strcmp($role, $user_role['CODE']) == 0) {
            return true;
        }
    }

    return false;
}

function get_stats() {
    $sql = '
     select e."NAME",
            count(m."ID") "COUNT"
       from "EXTRA" e
            left join "MAIN" m ON m."EXTRA_ID" = e."ID"
      group by e."NAME"
      order by count(m."ID") desc
    ';

    $stats = execute_query($sql, [], true);

    return $stats;
}
?>
