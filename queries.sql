/* Выбирает БД */
USE doingsdone;

/* Заполняет таблицу users */
INSERT INTO users
SET reg_date = NOW(), 
    email = "consta@user.ru", 
    user_name = "Константин",
    user_password = "one_love";

INSERT INTO users
SET reg_date = NOW(), 
    email = "keks@doings.mur", 
    user_name = "Keks",
    user_password = "miaauu";

/* Заполняет таблицу projects */
INSERT INTO projects (project_name, user_id)
VALUES ("Входящие", "1"), ("Учеба", "1"), ("Работа", "1"), ("Домашние дела", "1"), ("Авто", "1"), ("Академия", "2"), ("Спорт", "2");

/* Заполняет таблицу tasks */
INSERT INTO tasks
SET task_name = "Собеседование в IT компании",
    task_timeout = "01.12.2019",
    user_id = "1",
    project_id = "3",
    task_status = "0",
    add_date = NOW();

INSERT INTO tasks
SET task_name = "Выполнить тестовое задание",
    task_timeout = "25.12.2018",
    user_id = "1",
    project_id = "3",
    task_status = "0",
    add_date = NOW();

INSERT INTO tasks
SET task_name = "Сделать задание первого раздела",
    task_timeout = "21.12.2018",
    user_id = "1",
    project_id = "2",
    task_status = "1",
    add_date = NOW();

INSERT INTO tasks
SET task_name = "Встреча с другом",
    task_timeout = "22.12.2018",
    user_id = "1",
    project_id = "1",
    task_status = "0",
    add_date = NOW();

INSERT INTO tasks
SET task_name = "Купить корм для кота",
    task_timeout = NULL,
    user_id = "1",
    project_id = "4",
    task_status = "0",
    add_date = NOW();

INSERT INTO tasks
SET task_name = "Заказать пиццу",
    task_timeout = NULL,
    user_id = "1",
    project_id = "4",
    task_status = "0",
    add_date = NOW();

INSERT INTO tasks
SET task_name = "Собеседовать енота",
    task_timeout = "01.12.2019",
    user_id = "2",
    project_id = "6",
    task_status = "0",
    add_date = NOW();

INSERT INTO tasks
SET task_name = "Сеанс шторолазания",
    task_timeout = NULL,
    user_id = "2",
    project_id = "7",
    task_status = "0",
    add_date = NOW();

/* Получить список из всех проектов для одного пользователя */
SELECT user_name, p.project_name, p.project_id FROM projects p
JOIN users u
ON u.user_id = p.user_id
WHERE u.user_id = 1;

/* Посчитать количество задач в каждом проекте  */
SELECT t.task_id, t.task_name, p.project_name FROM tasks t
JOIN projects p
ON t.project_id = p.project_id;

/* Получить список из всех задач для одного проекта */
SELECT p.project_name, t.task_name FROM tasks t
JOIN projects p
ON t.project_id = p.project_id
WHERE p.project_id = 3;

/* пометить задачу как выполненную */
UPDATE tasks SET task_status = "1"
WHERE task_name = "Заказать пиццу";

/* обновить название задачи по её идентификатору */
-- UPDATE tasks SET task_name = "Заказать такси"
-- WHERE task_id = "6";