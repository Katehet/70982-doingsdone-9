<?php

require_once("data.php");
require_once("connection.php");
require_once("functions.php");
require_once("helpers.php");
require_once("aside.php");

$page = "add.php";

$page_content = include_template($page, ["projects" => $projects]); // $page = "add.php"

$layout_content = include_template("layout.php", ["main_content" => $page_content, "title" => $title, "user_name" => $user_name, "projects" => $projects, "tasks" => $all_tasks]);
print($layout_content);

?>