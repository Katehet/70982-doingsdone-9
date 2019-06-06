<?php

/**
* Отправляет SQL-запрос и возвращает результат 
* в виде ассоциативного массива
* @param object $connection Ресурс соединения с БД
* @param string $sql SQL-запрос в БД
* @return array $data Ассоциативный массив - результат запроса в БД
*/
function get_query_result($connection, string $sql) {
    $result = mysqli_query($connection, $sql);
    if (!$result) {
        $error = mysqli_error($connection);
        print_r("Ошибка MySQL: " . $error);
    }
    return $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
};

/**
* Отправляет SQL-запрос и возвращает результат 
* в виде одномерного массива
* @param object $connection Ресурс соединения с БД
* @param string $sql SQL-запрос в БД
* @return array $dta Одномерный массив - результат запроса в БД
*/
function get_query_row($connection, string $sql) {
    $result = mysqli_query($connection, $sql);
    if (!$result) {
        $error = mysqli_error($connection);
        print_r("Ошибка MySQL: " . $error);
    }
    return $data = mysqli_fetch_assoc($result);
};

/**
* Получает количество задач в проекте
* @param array $arr Ассоциативный массив всех задач пользователя
* @param string $name_of_project Название интересующего проекта
* @return int $output Количество задач
*/
function get_tasks_number(array $arr, string $name_of_project) {
    $output = 0;
    foreach ($arr as $key => $value) {
            if ($value["project_name"] == $name_of_project) {
                $output += 1;
            }
        }
    return $output;
};

/**
* Проверяет срок выполнения задач
* @param array $value Ассоциативный массив - проверяемая задача
* @return bool true Если до времени выполнения задачи больше суток, инача false
*/
date_default_timezone_set('Europe/Moscow');
function time_is_up(array $value) {
    $date_now = time();
    $task_date = strtotime($value["task_timeout"]);
    $diff_in_hours = floor(($task_date - $date_now) / 3600);

    if ($diff_in_hours < 24 && $task_date != "" && !$value["status"]) {
        return true;
    }
    return false;
};

/**
* Проверяет передаваемые поля на заполнение
* @param array $input_data Ассоциативный массив - GET-запрос
* @param array $fields Одноммерный массив с именами обязательных полей
* @return array $errors Ассоциативный массив с текстом ошибок заполнения
*/
function fill_this_fields(array $input_data, array $fields, $errors){

    /* Проверка заполнения обязательных полей */
    foreach($fields as $field) {
        if(empty($input_data[$field])) {
            $errors[$field] = "Поле должно быть заполнено";
        }
    }

    /* Проверка корректного ввода email */
    foreach ($input_data as $key => $value) {
        if ($key == "email") {
            if (!filter_var($value, FILTER_VALIDATE_EMAIL) && ($value != "")) {
                $errors[$key] = "E-mail должен быть корректным";
            }
        }
    }
    return $errors;
};
?>