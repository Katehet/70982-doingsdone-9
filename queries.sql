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
VALUES ("Входящие", "1"), ("Учеба", "1"), ("Работа", "1"), ("Домашние дела", "1"), ("Авто", "1");

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
    task_status = "0",
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
    task_timeout = "Нет",
    user_id = "1",
    project_id = "4",
    task_status = "0",
    add_date = NOW();

INSERT INTO tasks
SET task_name = "Заказать пиццу",
    task_timeout = "Нет",
    user_id = "1",
    project_id = "4",
    task_status = "0",
    add_date = NOW();
