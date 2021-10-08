<?php
require('vendor/autoload.php');
include 'conndb.php';
require_once 'connSwiftMailer.php';
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

    $message = (new Swift_Message('Уведомление от сервиса «Дела в порядке»'))

        ->setFrom(['vratar89@bk.ru' => 'Viktor'])

        ->setTo([$item['email'] => $item['name']])

        ->setBody('Уважаемый, ' . $item['name']. '. У вас запланирована задача '.$item['date_term'].' на %время задачи%')
    ;

    $result = $mailer->send($message);
}
// здесь указываем адрес администратора, который получит заявку с сайта
// если получателей несколько, указываем в формате: ['receiver@domain.org', 'other@domain.org' => 'A NAME']
// (там где 'A NAME' пишем любое имя, это ни на что не влияет)
//$to = ['email_адрес_администратора' => 'ADMIN'];
//$from = 'ваш_логин@домен_почтового_сервера_например_yandex.ru';
//Отправитель from
//$from = '';
//Получатель to
//$to = '';
//if (!extension_loaded('openssl')) {
//    echo "no openssl extension loaded.";
//}
//$emailLogin = "vratar1001@gmail.com";
//$emailPassword = "Smile4Me1)))";
//
//$transport = (new Swift_SmtpTransport('smtp.gmail.com', 465))
//    ->setUsername($emailLogin)
//    ->setPassword($emailPassword);
////var_dump($transport);
//$mailer = new Swift_Mailer($transport);
