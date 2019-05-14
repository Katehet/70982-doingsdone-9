<?php

require_once("connection.php");
require_once("data.php");
require_once("functions.php");
require_once("helpers.php");

$page = "register.php";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_user = $_POST;

    $required = ["email", "password", "name"];
    $errors = [];

    /* Проверка заполнения обязательных полей */
    foreach($required as $field) {
        if(empty($_POST[$field])) {
            $errors[$field] = "Поле пустое!";
        }
    }

    /* Проверка корректного ввода email */
    foreach ($_POST as $key => $value) {
        if ($key == "email") {
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$key] = "Email должен быть корректным";
            }
        }
    }

    /* Проверка на наличие ошибок */
    if(count($errors)) {
        /* Вывод ошибок */
        $page_content = include_template($page, ["new_user" => $new_user, "errors" => $errors]);
    }
    else {
        /* Либо загрузка данных из формы в БД*/
        $pass_hash = password_hash($new_user["password"], PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (reg_date, email, user_name, user_password) VALUES (NOW(), ?, ?, ?)";
        $stmt = db_get_prepare_stmt($connect, $sql, [$new_user["email"], $new_user["name"], $pass_hash]);
        $res = mysqli_stmt_execute($stmt);

        /* В случае успеха заргузки данных перенаправляет на главную */
        header("Location: /index.php");
    }
}

/* Подключает шаблоны страниц на основе результатов запросов в БД */
$page_content = include_template($page, ["new_user" => $new_user, "errors" => $errors]);

print($page_content);
?>
