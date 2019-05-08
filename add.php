<?php

require_once("data.php");
require_once("connection.php");
require_once("functions.php");
require_once("helpers.php");

$page = "add.php";

// vvv FROM INDEX.PHP VVV
/* Получает список из всех проектов для одного пользователя */
$query_proj = "SELECT p.project_id, p.project_name FROM projects p 
               JOIN users u
               ON u.user_id = p.user_id
               WHERE u.user_id = $user_id";

$projects = get_query_result($connect, $query_proj);

/* Получает список из всех задач для одного пользователя */
$query_tasks = "SELECT t.task_name, t.task_timeout, p.project_name, t.task_status FROM tasks t
                JOIN projects p
                ON t.project_id = p.project_id
                WHERE p.user_id = $user_id";

/* Получает массив всех задач пользователя (для меню проектов) */
$all_tasks = get_query_result($connect, $query_tasks);

$page_content = include_template($page, []); // $page = "add.php"

$layout_content = include_template("layout.php", ["main_content" => $page_content, "title" => $title, "user_name" => $user_name, "projects" => $projects, "tasks" => $all_tasks]);
print($layout_content);

?>