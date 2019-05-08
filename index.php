<?php

require_once("connection.php");
require_once("data.php");
require_once("functions.php");
require_once("helpers.php");

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

/*
  При клике на название проекта нужно 
  выводить на главной задачи из проекта
*/
/* Проверяет параметр запроса в ссылке элемента списка проектов */
if(isset($_GET["project_id"])) {
    $project_id = intval($_GET["project_id"]);

    /* Создает список проектов с id из запроса, созданных пользователем */
    $id_pojects_list = "SELECT project_id FROM projects WHERE user_id = $user_id AND project_id = $project_id";
    $id_list = get_query_result($connect, $id_pojects_list);
    
    /* Проверяет полученный массив */
    if(empty($id_list)) {
        http_response_code(404); // если проекта с id = $project_id у пользователя $user_id не существует
        $page = "404.php";      // переадресуем его на страницу ошибки
    }
    else {
        /* Фильтрует список задач по id пользователя */
        $query_tasks .= " AND p.project_id = $project_id";
    }
};

/* Получает массив задач для вывода на главной */
$tasks = get_query_result($connect, $query_tasks);

/* Подключает шаблоны страниц на основе результатов запросов в БД */
$page_content = include_template($page, ["projects" => $projects, "tasks" => $tasks, "show_complete_tasks" => $show_complete_tasks]);
$layout_content = include_template("layout.php", ["main_content" => $page_content, "title" => $title, "user_name" => $user_name, "project_id" => $project_id, "projects" => $projects, "tasks" => $all_tasks]);
print($layout_content);

?>