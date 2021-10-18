<?php
require('vendor/autoload.php');
include 'conndb.php';
require_once 'connSwiftMailer.php';
$conn = new mysqli($servername, $username, $password, $database);
$transport = (new Swift_SmtpTransport('smtp.mail.ru', 465, 'ssl'))
    ->setUsername($emailSwiftMailer)
    ->setPassword($passwordSwiftMailer);
$mailer = new Swift_Mailer($transport);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//1. Выполнить SQL запрос на получение всех невыполненных задач (статус равен нулю), у которых срок равен текущему дню.
$id = [];
$array = [];
$date = new DateTime();
$dateToday = $date->format('Y-m-d');
$date_start = $dateToday . ' 00:00:00';
$date_end = $dateToday . ' 23:59:00';
$where = "status is null or status !=1 AND date_term >= '$date_start' AND date_term <= '$date_end' ";
$noTasks = mysqli_query($conn,
    "SELECT tasks.user_id, tasks.name, tasks.date_term, users.name AS user_name, users.email
               FROM tasks INNER JOIN users ON tasks.user_id = users.id WHERE $where ");

$resSql = mysqli_fetch_all($noTasks, MYSQLI_ASSOC);

if (!empty($resSql)) {
    foreach ($resSql as $item) {
        if (!empty($item['user_id'])) {
            $array[$item['user_id']][] = [
                'name' => $item['name'],
                'date_term' => $item['date_term'],
                'email' => $item['email'],
                'user_name' => $item['user_name'],
            ];
        }
    }
    foreach ($array as $userId => $tasks) {
        $emailTo = $tasks[0]["email"];
        $nameTo = $tasks[0]["user_name"];
        $userName = 'Здравствуйте ' . $nameTo . "\n";
        $bodyText = $userName;
        foreach ($tasks as $task) {
            $bodyText .= 'У вас запланирована задача ' . $task['name'] . "\n" . ' на ' . $task['date_term'] . '';
        }
        $message = (new Swift_Message('Уведомление от сервиса «Дела в порядке»'))
            ->setFrom(['vratar89@bk.ru' => 'Viktor'])
            ->setTo([$emailTo => $nameTo])
            ->setBody($bodyText);
        $result = $mailer->send($message);
        if (!extension_loaded('openssl')) {
            echo "No openssl";
        }
    }
}


