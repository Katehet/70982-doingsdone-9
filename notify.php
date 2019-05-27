<?php

require_once("vendor/autoload.php");
require_once("connection.php");
require_once("functions.php");
require_once("helpers.php");

/* Конфигурация транспорта */
$transport = new Swift_SmtpTransport("phpdemo.ru", 25);
$transport->setUsername("keks@phpdemo.ru");
$transport->setPassword("htmlacademy");

$mailer = new Swift_Mailer($transport);

/* Формирование сообщения */
$sql = "SELECT task_name, task_timeout, u.email, u.user_name FROM tasks t
        JOIN users u ON t.user_id = u.user_id 
        WHERE t.task_status = '0' AND t.task_timeout = CURDATE() 
        ORDER BY t.add_date";

$res = mysqli_query($connect, $sql);

if($res && mysqli_num_rows($res)) {
    $task_list = mysqli_fetch_all($res, MYSQLI_ASSOC);

    $res = mysqli_query($connect, "SELECT email, user_name FROM users");

    if($res && mysqli_num_rows($res)) {
        $users = mysqli_fetch_all($res, MYSQLI_ASSOC);
        $recipients = [];

        /* Получение списка пользователей с email */
        foreach ($users as $user) {
            $recipients[$user["email"]] = $user["user_name"];
        }
        
        foreach ($task_list as $key => $value) {
            $name = $value['user_name'];
            $task = $value['task_name'];
            $time = $value['task_timeout'];
                        
            $message = new Swift_Message();
            $message->setSubject("Уведомление от сервиса «Дела в порядке»");
            $message->setFrom(["keks@phpdemo.ru"=> "Дела в порядке"]);
            $message->setBcc($recipients);
            
            $msg_content = "Уважаемый $name! У вас запланирована задача '$task' на $time";
            $message->setBody($msg_content, "text/plain");
            
            /* Отправка сообщения */
            $result = $mailer->send($message);
        }

        if($result) {
            print("Рассылка успешно отправлена!");
        }
        else {
            print("Не удалось отправить рассылку.");
        }

    }
}

?>