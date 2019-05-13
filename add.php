<?php

require_once("data.php");
require_once("connection.php");
require_once("functions.php");
require_once("helpers.php");
require_once("aside.php");

$page = "add.php";
/* Валидация */
/* Проверка отправки данных из формы */
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_task = $_POST;

    /* Проверка на заполнение обязательных полей */
    $required = ["name", "project"];
    $errors = []; // Создает массив для хранения ошибок

    foreach($required as $field) {
        if(empty($_POST[$field])) {
            $errors[$field] = "Поле должно быть заполнено";
        }
    }

    if(($new_task["date"])) {
        /* Проверка формата и актуальности даты, если она указана*/
        $today = time();
        $task_day = strtotime( $new_task["date"]);
        $diff = floor(($today - $task_day) / 86400);

        if(!is_date_valid($new_task["date"])) {
            $errors["date_format"] = "Введите дату в формате ГГГГ-ММ-ДД";
        }

        elseif ($diff > 0) {
            $errors["date_exp"] = "Вы не можете спланировать прошлое :)";
        }
    } else {
        $new_task["date"] = NULL;
    }

    /* Проверка заргузки файла и его перемещение */
    if(isset($_FILES["file"]["name"])) {
        $tmp_name = $_FILES["file"]["tmp_name"];
        $file_name = $_FILES["file"]["name"];
        $path = __DIR__ . "/uploads/";
        $task_link = "/uploads/" . $_FILES["file"]["name"];

        move_uploaded_file($tmp_name, $path . $file_name);
    }

    /* Выводит ошибки из массива в случае их наличия */
    if(count($errors)) {
        $page_content = include_template($page, ["new_task" => $new_task, "errors" => $errors]);
    }
    else {
        /* Загрузка данных из формы в БД*/
        $sql = "INSERT INTO tasks (add_date, task_status, task_name, task_file, task_timeout, user_id, project_id) VALUES (NOW(), 0, ?, ?, ?, 1, ?)";
        $stmt = db_get_prepare_stmt($connect, $sql, [$new_task["name"], $task_link, $new_task["date"], $new_task["project"]]);
        $res = mysqli_stmt_execute($stmt);

        header("Location: /index.php"); // В случае успеха заргузки данных перенаправляет на главную
    }

}

/* Подключает шаблоны страниц формы и разметки */
$page_content = include_template($page, ["projects" => $projects,"new_task" => $new_task, $task_link, "errors" => $errors]);
$layout_content = include_template("layout.php", ["main_content" => $page_content, "title" => $title, "user_name" => $user_name, "projects" => $projects, "tasks" => $all_tasks]);

print($layout_content);

?>
