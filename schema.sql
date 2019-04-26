CREATE DATABASE doingsdone
    default CHARACTER SET utf8
    default COLLATE utf8_general_ci;

USE doingsdone;

CREATE TABLE users (
	user_id INT auto_increment PRIMARY KEY,
	reg_date TIMESTAMP default current_timestamp,
	email CHAR(128) NOT NULL unique,
	user_name CHAR(128),
	user_password CHAR(64)
);

CREATE TABLE tasks (
	task_id INT auto_increment PRIMARY KEY,
	add_date TIMESTAMP default current_timestamp,
	task_status TINYINT(1),
	task_name CHAR(128),
	task_file CHAR(128),
	task_timeout TIMESTAMP,
    user_id INT,
    project_id INT
);

CREATE TABLE projects (
	project_id INT auto_increment PRIMARY KEY,
	project_name CHAR(128),
    user_id INT
);