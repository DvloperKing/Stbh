create database stbh;
use stbh;

-- tabla perfiles
create table perfil (
	id int auto_increment primary key,
	name_perfil varchar(20)
);

-- tabla usuarios
create table users (
	id int auto_increment primary key,
	email varchar(45),
	pass varchar(45),
	id_perfil int,
	foreign key (id_perfil) references perfil(id)
);

-- tabla permisos
create table permissions (
	id decimal(3,1) primary key,
	name_permissions varchar(30)
);

-- relación de permisos por perfil
create table permissionsxprofile (
	id_perfil int,
	id_permissions decimal(3,1),
	foreign key (id_perfil) references perfil(id),
	foreign key (id_permissions) references permissions(id)
);

-- tabla niveles de educación
create table education_levels (
	id int auto_increment primary key,
	name_level varchar(30) unique
);

-- tabla modalidades
create table modalities (
	id int auto_increment primary key,
	name_modality varchar(20) unique,
	id_level int,
	foreign key (id_level) references education_levels(id)
);

-- tabla materias
create table subjects (
	id int auto_increment primary key,
	name_subject varchar(50),
	code varchar(20) unique,
	semester int not null
);

-- tabla docentes
create table teaching (
	id int auto_increment primary key,
	id_user int,
	first_name varchar(35),
	last_name varchar(60),
	foreign key (id_user) references users(id)
);

-- tabla alumnos
create table students (
	id int auto_increment primary key,
	control_number int unique,
	id_user int,
	id_modality int,
	first_name varchar(35),
	last_name varchar(60),
	semester int,
	foreign key (id_user) references users(id),
	foreign key (id_modality) references modalities(id)
);

-- materias asignadas a alumnos
create table student_subjects (
	id int auto_increment primary key,
	id_user int,
	id_subject int,
	foreign key (id_user) references users(id),
	foreign key (id_subject) references subjects(id)
);

-- materias asignadas a docentes
create table teacher_subjects (
	id int auto_increment primary key,
	id_user int,
	id_subject int,
	foreign key (id_user) references users(id),
	foreign key (id_subject) references subjects(id)
);
