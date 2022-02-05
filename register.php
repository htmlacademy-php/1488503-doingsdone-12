<?php

include 'conndb.php';
include 'helpers.php';

$errors = [];

if (empty ($_POST)) {
    $mainContent = include_template('register.php', [
        'errors' => $errors,
    ]);
    echo include_template('layout.php', ['title' => 'Дела в порядке', 'content' => $mainContent]);
    return;
}

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
    $data = [$email, $passwordHash, $name, $createDate];
    $addRegister = "INSERT INTO `users` ( `email`, `password`, `name`,`date_create`)
                        VALUES (?,?,?,?)";
    $stmt = db_get_prepare_stmt($conn, $addRegister, $data);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    var_dump($result);
    if ($result) {
        header('Location:index.php');
    }
}

$mainContent = include_template('register.php', [
    'errors' => $errors,
]);
echo include_template('layout.php', ['title' => 'Дела в порядке', 'content' => $mainContent]);
