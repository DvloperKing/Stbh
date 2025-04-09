drop database if exists stbh;
create database stbh;
use stbh;

create table perfil (
	id int auto_increment primary key,
	name_perfil varchar(20)
);

create table users (
	id int auto_increment primary key,
	email varchar(45),
	pass varchar(45),
	first_name varchar(35),
	last_name varchar(60),
	id_perfil int,
	foreign key (id_perfil) references perfil(id)
);

create table permissions (
	id decimal(3,1) primary key,
	name_permissions varchar(50)
);

create table permissionsxprofile (
	id_perfil int,
	id_permissions decimal(3,1),
	foreign key (id_perfil) references perfil(id),
	foreign key (id_permissions) references permissions(id)
);

create table education_levels (
	id int auto_increment primary key,
	name_level varchar(30) unique
);

create table modalities (
	id int auto_increment primary key,
	name_modality varchar(20) unique,
	id_level int,
	foreign key (id_level) references education_levels(id)
);

create table subjects (
	id int auto_increment primary key,
	name_subject varchar(20),
	code varchar(20) unique,
	semester int not null
);

create table teaching (
	id int auto_increment primary key,
	id_user int,
	highest_degree varchar(50),
	phone_number varchar(20),
	foreign key (id_user) references users(id)
);

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

create table student_subjects (
	id int auto_increment primary key,
	id_user int,
	id_subject int,
	foreign key (id_user) references users(id),
	foreign key (id_subject) references subjects(id)
);

create table teacher_subjects (
	id int auto_increment primary key,
	id_user int,
	id_subject int,
	foreign key (id_user) references users(id),
	foreign key (id_subject) references subjects(id)
);

insert into perfil (name_perfil) values ('administrador');
insert into perfil (name_perfil) values ('docente');
insert into perfil (name_perfil) values ('alumno');

insert into permissions (id, name_permissions) values (1.0, 'ver usuarios');
insert into permissions (id, name_permissions) values (1.1, 'añadir usuario');
insert into permissions (id, name_permissions) values (1.2, 'modificar usuario');
insert into permissions (id, name_permissions) values (1.3, 'eliminar usuario');

insert into permissions (id, name_permissions) values (2.0, 'ver perfiles');
insert into permissions (id, name_permissions) values (2.1, 'asignar permisos a perfiles');

insert into permissions (id, name_permissions) values (3.0, 'ver docentes');
insert into permissions (id, name_permissions) values (3.1, 'añadir docente');
insert into permissions (id, name_permissions) values (3.2, 'modificar docente');
insert into permissions (id, name_permissions) values (3.3, 'eliminar docente');

insert into permissions (id, name_permissions) values (4.0, 'ver alumnos');
insert into permissions (id, name_permissions) values (4.1, 'añadir alumno');
insert into permissions (id, name_permissions) values (4.2, 'modificar alumno');
insert into permissions (id, name_permissions) values (4.3, 'eliminar alumno');

insert into permissions (id, name_permissions) values (5.0, 'ver materias');
insert into permissions (id, name_permissions) values (5.1, 'añadir materia');
insert into permissions (id, name_permissions) values (5.2, 'modificar materia');
insert into permissions (id, name_permissions) values (5.3, 'eliminar materia');

insert into permissions (id, name_permissions) values (6.0, 'ver horarios');
insert into permissions (id, name_permissions) values (6.1, 'añadir horario');
insert into permissions (id, name_permissions) values (6.2, 'modificar horario');
insert into permissions (id, name_permissions) values (6.3, 'eliminar horario');

insert into permissions (id, name_permissions) values (7.0, 'ver calificaciones');
insert into permissions (id, name_permissions) values (7.1, 'registrar calificación');
insert into permissions (id, name_permissions) values (7.2, 'modificar calificación');
insert into permissions (id, name_permissions) values (7.3, 'eliminar calificación');

insert into permissions (id, name_permissions) values (8.0, 'ver modalidades y niveles');
insert into permissions (id, name_permissions) values (8.1, 'añadir modalidad/nivel');
insert into permissions (id, name_permissions) values (8.2, 'modificar modalidad/nivel');
insert into permissions (id, name_permissions) values (8.3, 'eliminar modalidad/nivel');

insert into permissions (id, name_permissions) values (9.0, 'ver materias asignadas a alumnos');
insert into permissions (id, name_permissions) values (9.1, 'ver materias asignadas a docentes');
insert into permissions (id, name_permissions) values (9.2, 'asignar materias a alumnos');
insert into permissions (id, name_permissions) values (9.3, 'asignar materias a docentes');

-- asignar todos los permisos al perfil administrador (id = 1)
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 1.0);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 1.1);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 1.2);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 1.3);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 2.0);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 2.1);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 3.0);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 3.1);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 3.2);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 3.3);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 4.0);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 4.1);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 4.2);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 4.3);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 5.0);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 5.1);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 5.2);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 5.3);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 6.0);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 6.1);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 6.2);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 6.3);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 7.0);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 7.1);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 7.2);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 7.3);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 8.0);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 8.1);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 8.2);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 8.3);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 9.0);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 9.1);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 9.2);
insert into permissionsxprofile (id_perfil, id_permissions) values (1, 9.3);

insert into users (email, pass, first_name, last_name, id_perfil) values ('admin@stbh.com', 'admin123', 'admin', 'sistema', 1);
insert into users (email, pass, first_name, last_name, id_perfil) values ('alumno@stbh.com', '12345', 'juan', 'pérez garcía', 3);
insert into users (email, pass, first_name, last_name, id_perfil) values ('docente@stbh.com', 'clave', 'maría', 'lópez', 2);

insert into education_levels (name_level) values ('licenciatura');

insert into modalities (name_modality, id_level) values ('escolarizado', 1);

insert into students (control_number, id_user, id_modality, first_name, last_name, semester) values (20250001, 2, 1, 'juan', 'pérez garcía', 2);

insert into teaching (id_user, highest_degree, phone_number) values (3, 'maestría en educación', '8341234567');

select * from perfil;
select * from permissions;
select * from permissionsxprofile;
select * from users;
select * from students;
select * from teaching;

select u.*, s.* from users u inner join students s on u.id = s.id_user;
select u.*, p.name_perfil as perfil from users u inner join perfil p on u.id_perfil = p.id;
select id_permissions from permissionsxprofile where id_perfil = 1;
