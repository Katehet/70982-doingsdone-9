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
$sql = "SELECT task_name, task_timeout FROM tasks WHERE task_status = '0' AND task_timeout = CURDATE() ORDER BY add_date";

$res = mysqli_query($connect, $sql);

if($res && mysqli_num_rows($res)) {
    $task_list = mysqli_fetch_all($res, MYSQLI_ASSOC);

    $res = mysqli_query($connect, "SELECT email, user_name FROM users");

    if($res && mysqli_num_rows($res)) {
        $users = mysqli_fetch_all($res, MYSQLI_ASSOC);
        $recipients = [];

        foreach ($users as $key => $user) {
            $recipients[$user["email"]] = $user["user_name"];
        }


        $message = new Swift_Message();
        $message->setSubject("Уведомление от сервиса «Дела в порядке»");
        $message->setFrom(["keks@phpdemo.ru"=> "Дела в порядке"]);
        $message->setBcc($recipients);

        $msg_content = "Уважаемый пользователь! У вас запланирована задача на сегодня";
        $message->setBody($msg_content, "text/plain");

        /* Отправка сообщения */
        $result = $mailer->send($message);


        if($result) {
            print("Рассылка успешно отправлена!");
        }
        else {
            print("Не удалось отправить рассылку.");
        }

    }
}

?>