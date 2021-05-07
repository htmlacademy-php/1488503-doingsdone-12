<?php
include('helpers.php');
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
$categories = ["Входящие", "Учеба", "Работа", "Домашние дела", "Авто",];
$tasks = [
    [
        'task' => 'Собеседование в IT компании',
        'date_of_completion' => '10.05.2021',
        'category' => 'Работа',
        'completed' => false,
    ],
    [
        'task' => 'Выполнить тестовое задание',
        'date_of_completion' => '09.05.2021',
        'category' => 'Работа',
        'completed' => false,
    ],
    [
        'task' => 'Сделать задание первого раздела',
        'date_of_completion' => '07.05.2021',
        'category' => 'Учеба',
        'completed' => false,
    ],
    [
        'task' => 'Встреча с другом',
        'date_of_completion' => '06.05.2021',
        'category' => 'Входящие',
        'completed' => false,
    ],
    [
        'task' => 'Купить корм для кота',
        'date_of_completion' => '05.05.2021',
        'category' => 'Домашние дела',
        'completed' => false,
    ],
    [
        'task' => 'Заказать пиццу',
        'date_of_completion' => '08.05.2021',
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


