<?php
session_start();
require_once 'helpers.php';
require_once 'conndb.php';
require_once 'validations.php';
$errors = [];

if (empty($_POST)) {
    $mainContent = include_template('auth.php', [
        'errors' => $errors,
    ]);


    echo include_template('layout.php', ['title' => 'Дела в порядке', 'content' => $mainContent]);
    die();
}

$rules = [
    'email' => ['required', 'email', 'exists:users,email'],
    'password' => ['required'],
];

$errors = validate($_POST, $rules, $conn);

if (count($errors) === 0) {
    $email = $_POST['email'];
    $userQuery = 'select * from users where email = ?';
    $stmt = db_get_prepare_stmt($conn, $userQuery, [$email]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

    if (password_verify($_REQUEST['password'], $user['password'])) {
        $_SESSION['user'] = $user;
        header('Location: /index.php');
        exit();
    }
    $errors['password'] = 'Пароль не верен';
}

$mainContent = include_template('auth.php', [
    'errors' => $errors,
]);


echo include_template('layout.php', ['title' => 'Дела в порядке', 'content' => $mainContent]);
