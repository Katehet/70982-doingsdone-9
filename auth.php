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
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$key] = "E-mail должен быть корректным";
            }
        }
    }

    function get_query_row($connection, $sql) {
        $result = mysqli_query($connection, $sql);
        if (!$result) {
            $error = mysqli_error($connection);
            print_r("Ошибка MySQL: " . $error);
        }
        return $data = mysqli_fetch_assoc($result);
    };

    // new data
    $email = $user["email"];
    $pswrd =$user["password"];
    $hash = password_hash($pswrd, PASSWORD_DEFAULT);

    /* Поиск e-mail и пароля в БД */
    $sql_email = "SELECT email, user_password FROM users WHERE email = '$email'";
    $res_user = get_query_row($connect, $sql_email);
    $pass = $res_user["user_password"];

    var_dump(password_verify($hash, $pass));

}

/* Подключает шаблоны страниц на основе результатов запросов в БД */
$page_content = include_template($page, ["user" => $user, "errors" => $errors, "res_user" => $res_user]);

print($page_content);

?>
