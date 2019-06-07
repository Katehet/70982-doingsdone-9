<?php

//Переменные
$page = "index.php";
$title = "Дела в порядке";
$user_id = isset($_SESSION["ID"]) ? intval($_SESSION["ID"]) : "";
$user_name = isset($_SESSION["user"]) ? strip_tags($_SESSION["user"]) : "";

// Показывать или нет выполненные задачи
$show_complete_tasks = $_GET["show_completed"] ?? 0;
