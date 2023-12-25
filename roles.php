<?php
// Подключение файла database.php
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
    
    $roles = execute_query($sql, $params, true); // Выполнение запроса для получения ролей пользователя

    return $roles;
}

function has_role($user_id, $role) {
    $user_roles = get_user_roles($user_id); // Получение ролей пользователя по его идентификатору

    foreach ($user_roles as $user_role) {
        if (strcmp($role, $user_role['CODE']) == 0) { // Сравнение кода роли
            return true; // Роль найдена в списке ролей пользователя
        }
    }

    return false; // У пользователя отсутствует указанная роль
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

    $stats = execute_query($sql, [], true); // Выполнение запроса для получения статистики

    return $stats;
}
?>
