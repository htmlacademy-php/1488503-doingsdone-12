<?php
session_start();
//  Подключение файл  //
include 'helpers.php';
include 'conndb.php';
$conn = mysqli_connect($hostname, $username, $password, $dbname);
mysqli_set_charset($conn, 'utf8');

function countTasksForCategory($conn, $categoryId)
{
    $sql = 'SELECT count(*) as count FROM tasks WHERE project_id =' . $categoryId;
    $result = mysqli_query($conn, $sql)->fetch_assoc();
    return $result['count'];
}

if (isset($_SESSION['user'])) {

    $show_complete_tasks = rand(0, 1);
    $categories = [];
    $tasks = [];
    $projectIds = [];
    $user_id = $_SESSION['user']['id'];
    $sqlProject = "SELECT * FROM projects WHERE user_id = '$user_id'";
    $result = mysqli_query($conn, $sqlProject);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);


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
        $formArrays = [
            'name',
            'project',
            'date',
        ];
        foreach ($formArrays as $formArray) {

            if ($formArray === 'date' && isset($postDate)) {
                $errors[$formArray] = "Не существует";
                $dateTodayFormatted = time();
                $postDate = strtotime($_POST[$formArray]);
                if ($postDate <= $dateTodayFormatted) {
                    $errors[$formArray] = 'Дата должна быть больше или равна текущей.';
                }
            }
            if ($formArray === 'date' || !in_array('date', $formArrays)){
                $errors[$formArray] = "Даты не выбрали";
            }
            if ($formArray === 'name' || !in_array('name' , $formArrays)){
                $errors[$formArray] = "Вы не написал название проект";
            }
        }

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
            $addTasks = " INSERT INTO `tasks` (`user_id`,`project_id`, `name`, `file`, `date_add`,`date_term`)
            VALUES ('$user','$project','$name','$file','$current_date','$date')";
            if (mysqli_query($conn, $addTasks)) {
                header('Location:index.php');
            }
        }

    }
    $mainContent = include_template('add-form-task.php', [
        'categories' => $categories,
        'projectId' => $projectId,
        'errors' => $errors,
    ]);
} else {
    $mainContent = include_template('guest.php', []);
}
echo include_template('layout.php', ['title' => 'Дела в порядке', 'content' => $mainContent]);

