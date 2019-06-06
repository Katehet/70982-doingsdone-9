<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once("vendor/autoload.php");

session_start();
if(isset($_SESSION["user"])) {

require_once("connection.php");
require_once("data.php");
require_once("functions.php");
require_once("helpers.php");
require_once("aside.php");

/*
Фильтр задач
*/
/* По запросу формирует соответствующий список задач */
if(isset($_GET["tab"])) {
    $tab = $_GET["tab"];
    switch($tab) {
        case "today":
            $tab = " = CURRENT_DATE";
            break;
            case "tomorrow":
            $tab = " = DATE_ADD(CURRENT_DATE, INTERVAL 1 DAY)";
            break;
            case "expired":
            $tab = " < CURRENT_DATE";
            break;
    }
    $query_tasks .= " AND t.task_timeout" . "$tab";
}

/*
Отмечать задачи как выполненные
*/
/* По запросу  и переданному id обновляет статус задачи */
if(isset($_GET["check"])) {
    $checked = intval($_GET["check"]);
    $task_id = intval($_GET["task_id"]);
    $update_status = "UPDATE tasks SET task_status = '$checked' WHERE task_id = '$task_id'";
    
    $result = mysqli_query($connect, $update_status);
    if (!$result) {
        $error = mysqli_error($connect);
        print_r("Ошибка MySQL: " . $error);
    }
}

/*
При клике на название проекта нужно
выводить на главной задачи из проекта
*/
/* Проверяет параметр запроса в ссылке элемента списка проектов */
$project_id = 0;
if(isset($_GET["project_id"])) {
    $project_id = intval($_GET["project_id"]);

    /* Создает список проектов с id из запроса, созданных пользователем */
    $id_pojects_list = "SELECT project_id FROM projects WHERE user_id = $user_id AND project_id = '$project_id'";
    $id_list = get_query_result($connect, $id_pojects_list);

    /* Проверяет полученный массив */
    if(empty($id_list)) {
        http_response_code(404); // если проекта с id = $project_id у пользователя $user_id не существует
        $page = "404.php";      // переадресуем его на страницу ошибки
    }
    else {
        /* Фильтрует список задач по id пользователя */
        $query_tasks .= " AND p.project_id = '$project_id'";
    }
};

/* Получает массив задач для вывода на главной */
$tasks = get_query_result($connect, $query_tasks);

/*
Поиск по задачам
*/
/* Выполняет поиск задачи по названию */
$search = $_GET["search"] ?? "";

if($search) {
    $search = trim($search);
    $sql = $query_tasks . " AND MATCH(task_name) AGAINST(?)";

    $stmt = db_get_prepare_stmt($connect, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);
};

/* Подключает шаблоны страниц на основе результатов запросов в БД */
$page_content = include_template($page, ["projects" => $projects, "tasks" => $tasks, "show_complete_tasks" => $show_complete_tasks]);
$layout_content = include_template("layout.php", ["main_content" => $page_content, "title" => $title, "user_name" => $user_name, "project_id" => $project_id, "projects" => $projects, "tasks" => $all_tasks]);
print($layout_content);

} 
else {
    header("Location: /guest.php");
}
?>
