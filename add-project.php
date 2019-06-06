<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

session_start();
if(isset($_SESSION["user"])) {

    require_once("data.php");
    require_once("connection.php");
    require_once("functions.php");
    require_once("helpers.php");
    require_once("aside.php");

    $page = "add-project.php";
    $user_name = $_SESSION["user"];
    $errors = [];

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_project = $_POST;

        /* Валидация */
        /* Проверка на пустое поле */
        if(empty($new_project["name"])) {
            $errors["name"] = "Введите название проекта";
        }

        $name = $new_project["name"];
        /* Запрашивает наличие проекта с введенным именем для данного пользователя*/
        $sql_project = "SELECT p.project_id, p.project_name FROM projects p
                        JOIN users u
                        ON u.user_id = p.user_id
                        WHERE p.user_id = '$user_id'
                        AND p.project_name = '$name'";
        $is_project_name = get_query_row($connect, $sql_project);

        // Проверяет, существует ли проект с таким именем
        if($is_project_name) {
            $errors["name"] = "У Вас уже есть такой проект";
        }
        // Если соответствия в БД нет, добавляет в БД строку с проектом
        else {
            $sql = "INSERT INTO projects (project_name, user_id) VALUES (?, ?)";
            $stmt = db_get_prepare_stmt($connect, $sql, [$name, $user_id]);
            $res = mysqli_stmt_execute($stmt);
            
        }
        
        /* Проверка на наличие ошибок */
        if(count($errors)) {
            /* Показывает пользователю ошибки */
            $page_content = include_template($page, ["errors" => $errors]);
        } 
        /* Либо все его проекты и задачи */
        else header("Location: /index.php");
        
    }

    $page_content = include_template($page, ["errors" => $errors]);
    $layout_content = include_template("layout.php", ["main_content" => $page_content, "title" => $title, "user_name" => $user_name, "projects" => $projects, "tasks" => $all_tasks, "page" => $page]);

    print($layout_content);

} else header("Location: /auth.php");

?>