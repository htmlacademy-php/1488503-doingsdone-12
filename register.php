<?php

require_once 'conndb.php';
require_once 'helpers.php';
require_once 'validations.php';
$errors = [];

if (empty ($_POST)) {
    $mainContent = include_template('register.php', [
        'errors' => $errors,
    ]);
    echo include_template('layout.php', ['title' => 'Дела в порядке', 'content' => $mainContent]);
    return;
}

$rules = [
    'email' => ['required', 'email', 'exists:users,email'],
    'password' => ['required', 'min:6'],
    'name' => ['required'],
];

$errors = validate($_POST, $rules, $conn);

if (count($errors) === 0) {
    $mainContent = include_template('register.php', [
        'errors' => $errors,
    ]);
    echo include_template('layout.php', ['title' => 'Дела в порядке', 'content' => $mainContent]);
    return;
}
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
if ($result) {
    header('Location:index.php');

}

$mainContent = include_template('register.php', [
    'errors' => $errors,
]);
echo include_template('layout.php', ['title' => 'Дела в порядке', 'content' => $mainContent]);
