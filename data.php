<?php

//Переменные
$page = "index.php";
$title = "Дела в порядке";
$user_id = $_SESSION["ID"] ?? "";
$user_name = $_SESSION["user"] ?? "";

// Показывать или нет выполненные задачи
$show_complete_tasks = $_GET["show_completed"] ?? 0;

?>