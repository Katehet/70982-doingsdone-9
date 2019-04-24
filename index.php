<?php
require("helpers.php");

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

$projects = ["Входящие", "Учеба", "Работа", "Домашние дела", "Авто"];

$tasks = [
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
        "date" => "Нет",
        "project" => "Домашние дела",
        "status" => false
    ],
    [
        "name" => "Заказать пиццу",
        "date" => "Нет",
        "project" => "Домашние дела",
        "status" => false
    ]
];

function get_tasks_number($arr, $name_of_project) {
    $output = 0;
    foreach ($arr as $key => $value) {
            if ($value["project"] == $name_of_project) {
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