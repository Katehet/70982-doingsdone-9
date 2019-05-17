<?php

require_once("connection.php");
require_once("functions.php");
require_once("helpers.php");

$page = "auth.php";
/* Валидация */
/* Проверка отправки данных из формы */
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST;

    /* Проверка на заполнение обязательных полей */
    $required = ["email", "password"];
    $errors = []; // Создает массив для хранения ошибок

    foreach($required as $field) {
        if(empty($_POST[$field])) {
            $errors[$field] = "Поле должно быть заполнено";
        }
    }

    /* Проверка корректного ввода email */
    foreach ($_POST as $key => $value) {
        if ($key == "email") {
            if (!filter_var($value, FILTER_VALIDATE_EMAIL) && ($value != "")) {
                $errors[$key] = "E-mail должен быть корректным";
            }
        }
    }

    // Данные от пользователя
    $email = $user["email"];
    $pswrd =$user["password"];

    /* Поиск записей в БД по введённому e-mail  */
    $sql_email = "SELECT email, user_password, user_name, user_id FROM users WHERE email = '$email'";
    $res_user = get_query_row($connect, $sql_email);
    
    /* Проверка выполнения запроса e-mail */
    if($res_user) {
        $pass = $res_user["user_password"];
        
        /* Сверка паролей */
        if(password_verify($pswrd, $pass)) {
            /* Старт новой сессии и редирект в случае успеха сверки паролей*/
            session_start();
            $_SESSION["user"] = $res_user["user_name"];
            $_SESSION["email"] = $res_user["email"];
            $_SESSION["ID"] = $res_user["user_id"];
            header("Location: /index.php");

          /* Запись ошибки, если пароль неверный */
        } else $errors["password"] = "Пароль неверный!";
       
      /* Запись ошибки, если e-mail корректен, но не найден в БД */      
    } elseif(filter_var($email, FILTER_VALIDATE_EMAIL) && ($email != "")) {
        $errors["email"] = "E-mail не найден";
    }

    /* Проверка на наличие ошибок */
    if(count($errors)) {
        $page_content = include_template($page, ["user" => $user, "errors" => $errors]);
    }

}

/* Подключает шаблоны страниц на основе результатов запросов в БД */
$page_content = include_template($page, ["user" => $user, "errors" => $errors]);

print($page_content);

?>
