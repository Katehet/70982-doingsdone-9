<?php

//Отправляет SQL-запрос и возвращает результат
function get_query_result($connection, $sql) {
    $result = mysqli_query($connection, $sql);
    if (!$result) {
        $error = mysqli_error($connection);
        print_r("Ошибка MySQL: " . $error);
    }
    return $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
};

//Получает количество задач в проекте
function get_tasks_number($arr, $name_of_project) {
    $output = 0;
    foreach ($arr as $key => $value) {
            if ($value["project"] == $name_of_project) {
                $output += 1;
            }
        }
    return $output;
};

//Проверяет срок выполнения задач
date_default_timezone_set('Europe/Moscow');
function time_is_up($value) {
    $date_now = time();
    $task_date = strtotime($value["date"]);
    $diff_in_hours = floor(($task_date - $date_now) / 3600);

    if ($diff_in_hours < 24 && $task_date != "" && !$value["status"]) {
        return true;
    }
    return false;
};

?>