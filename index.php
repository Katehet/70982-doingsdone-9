<?php

require("helpers.php");

//Соединение с БД
$connect = mysqli_connect("localhost", "root", "", "doingsdone");
if (!$connect) {
    print("Ошибка подключения: " . mysqli_connect_error());
}
mysqli_set_charset($connect, "utf8");

/* Получить список из всех проектов для одного пользователя */
$query_proj = "SELECT p.project_name FROM projects p JOIN users u ON u.user_id = p.user_id WHERE u.user_id = 1";
$result_proj = mysqli_query($connect, $query_proj);
if (!$result_proj) {
    $error = mysqli_error($connect);
    print_r("Ошибка MySQL: " . $error);
}
$projects = mysqli_fetch_all($result_proj, MYSQLI_ASSOC);

/* Получить список из всех задач для одного пользователя */
$query_tasks = "SELECT t.task_name, t.task_timeout, p.project_name, t.task_status FROM tasks t JOIN projects p ON t.project_id = p.project_id WHERE p.user_id = 1";
$result_tasks = mysqli_query($connect, $query_tasks);
if (!$result_tasks) {
    $error = mysqli_error($connect);
    print_r("Ошибка MySQL: " . $error);
}
$tasks = mysqli_fetch_all($result_tasks, MYSQLI_ASSOC);

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

$rojects = ["Входящие", "Учеба", "Работа", "Домашние дела", "Авто"];

$asks = [
    [
        "name" => "Собеседование в IT компании",
        "date" => "01.12.2019",
        "project" => "Работа",
        "status" => false
    ],
    [
        "name" => "Выполнить тестовое задание",
        "date" => "25.12.2018",
        "project" => "Работа",
        "status" => false
    ],
    [
        "name" => "Сделать задание первого раздела",
        "date" => "21.12.2018",
        "project" => "Учеба",
        "status" => true
    ],
    [
        "name" => "Встреча с другом",
        "date" => "22.12.2018",
        "project" => "Входящие",
        "status" => false
    ],
    [
        "name" => "Купить корм для кота",
        "date" => NULL,
        "project" => "Домашние дела",
        "status" => false
    ],
    [
        "name" => "Заказать пиццу",
        "date" => NULL,
        "project" => "Домашние дела",
        "status" => false
        ]
    ];
    
function get_tasks_number($arr, $name_of_project) {
    $output = 0;
    foreach ($arr as $key => $value) {
            if ($value["project_name"] == $name_of_project) {
                $output += 1;
            }
        }
    return $output;
};

$page_content = include_template("index.php", ["projects" => $projects, "tasks" => $tasks, "show_complete_tasks" => $show_complete_tasks]);
$layout_content = include_template("layout.php", ["main_content" => $page_content, "title" => "Дела в порядке", "projects" => $projects, "tasks" => $tasks]);
print($layout_content);

date_default_timezone_set('Europe/Moscow');

function time_is_up($value) {
    $date_now = time();
    $task_date = strtotime($value["date"]);
    $diff_in_hours = floor(($task_date - $date_now) / 3600);

    if ($diff_in_hours < 24 && $task_date != "" && !$value["status"]) {
        return true;
    }
    return false;
}
?>