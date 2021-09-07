<?php
include 'helpers.php';
include 'conndb.php';
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8");
$errors = [];
if (!empty($_REQUEST['email'] and !empty($_REQUEST['password']))){
    $email = $_REQUEST['email'];
    $password = password_hash($_REQUEST['password'], PASSWORD_DEFAULT);
    $query = 'SELECT * FROM users WHERE email= "'.$email.'" AND password="'.$password.'"';
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    if (!empty($user)) {
       echo "Пользователь прошел авторизацию, выполним какой-то код.";
    } else {
        echo "Пользователь неверно ввел логин или пароль, выполним какой-то код.";
    }
}
$mainContent = include_template('auth.php', [
    'error' => $errors,
]);
include_template('layout.php', ['title' => 'Дела в порядке' , 'content' => $mainContent]);