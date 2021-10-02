<?php
require('vendor/autoload.php');
include 'conndb.php';
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8");
//1. Выполнить SQL запрос на получение всех невыполненных задач (статус равен нулю), у которых срок равен текущему дню.
$id = [];
$date = new DateTime();
$dateToday = $date->format('Y-m-d');
$date_start = $dateToday . ' 00:00:00';
$date_end = $dateToday . ' 23:59:00';
$where = "status is null or status !=1 AND date_term >= '$date_start' AND date_term <= '$date_end' ";
$noTasks = mysqli_query($conn,
    "SELECT tasks.user_id, tasks.name, tasks.date_term, users.name, users.email
               FROM tasks INNER JOIN users ON tasks.user_id = users.id WHERE $where ");
$resSql = mysqli_fetch_all($noTasks, MYSQLI_ASSOC);

foreach ($resSql as $item) {
    if (!empty($item['user_id'])) {
        $array[$item['user_id']][] = [
            'name' => $item['name'],
            'date_term' => $item['date_term'],
            'email' => $item['email'],
        ];
    }
}

$transport = new Swift_SmtpTransport('smtp.example.org', 25);

$message = new Swift_Message("Доброе утро!");
$message->setTo([$item['email'] => ""]);
$message->setBody("Вашу гифку «Кот и пылесос» посмотрело больше 1 млн!");
$message->setFrom("mail@giftube.academy", "Tasks");

// Отправка сообщения
$mailer = new Swift_Mailer($transport);
$mailer->send($message);
