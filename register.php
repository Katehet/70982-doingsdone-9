<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once "connection.php";
require_once "functions.php";
require_once "helpers.php";

$page = "register.php";
$new_user = [];
$errors = []; // Массив для хранения ошибок
/* Валидация */
/* Проверка отправки данных из формы */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_user = $_POST;

    /* Проверка на заполнение обязательных полей */
    $required = ["email", "password", "name"];

    /* Проверка заполнения полей */
    $errors = fill_this_fields($new_user, $required);

    /* Данные от пользователя */
    $email = $new_user["email"];
    $name = strip_tags($new_user["name"]);


    /* Поиск записей в БД по введённому e-mail  */
    $sql_email = "SELECT email FROM users WHERE email = '$email'";
    $res_email = get_query_result($connect, $sql_email);

    /* Проверка выполнения запроса e-mail */
    if ($res_email) {
        $errors["email"] = "Пользователь с таким адресом уже существует";
    }

    /* Проверка на наличие ошибок */
    if (count($errors)) {
        /* Вывод ошибок */
        $page_content = include_template($page, ["new_user" => $new_user, "errors" => $errors]);
    } else {
        /* Либо загрузка данных из формы в БД*/
        $pass_hash = password_hash($new_user["password"], PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (reg_date, email, user_name, user_password) VALUES (NOW(), ?, ?, ?)";
        $stmt = db_get_prepare_stmt($connect, $sql, [$new_user["email"], $name, $pass_hash]);
        $res = mysqli_stmt_execute($stmt);

        /* В случае успеха заргузки данных перенаправляет на главную */
        header("Location: /index.php");
    }
}

/* Подключает шаблоны страниц на основе результатов запросов в БД */
$page_content = include_template($page, ["new_user" => $new_user, "errors" => $errors]);

print($page_content);
