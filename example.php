<?php
require 'conndb.php';
require 'helpers.php';
// Я эту штуку убрал в conndb.php, но это на твое усмотрение
$conn = mysqli_connect($hostname, $username, $password, $database);
mysqli_set_charset($conn, 'utf8');

$query = 'select * from projects where user_id = ?';
$data = [1];

$stmt = db_get_prepare_stmt($conn, $query, $data);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

var_dump($rows);
