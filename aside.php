<?php

/* Получает список из всех проектов для одного пользователя */
$query_proj = "SELECT p.project_id, p.project_name FROM projects p
               JOIN users u
               ON u.user_id = p.user_id
               WHERE u.user_id = '$user_id'";

$projects = get_query_result($connect, $query_proj);

/* Получает список из всех задач для одного пользователя */
$query_tasks = "SELECT t.task_name, t.task_timeout, p.project_name, t.task_status, t.task_file, t.task_id FROM tasks t
                JOIN projects p
                ON t.project_id = p.project_id
                WHERE p.user_id = '$user_id'";

/* Получает массив всех задач пользователя (для меню проектов) */
$all_tasks = get_query_result($connect, $query_tasks);

?>
