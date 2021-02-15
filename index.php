<?php
include('templates/layout.php');
include "templates/main.php";
//HTML-код главной страницы
print include_template('layout',"'categories' => $categories, 'tasks' => $tasks,");
