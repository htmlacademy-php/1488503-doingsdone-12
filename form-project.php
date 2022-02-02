<?php
session_start();
include 'helpers.php';
include 'conndb.php';
$errors = [];
$categories = [];
$conn = new mysqli($hostname, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8");
function countTasksForCategory($conn, $categoryId)
{
    $sql = 'SELECT count(*) as count FROM tasks WHERE project_id =' . $categoryId;
    $result = mysqli_query($conn, $sql)->fetch_assoc();
    return $result['count'];

}

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    $mainContent = include_template('guest.php', []);
    echo include_template('layout.php', ['title' => ' Дела в порядке', 'content' => $mainContent]);
    die();
}
$user_id = $_SESSION['user']['id'];


$sqlProject = "SELECT * FROM `projects` where user_id = '$user_id'";
$result = mysqli_query($conn, $sqlProject);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

db_get_prepare_stmt($conn,$sqlProject,'');


foreach ($rows as $row) {
    $count = countTasksForCategory($conn, $row['id']);
    $categories[] = [
        'name' => $row['name'],
        'project_id' => $row['id'],
        'count' => $count,
    ];
}
$errors['name'] = "поле пустое";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['name'])) {
    $name = $_POST['name'];
    $addProject = "INSERT INTO `projects`(`user_id`,`name`) VALUES ('$user_id','$name')";
    if (mysqli_query($conn, $addProject)) {
        header('Location:index.php');
    }
}
$mainContent = include_template('form-project.php', [
    'errors' => $errors,
    'categories' => $categories,
]);
echo include_template('layout.php', ['title' => 'Дела в порядке', 'content' => $mainContent]);
