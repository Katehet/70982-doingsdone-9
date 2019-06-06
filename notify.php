<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once "vendor/autoload.php";
require_once "connection.php";
require_once "functions.php";
require_once "helpers.php";

/* Конфигурация транспорта */
$transport = new Swift_SmtpTransport("phpdemo.ru", 25);
$transport->setUsername("keks@phpdemo.ru");
$transport->setPassword("htmlacademy");

$mailer = new Swift_Mailer($transport);

/* Формирование сообщения */
$res = mysqli_query($connect, "SELECT email, user_id, user_name FROM users");

if ($res && mysqli_num_rows($res)) {
    $users = mysqli_fetch_all($res, MYSQLI_ASSOC);

    /* Получение списка пользователей с email */
    foreach ($users as $user) {
        $recipients= [
            $user["email"] => $user["user_name"]
        ];

        $sql = "SELECT task_name, task_timeout FROM tasks 
                WHERE user_id = " . $user['user_id'] . "
                AND task_status = '0' AND task_timeout = CURDATE()";
        $res = mysqli_query($connect, $sql);
        if ($res && mysqli_num_rows($res)) {
            $task_list = mysqli_fetch_all($res, MYSQLI_ASSOC);

            $msg = "Уважаемый(-ая) " . $user['user_name'] . "! У Вас запланированы следующие задачи: ";
            foreach ($task_list as $key => $value) {
                $msg .= "<br>- " . $value['task_name']. " на " . $value['task_timeout'];
            }
            $message = new Swift_Message();
            $message->setSubject("Уведомление от сервиса «Дела в порядке»");
            $message->setFrom(["keks@phpdemo.ru"=> "Дела в порядке"]);
            $message->setBcc($recipients);
            
            $message->setBody($msg, "text/html");
            
            /* Отправка сообщения */
            $result = $mailer->send($message);
            
            if ($result) {
                print("Рассылка успешно отправлена!");
            } else {
                print("Не удалось отправить рассылку.");
            }
        }
    }
}
