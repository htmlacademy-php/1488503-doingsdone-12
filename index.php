<?php
include('helpers.php');
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
$categories = ["Входящие", "Учеба", "Работа", "Домашние дела", "Авто",];
$tasks = [
    [
        'task' => 'Собеседование в IT компании',
        'date_of_completion' => '04.03.2021',
        'category' => 'Работа',
        'completed' => false,
    ],
    [
        'task' => 'Выполнить тестовое задание',
        'date_of_completion' => '25.12.2019',
        'category' => 'Работа',
        'completed' => false,
    ],
    [
        'task' => 'Сделать задание первого раздела',
        'date_of_completion' => '21.12.2019',
        'category' => 'Учеба',
        'completed' => false,
    ],
    [
        'task' => 'Встреча с другом',
        'date_of_completion' => '22.12.2019',
        'category' => 'Входящие',
        'completed' => false,
    ],
    [
        'task' => 'Купить корм для кота',
        'date_of_completion' => 'null',
        'category' => 'Домашние дела',
        'completed' => false,
    ],
    [
        'task' => 'Заказать пиццу',
        'date_of_completion' => 'null',
        'category' => 'Домашние дела',
        'completed' => false,
    ]
];
function countTasksForCategory($tasks, $category)
{
    $count = 0;
    foreach ($tasks as $task) {
        if ($task['category'] === $category) {
            $count++;
        }
    }
    return $count;
}
$mainContent = include_template('main.php',['categories' => $categories, 'tasks'=> $tasks, 'show_complete_tasks' => $show_complete_tasks]);
echo include_template('layout.php', ['title' => 'Дела в порядке', 'content' => $mainContent]);
//HTML-код главной страницы
