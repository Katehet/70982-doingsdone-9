<?php
require_once "connection.php";
require_once "functions.php";
require_once "helpers.php";

$page = "auth.php";
$user = []; // Для данных пользователя
$errors = []; // Для ошибок
/* Валидация */
/* Проверка отправки данных из формы */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = $_POST;

    /* Проверка на заполнение обязательных полей */
    $required = ["email", "password"];
    
    /* Массив для хранения ошибок */
    $errors = fill_this_fields($user, $required);

    /* Данные от пользователя */
    $email = mysqli_real_escape_string($connect, $user['email']);
    $pswrd = htmlspecialchars($user["password"]);

    /* Поиск записей в БД по введённому e-mail  */
    $sql_email = "SELECT email, user_password, user_name, user_id FROM users WHERE email = '$email'";
    $res_user = get_query_row($connect, $sql_email);
    
    /* Проверка выполнения запроса e-mail */
    if ($res_user) {
        $pass = $res_user["user_password"];
        
        /* Сверка паролей */
        if (password_verify($pswrd, $pass)) {
            /* Старт новой сессии и редирект в случае успеха сверки паролей*/
            session_start();
            $_SESSION["user"] = $res_user["user_name"];
            $_SESSION["email"] = $res_user["email"];
            $_SESSION["ID"] = $res_user["user_id"];
            header("Location: /index.php");
        } else {
            /* Запись ошибки, если пароль неверный */
            $errors["password"] = "Пароль неверный!";
        }
       
        /* Запись ошибки, если e-mail корректен, но не найден в БД */
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) && ($email != "")) {
        $errors["email"] = "E-mail не найден";
    }

    /* Проверка на наличие ошибок */
    if (count($errors)) {
        $page_content = include_template($page, ["user" => $user, "errors" => $errors]);
    }
}

/* Подключает шаблоны страниц на основе результатов запросов в БД */
$page_content = include_template($page, ["user" => $user, "errors" => $errors]);

print($page_content);
