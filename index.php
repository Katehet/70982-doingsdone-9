<?php

require_once("connection.php");
require_once("data.php");
require_once("functions.php");
require_once("helpers.php");

/* Получает список из всех проектов для одного пользователя */
$query_proj = "SELECT p.project_id, p.project_name FROM projects p "
            . "JOIN users u "
            . "ON u.user_id = p.user_id "
            . "WHERE u.user_id = $user_id";

$projects = get_query_result($connect, $query_proj);

/* Проверяет параметр запроса в ссылке элемента списка проектов */
if(isset($_GET["project_id"])) {
    $project_id = $_GET["project_id"];
};
/* Получает список задач для выбранного проекта */
$project_tasks = "SELECT t.task_name, t.task_timeout, p.project_name, t.task_status FROM tasks t "
                . "JOIN projects p "
                . "ON t.project_id = p.project_id "
                . "WHERE p.project_id = $project_id";
$tasks_in_project = get_query_result($connect, $project_tasks);

/* Получает список из всех задач для одного пользователя */
$user_tasks = "SELECT t.task_name, t.task_timeout, p.project_name, t.task_status FROM tasks t "
            . "JOIN projects p "
            . "ON t.project_id = p.project_id "
            . "WHERE p.user_id = $user_id";
$tasks_for_user = get_query_result($connect, $user_tasks);

/* Подключает шаблоны страниц на основе результатов запросов в БД */
$page_content = include_template("index.php", ["projects" => $projects, "tasks" => $tasks_in_project, "show_complete_tasks" => $show_complete_tasks]);
$layout_content = include_template("layout.php", ["main_content" => $page_content, "title" => ;$title, "user_name" => $user_name, "projects" => $projects, "tasks" => $tasks_for_user, "project_id" => $project_id]);
print($layout_content);

?>