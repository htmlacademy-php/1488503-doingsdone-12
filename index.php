<?php
session_start();
include 'helpers.php';
include 'conndb.php';
$errors = [];
$projectId = null;
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
    $categories = [];
    $tasks = [];
    $where = '';
    $show_complete_tasks = 0;
    if (isset($_GET['filter'])) {
        if ($_GET['filter'] == 'tomorrow') {
            $date = new DateTime();
            $date->modify('+1 day');
            $dateTomorrow = $date->format('Y-m-d');
            $date_start = $dateTomorrow . ' 00:00:00';
            $date_end = $dateTomorrow . ' 23:59:00';
            $where = " AND date_term >= '$date_start' AND date_term <= '$date_end'";
        }
        if ($_GET['filter'] == 'today') {
            $date = new DateTime();
            $dateToday = $date->format('Y-m-d');
            $date_start = $dateToday . ' 00:00:00';
            $date_end = $dateToday . ' 23:59:00';
            $where = " AND date_term >= '$date_start' AND date_term <= '$date_end'";
        }
        if ($_GET['filter'] == 'yesterday') {
            $date = new DateTime();
            $date->modify('-1 day');
            $dateYesterday = $date->format('Y-m-d');
            $date_end = $dateYesterday . ' 23:59:00';
            $where = "AND date_term <= '$date_end' ";
        }
    }
    if (isset($_GET['show_completed'])){
        $show_complete_tasks = intval($_GET['show_completed']);
    }
    if ($show_complete_tasks == 0){
        $where .= " AND (status is null or status !=1) ";
    }
    $bodyBackground = true;

    if (isset($_GET['check']) and isset($_GET['task_id'])) {
        $status = intval($_GET['check']);
        $tasks_id = intval($_GET['task_id']);
        mysqli_query($conn, "UPDATE `tasks` SET status = $status  WHERE id = '$tasks_id'");
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
    if (!empty($_GET['search'])) {
        $trim = trim($_GET['search']);
        $searchSql = mysqli_query($conn,
            "SELECT * FROM `tasks` WHERE MATCH(name) AGAINST('$trim') and user_id = '$user_id' '$where' ");
        if (mysqli_num_rows($searchSql) > 0) {
            $arSearchRows = mysqli_fetch_all($searchSql, MYSQLI_ASSOC);
            foreach ($arSearchRows as $row) {
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
        } else {
            $errors['search'] = "Ничего не найдено по вашему запросу";
        }
    } else {
        $foundMatches = false;
        $resultSQL = "SELECT * FROM `tasks` WHERE  user_id = '$user_id' $where ";
        if (!empty($_GET['project_id'])) {
            $projectId = intval($_GET['project_id']);
            $resultSQL = $resultSQL . 'and project_id = ' . $projectId;

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
                    'id' => $row['id'],
                ];
            }
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
