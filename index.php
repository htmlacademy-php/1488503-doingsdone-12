<?php
include 'helpers.php';
include 'conndb.php';

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
$categories = [];
$tasks = [];


//Подключение базы данных
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


//Кодировка utf-8
$conn->set_charset("utf8");

//Взял из базы данных название проекты
$sqlProject = 'SELECT * FROM projects ';

$result = mysqli_query($conn, $sqlProject);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

function countTasksForCategory($conn, $categoryId)
{
    $sql = 'SELECT count(*) as count FROM tasks WHERE project_id =' . $categoryId;
    $result = mysqli_query($conn, $sql)->fetch_assoc();
    return $result['count'];

}

//показывает каждый row
//name == это название проект
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
$resultSQL = "SELECT * FROM tasks";

// Если параметр присутствует, то показывать только те задачи, что относятся к этому проекту.
if (!empty($_GET['project_id'])) {
    $projectId = intval($_GET['project_id']);
    $resultSQL = $resultSQL . ' WHERE project_id = ' . $projectId;

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
        $tasks[] = [
            'task' => $row['name'],
            'date_of_completion' => $row['date_term'],
            'category' => $row['project_id'],
            'completed' => $row['status'] == true,
        ];
    }
}


$mainContent = include_template('main.php', [
    'categories' => $categories,
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks,
    'projectId' => $projectId
]);
echo include_template('layout.php', ['title' => 'Дела в порядке', 'content' => $mainContent]);
//HTML-код главной страницы


