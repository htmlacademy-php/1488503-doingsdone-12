<?php

session_start();
//  Подключение файл  //
require_once 'helpers.php';
require_once 'conndb.php';
require_once 'validations.php';

if (!isset($_SESSION['user'])) {
    $mainContent = include_template('guest.php', []);
    echo include_template('layout.php', ['title' => 'Дела в порядке', 'content' => $mainContent]);
    return;
}

$show_complete_tasks = rand(0, 1);
$categories = [];
$tasks = [];
$projectIds = [];
$user_id = $_SESSION['user']['id'];
$sqlProject = "SELECT * FROM projects WHERE user_id = ? ";
$rows = getSQL($conn, $sqlProject, $user_id);
foreach ($rows as $row) {
    $count = countTasksForCategory($conn, $row['id']);
    $categories[] = [
        'name' => $row['name'],
        'project_id' => $row['id'],
        'count' => $count,
    ];
    $projectIds[] = $row['id'];
}

$projectId = null;
$foundMatches = false;
$errors = [];

if (!empty($_POST)) {
    $validationRules = [
        'name' => ['required', 'string', 'min:1'],
        'date' => ['required', 'date', 'after:' . date('d.m.Y')],
        'project' => ['required', 'exists:projects,id'],
    ];

    $errors = validate($_POST ?? [], $validationRules, $conn);

    if (!empty($_FILES['file']['name'])) {
        $file_name = $_FILES['file']['name'];
        $file_path = __DIR__ . '/uploads/';
        $file_url = $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $file_name;
        move_uploaded_file($_FILES['file']['tmp_name'], $file_path . $file_name);
    }

    if (empty($errors)) {
        $user = $_SESSION['user']['id'];
        $name = $_POST['name'];
        $project = $_POST['project'];
        $date = $_POST['date'] . ' 00:00:00';
        $current_date = date("Y.m.d H:i:s");
        $file = $file_url ?? null;
        $data = [$user, $project, $name, $file, $current_date, $date];
        $addTasks = " INSERT INTO `tasks` (`user_id`,`project_id`, `name`, `file`, `date_add`,`date_term`)
            VALUES (?,?,?,?,?,?)";
        $stmt = db_get_prepare_stmt($conn, $addTasks, $data);
        $result = mysqli_stmt_execute($stmt);
        if ($result) {
            header('Location:index.php');
        }
    }
}
$mainContent = include_template('add-form-task.php', [
    'categories' => $categories,
    'projectId' => $projectId,
    'errors' => $errors,
    'old' => $_POST,
]);

echo include_template('layout.php', ['title' => 'Дела в порядке', 'content' => $mainContent]);

