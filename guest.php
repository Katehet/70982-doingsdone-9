<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once "helpers.php";
require_once "data.php";

$page = "guest.php";

$guest_page = include_template($page, []);
$layout_content = include_template("layout.php", ["guest_page" => $guest_page, "title" => $title]);

print($layout_content);
