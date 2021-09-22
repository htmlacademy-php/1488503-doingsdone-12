<?php
session_start();
include 'helpers.php';
include 'conndb.php';
$errors = [];
$categories = [];
$conn = new mysqli($servername, $username, $password, $database);
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

if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    $user_id = $_SESSION['user']['id'];
    $sqlProject = "SELECT * FROM `projects` where user_id = '$user_id'";
    $result = mysqli_query($conn, $sqlProject);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    foreach ($rows as $row) {
        $count = countTasksForCategory($conn, $row['id']);
        $categories[] = [
            'name' => $row['name'],
            'project_id' => $row['id'],
            'count' => $count,
        ];
    }
    if ($_POST) {

        if (empty($_POST['name']))
        $errors['name'] = "поле пустое";

        if (!empty($_POST['name'])){
            $name = $_POST['name'];
            $addProject = "INSERT INTO `projects`(`user_id`,`name`) VALUES ('$user_id','$name')";
            if(mysqli_query($conn, $addProject)){
                header('Location:form-project.php');
            }

        }
    }
    $mainContent = include_template('form-project.php', [
        'errors' => $errors,
        'categories' => $categories,
    ]);

} else {
    $mainContent = include_template('guest.php', []);
}
echo include_template('layout.php', ['title' => 'Дела в порядке', 'content' => $mainContent]);