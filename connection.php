<?php

//Проверяет соединение с БД
$connect = mysqli_connect("localhost", "root", "", "doingsdone");

if (!$connect) {
    print("Ошибка подключения: " . mysqli_connect_error());
}
mysqli_set_charset($connect, "utf8");

?>