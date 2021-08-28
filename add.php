<?php
include 'helpers.php';
include 'conndb.php';


// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
$categories = [];
$tasks = [];
$projectIds = [];


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

        if ($formArray === 'date') {
            $dateTodayFormatted = time();
            $postDate = strtotime($_POST[$formArray]);
            if ($postDate <= $dateTodayFormatted) {
            $errors[$formArray] = 'Дата должна быть больше или равна текущей.';
            }
        }

        if ($formArray === 'project') {

            if (!in_array($_POST[$formArray], $projectIds)) {
                $errors[$formArray] = 'Такого проекта нет';
            }

        }

        if (empty($_POST[$formArray])) {
            $errors[$formArray] = 'Поле не заполнено';
        }

    }

    if (!empty($_FILES['file']['name'])) {
        //$_FILES['file']['name']
        // [file] = название name = 'file' из форма add-form-task.php
        // name Оригинальное имя файла на компьютере клиента;
        $file_name = $_FILES['file']['name'];
        $file_path = __DIR__ . '/uploads/';
        $file_url = 'https://1488503-doingsdone-12/uploads/' . $file_name;
        move_uploaded_file($_FILES['file']['tmp_name'], $file_path . $file_name);
    }

    if (empty($errors)) {
        $name = $_POST['name'];
        $project = $_POST['project'];
        $date = $_POST['date'] . ' 00:00:00';
        $current_date = date("Y.m.d H:i:s");
        $file = $file_url ?? null;
        $addTasks = " INSERT INTO `tasks` (`user_id`,`project_id`, `name`, `file`, `date_add`,`date_term`) 
        VALUES ('4','$project','$name','$file','$current_date','$date')";
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


echo include_template('layout.php', ['title' => 'Дела в порядке', 'content' => $mainContent]);
//HTML-код главной страницы
