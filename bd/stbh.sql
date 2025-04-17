-- eliminar y crear la base de datos

drop database if exists stbh;
create database stbh;
use stbh;

-- tablas principales

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
  name_modality varchar(20) unique
);

create table modality_level (
  id int auto_increment primary key,
  id_modality int,
  id_level int,
  foreign key (id_modality) references modalities(id),
  foreign key (id_level) references education_levels(id)
);

create table subjects (
  id int auto_increment primary key,
  name_subject varchar(20),
  code varchar(20) unique,
  semester int
);

-- nueva tabla corregida para registrar la relación nivel + modalidad + materia
create table subject_modality_level (
  id int auto_increment primary key,
  id_subject int,
  id_modality int,
  id_level int,
  foreign key (id_subject) references subjects(id),
  foreign key (id_modality) references modalities(id),
  foreign key (id_level) references education_levels(id)
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

-- inserts de catálogos

insert into perfil (name_perfil) values ('administrador');
insert into perfil (name_perfil) values ('docente');
insert into perfil (name_perfil) values ('alumno');

insert into permissions (id, name_permissions) values (1.0, 'ver usuarios');
insert into permissions (id, name_permissions) values (5.0, 'ver materias');
insert into permissions (id, name_permissions) values (5.1, 'añadir materia');

insert into permissionsxprofile (id_perfil, id_permissions) select 1, id from permissions;

insert into users (email, pass, first_name, last_name, id_perfil) values
('admin@stbh.com', 'admin123', 'admin', 'sistema', 1),
('alumno@stbh.com', '12345', 'juan', 'pérez garcía', 3),
('docente@stbh.com', 'clave', 'maría', 'lópez', 2);

insert into education_levels (name_level) values ('bachillerato');
insert into education_levels (name_level) values ('básico');

insert into modalities (name_modality) values ('sabatino');
insert into modalities (name_modality) values ('internado');
insert into modalities (name_modality) values ('online');

insert into modality_level (id_modality, id_level) values (1, 2); -- sabatino → básico
insert into modality_level (id_modality, id_level) values (2, 1); -- internado → bachillerato
insert into modality_level (id_modality, id_level) values (2, 2); -- internado → básico
insert into modality_level (id_modality, id_level) values (3, 2); -- online → básico

-- materias
insert into subjects (name_subject, code, semester) values
('matemáticas i', 'mat101', 1),
('programación i', 'pro101', 1),
('química', 'qui101', 1),
('álgebra', 'alg101', 1),
('doctrina cristiana i', 'dc01', 1);

-- relación exacta materia - modalidad - nivel
insert into subject_modality_level (id_subject, id_modality, id_level) values
(1, 1, 2), -- matemáticas i → sabatino, básico
(1, 2, 2), -- matemáticas i → internado, básico
(1, 3, 2), -- matemáticas i → online, básico
(2, 1, 2), -- programación i → sabatino, básico
(2, 2, 2),
(2, 3, 2),
(3, 2, 2), -- química → internado, básico
(4, 2, 1), -- álgebra → internado, bachillerato
(5, 2, 2); -- doctrina cristiana i → internado, básico

insert into teaching (id_user, highest_degree, phone_number)
values (3, 'maestría en educación', '8341234567');

insert into students (control_number, id_user, id_modality, semester)
values (20250001, 2, 1, 2);

select * from students;

-- consulta materias completas
select 
  s.name_subject, 
  s.code, 
  s.semester, 
  m.name_modality, 
  el.name_level
from subject_modality_level sml
join subjects s on s.id = sml.id_subject
join modalities m on m.id = sml.id_modality
join education_levels el on el.id = sml.id_level
order by el.name_level, m.name_modality, s.semester, s.name_subject;

SELECT ts.id, u.first_name, u.last_name, s.name_subject
  FROM teacher_subjects ts
  JOIN users u ON ts.id_user = u.id
  JOIN subjects s ON ts.id_subject = s.id
  ORDER BY u.first_name, s.name_subject;
  
SELECT  m.name_modality,
  sml.id_level,
  sub.semester,
  sub.name_subject,
  sub.code,
  CONCAT(u.first_name, ' ', u.last_name) AS docente
FROM teacher_subjects ts
JOIN users u ON u.id = ts.id_user
JOIN subjects sub ON sub.id = ts.id_subject
JOIN subject_modality_level sml ON sml.id_subject = sub.id
JOIN modalities m ON m.id = sml.id_modality
WHERE u.id_perfil = 2
ORDER BY m.name_modality, sub.semester, sub.name_subject;

-- pruebas para modulo docente
-- Insertar perfiles
INSERT INTO perfil (name_perfil) VALUES ('Docente'), ('Alumno');

-- Insertar usuarios
INSERT INTO users (email, pass, first_name, last_name, id_perfil)
VALUES 
  ('docente1@correo.com', '1234', 'Ana', 'Gómez', 1),
  ('alumno1@correo.com', '1234', 'Luis', 'Pérez', 2),
  ('alumno2@correo.com', '1234', 'Marta', 'López', 2);

-- Insertar niveles educativos
INSERT INTO education_levels (name_level) VALUES ('Secundaria');

-- Insertar modalidades
INSERT INTO modalities (name_modality) VALUES ('Presencial');

-- Relación modalidad-nivel
INSERT INTO modality_level (id_modality, id_level) VALUES (1, 1);

-- Insertar materias
INSERT INTO subjects (name_subject, code, semester)
VALUES 
  ('Matemáticas', 'MAT101', 1),
  ('Ciencias', 'CIE101', 1);

-- Relación materia-modalidad-nivel
INSERT INTO subject_modality_level (id_subject, id_modality, id_level)
VALUES 
  (1, 1, 1),
  (2, 1, 1);

-- Insertar docente
INSERT INTO teaching (id_user, highest_degree, phone_number)
VALUES (1, 'Maestría en Educación', '555-1234');

-- Insertar alumnos
INSERT INTO students (control_number, id_user, id_modality, semester)
VALUES 
  (1001, 2, 1, 1),
  (1002, 3, 1, 1);

-- Asignar materias a alumnos
INSERT INTO student_subjects (id_user, id_subject)
VALUES 
  (2, 1),
  (2, 2),
  (3, 1);

-- Asignar materias a docente
INSERT INTO teacher_subjects (id_user, id_subject)
VALUES 
  (1, 1),
  (1, 2);

-- Actualizar número telefónico de docente
UPDATE teaching SET phone_number = '555-9999' WHERE id_user = 1;

-- Cambiar semestre de un alumno
UPDATE students SET semester = 2 WHERE id_user = 2;

-- Cambiar materia asignada a un alumno
UPDATE student_subjects SET id_subject = 2 WHERE id_user = 3;

-- Ver docentes con sus títulos y correo
SELECT t.id, u.first_name, u.last_name, t.highest_degree, u.email
FROM teaching t
JOIN users u ON t.id_user = u.id;

-- Ver alumnos y sus materias inscritas
SELECT s.id, u.first_name, u.last_name, subj.name_subject
FROM student_subjects ss
JOIN users u ON ss.id_user = u.id
JOIN students s ON s.id_user = u.id
JOIN subjects subj ON ss.id_subject = subj.id;

-- Ver materias asignadas a cada docente
SELECT u.first_name, u.last_name, subj.name_subject
FROM teacher_subjects ts
JOIN users u ON ts.id_user = u.id
JOIN subjects subj ON ts.id_subject = subj.id;

-- Ver materias que tienen tanto alumnos como docente asignado
SELECT DISTINCT subj.name_subject
FROM subjects subj
JOIN student_subjects ss ON ss.id_subject = subj.id
JOIN teacher_subjects ts ON ts.id_subject = subj.id;
