<?php
include 'conndb.php';
include 'helpers.php';

$conn = new mysqli($hostname, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8");

$errors = [];

if (!empty($_POST)) {
    $formArrays = [
        'email',
        'password',
        'name',
    ];

    foreach ($formArrays as $formArray) {
        if ($formArray === 'email') {
            if (filter_var($_POST[$formArray], FILTER_VALIDATE_EMAIL) === false) {
                $errors[$formArray] = 'Вы не ввели Email';
            }
            $what = mysqli_real_escape_string($conn, $_POST[$formArray]);
            $duplicateEmail = "SELECT COUNT(*) FROM `users` WHERE email = '$what'";

            $RES = mysqli_query($conn, $duplicateEmail);

            if (!$RES) {
                $error = mysqli_error($conn);
                print("Ошибка MySQL: " . $error);
            } else {
                if ($row = mysqli_fetch_row($RES)) {
                    if ($row[0] > 0) {
                        $errors[$formArray] = 'Вы уже зарегистрировали!';
                    }
                }
            }

        }
        if (empty($_POST[$formArray])) {
            $errors[$formArray] = 'Поле не заполнено';
        }
    }

    if (empty($errors)) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $date = new DateTime();
        $createDate = date_format($date, 'Y-m-d H:i:s');
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $addRegister = "INSERT INTO `users` ( `email`, `password`, `name`,`date_create`)
            VALUES ('$email', '$passwordHash','$name', '$createDate')";
        if (mysqli_query($conn, $addRegister)) {
            header('Location:index.php');
        }
    }

}

$mainContent = include_template('register.php', [
    'errors' => $errors,
]);
echo include_template('layout.php', ['title' => 'Дела в порядке', 'content' => $mainContent]);
