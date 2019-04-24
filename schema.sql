create database doingsdone
    default character set utf8
    default collate utf8_general_ci;

use doingsdone;

create table users (
	user_id int auto_increment primary key,
	reg_date timestamp default current_timestamp,
	email char(128) not null unique,
	user_name char(128),
	user_password char(64)
);

create table tasks (
	task_id int auto_increment primary key,
	add_date timestamp default current_timestamp,
	task_status tinyint(1),
	task_name char(128),
	task_file char(128),
	task_timeout date,
    user_id int,
    project_id int
);

create table projects (
	project_id int auto_increment primary key,
	project_name char(128),
    user_id int
);