<?php
session_start();
include 'helpers.php';
include 'conndb.php';
$conn = mysqli_connect($hostname, $username, $password, $dbname);
mysqli_set_charset($conn, 'utf8');
$errors = [];
$categories = [];
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
$query  = "SELECT * FROM `projects` where user_id = ? ";
$data = [$user_id];

$stmt = db_get_prepare_stmt($conn,$query,$data);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

foreach ($rows  as $row) {
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
    $data = [$user_id,$name];
    $addProject = 'INSERT INTO `projects`(`user_id`,`name`) VALUES (?,?)';
    $stmt = db_get_prepare_stmt($conn,$addProject,$data);
    $result = mysqli_stmt_get_result($stmt);
    if ($result) {
        header('Location:index.php');
    }
}
$mainContent = include_template('form-project.php', [
    'errors' => $errors,
    'categories' => $categories,
    'projectId' => null,
]);
echo include_template('layout.php', ['title' => 'Дела в порядке', 'content' => $mainContent]);
