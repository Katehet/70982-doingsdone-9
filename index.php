<?php

require_once("connection.php");
require_once("data.php");
require_once("functions.php");
require_once("helpers.php");

/* Получить список из всех проектов для одного пользователя */
$query_proj = "SELECT p.project_id, p.project_name FROM projects p "
            . "JOIN users u "
            . "ON u.user_id = p.user_id "
            . "WHERE u.user_id = $user_id";

$projects = get_query_result($connect, $query_proj);

$project_id;

if(isset($_GET["project_id"])) {
    $project_id = $_GET["project_id"];

    /* Получить список задач для выбранного проекта */
    $project_tasks = "SELECT t.task_name, t.task_timeout, p.project_name, t.task_status FROM tasks t "
                . "JOIN projects p "
                . "ON t.project_id = p.project_id "
                . "WHERE p.project_id = $project_id";
}

$tasks_in_project = get_query_result($connect, $project_tasks);

/* Получить список из всех задач для одного пользователя */
$user_tasks = "SELECT t.task_name, t.task_timeout, p.project_name, t.task_status FROM tasks t "
            . "JOIN projects p "
            . "ON t.project_id = p.project_id "
            . "WHERE p.user_id = $user_id";

$tasks_for_user = get_query_result($connect, $user_tasks);

$page_content = include_template("index.php", ["projects" => $projects, "tasks" => $tasks_in_project, "show_complete_tasks" => $show_complete_tasks]);
$layout_content = include_template("layout.php", ["main_content" => $page_content, "title" => "Дела в порядке", "user_name" => $user_name, "projects" => $projects, "tasks" => $tasks_for_user]);
print($layout_content);

?>