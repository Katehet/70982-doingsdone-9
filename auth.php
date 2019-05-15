<?php

require_once("connection.php");
require_once("functions.php");
require_once("helpers.php");

$page = "auth.php";

/* Подключает шаблоны страниц на основе результатов запросов в БД */
$page_content = include_template($page, ["new_user" => $new_user, "errors" => $errors]);

print($page_content);

?>
