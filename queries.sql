USE
things_are_in_order;

INSERT INTO `users` (`email`, `password`, `name`, `date_create`)
VALUES ('html@gmail.com', '1234', 'Виктор', '2021-05-31 12:44:12'),
       ('Ivanov@gmail.com', '123', 'Иван', '2021-06-08 12:20:12');

INSERT INTO `projects` (`user_id`, `name`)
VALUES (1, 'Входящие'),
       (1, 'Учеба'),
       (1, 'Работа'),
       (1, 'Домашние дела'),
       (1, 'Авто');

INSERT INTO `tasks` (`user_id`, `project_id`, `name`, `date_add`, `date_term`, `status`)
VALUES (1, 3, 'Собеседование в IT компании', '2021-05-10 00:00:00', '2021-06-04 12:52:21', 0),
       (1, 3, 'Выполнить тестовое задание', '2021-05-09 00:00:00', '2021-06-04 15:03:21', 0),
       (1, 2, 'Сделать задание первого раздела', '2021-05-07 00:00:00', '2021-06-04 15:05:21', 0),
       (1, 1, 'Встреча с другом', '2021-05-06 00:00:00', '2021-06-04 15:06:21', 0),
       (1, 4, 'Купить корм для кота', '2021-05-05 00:00:00', '2021-06-04 15:07:21', 0),
       (1, 4, 'Заказать пиццу', '2021-05-08 00:00:00', '2021-06-04 15:08:21', 0);

SELECT *
FROM projects
WHERE user_id = 1;

SELECT *
FROM tasks
WHERE project_id = 3;

UPDATE tasks
SET status='1'
WHERE id = 1;

UPDATE tasks
SET name = 'Сделать задачу'
WHERE id = 1;
