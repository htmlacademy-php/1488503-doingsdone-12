<?php
session_start();
include 'helpers.php';
include 'conndb.php';
$errors = [];
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
    $show_complete_tasks = rand(0, 1);
    $categories = [];
    $tasks = [];
    $bodyBackground = true;
    $search = $_GET['search'];

    if (!empty($search)) {
        $trim = trim($_GET['search']);
        $searchSql = mysqli_query($conn, "SELECT * FROM `tasks` WHERE MATCH(name, user_id ) AGAINST('$trim')");
        if (mysqli_num_rows($searchSql) > 0) {
            $searchMessage = mysqli_fetch_assoc($searchSql);
        } else {
            $errors['search'] = "Ничего не найдено по вашему запросу";
        }
    }

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

    $projectId = null;
    $foundMatches = false;
    $resultSQL = "SELECT * FROM `tasks` WHERE  user_id = '$user_id'";
    if (!empty($_GET['project_id'])) {
        $projectId = intval($_GET['project_id']);
        $resultSQL = $resultSQL . ' project_id = ' . $projectId;

        foreach ($categories as $key => $value) {
            if ($projectId === intval($value["project_id"])) {
                $foundMatches = true;
            }
        }
        if (!$foundMatches) {
            header('Location:404.php');
        }
    }
    $result2 = mysqli_query($conn, $resultSQL);
    $rows2 = mysqli_fetch_all($result2, MYSQLI_ASSOC);

    foreach ($rows2 as $row) {
        if (!empty($row['name']) && !empty($row['project_id'])) {

            if (!empty($row['file'])) {
                $arFile = explode('/', $row['file']);
                $fileName = $arFile[count($arFile) - 1];
            }

            $tasks[] = [
                'task' => $row['name'],
                'date_of_completion' => $row['date_term'],
                'category' => $row['project_id'],
                'completed' => $row['status'] == true,
                'file' => $row['file'],
                'fileName' => $fileName ?? '',
                'name' => $row['name'],
            ];
        }
    }
    $mainContent = include_template('main.php', [
        'categories' => $categories,
        'tasks' => $tasks,
        'show_complete_tasks' => $show_complete_tasks,
        'projectId' => $projectId,
        'errors' => $errors,
    ]);
} else {
    $mainContent = include_template('guest.php', []);
}
echo include_template('layout.php', ['title' => 'Дела в порядке', 'content' => $mainContent]);