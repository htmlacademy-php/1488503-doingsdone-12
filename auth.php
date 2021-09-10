<?php
session_start();
if (isset($_SESSION['user'])) {

}
include 'helpers.php';
include 'conndb.php';
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8");
$required_fields = ['email', 'password'];
$errors = [];

if ($_POST) {
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Поле не заполнено';
        }
    }

    if (!empty($_REQUEST['email'] and !empty($_REQUEST['password']))) {
        $email = $_REQUEST['email'];
        $password = $_REQUEST['password'];
//        $password = password_hash($_REQUEST['password'], PASSWORD_DEFAULT);
        $checkUser = mysqli_query($conn, "SELECT * FROM `users` WHERE email= '$email'"); //*AND password='$password'");
        if (mysqli_num_rows($checkUser) > 0) {
            $user = mysqli_fetch_assoc($checkUser);
            if (password_verify($password, $user['password'])) {
                $_SESSION['email'] = $user;
                header("Location:/index.php");
            } else {
                $errors['password'] = "Неправильный пароль";
            }
        } else {
            $errors['email'] = "Не правильный email";
        }
    }
}

$mainContent = include_template('auth.php', [
    'errors' => $errors,
]);


echo include_template('layout.php', ['title' => 'Дела в порядке', 'content' => $mainContent]);