<?php

require_once("helpers.php");

$page = "guest.php";

$guest_page = include_template($page, []);
$layout_content = include_template("layout.php", ["guest_page" => $guest_page, "title" => $title]);

print($layout_content);

?>
