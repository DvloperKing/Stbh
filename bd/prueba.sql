-- Elimia la bd si existe
drop database if exists stbh;
-- Crea la bd si no existe
create database if not exists stbh ;
use stbh;
-- Creacion de perfiles o roles de usuario (administrador, docente, alumno)
-- Constraints: PRIMARY KEY
create table perfil (
  id int auto_increment primary key,
  name_perfil varchar(20)
);
-- Creacion de usuarios del sistema
-- Constraints: PRIMARY KEY, FOREIGN KEY (relación con perfil)
create table users (
  id int auto_increment primary key,
  email varchar(45),
  pass varchar(45),
  first_name varchar(35),
  last_name varchar(60),
  id_perfil int,
  foreign key (id_perfil) references perfil(id)
);
-- Definición de permisos de usuarios
-- Constraints: PRIMARY KEY
create table permissions (
  id decimal(3,1) primary key,
  name_permissions varchar(50)
);
-- Relacion de los perfiles con sus permisos
-- Constraints: FOREIGN KEY (a perfil y permissions)
create table permissionsxprofile (
  id_perfil int,
  id_permissions decimal(3,1),
  foreign key (id_perfil) references perfil(id),
  foreign key (id_permissions) references permissions(id)
);
-- Creacion de los niveles educativos (básico, bachillerato)
-- Constraints: PRIMARY KEY, UNIQUE (name_level)
create table education_levels (
  id int auto_increment primary key,
  name_level varchar(30) unique
);
-- Creacion de las modalidades de estudio (sabatino, internado, online)
-- Constraints: PRIMARY KEY, UNIQUE (name_modality)
create table modalities (
  id int auto_increment primary key,
  name_modality varchar(20) unique
);
-- Relacion entre la tabla modalidades con niveles educativos
-- Constraints: PRIMARY KEY, FOREIGN KEY (a modalities y education_levels)
create table modality_level (
  id int auto_increment primary key,
  id_modality int,
  id_level int,
  foreign key (id_modality) references modalities(id),
  foreign key (id_level) references education_levels(id)
);
-- tabla grupos
CREATE TABLE grupos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    id_modality_level INT,
    FOREIGN KEY (id_modality_level) REFERENCES modality_level(id)
);
-- Creacion de materias
-- Constraints: PRIMARY KEY, UNIQUE (code)
create table subjects (
  id int auto_increment primary key,
  name_subject varchar(60),
  code varchar(20) unique,
  semester int
);
-- Relacion de la tablas, materias con nivel y la modalidad
-- Constraints: PRIMARY KEY, FOREIGN KEY (a subjects, modalities y education_levels)
create table subject_modality_level (
  id int auto_increment primary key,
  id_subject int,
  id_modality int,
  id_level int,
  foreign key (id_subject) references subjects(id),
  foreign key (id_modality) references modalities(id),
  foreign key (id_level) references education_levels(id)
);
-- Tabla con datos adicionales de docentes registrados (vía users)
-- Constraints: PRIMARY KEY, FOREIGN KEY (a users)
create table teaching (
  id int auto_increment primary key,
  id_user int,
  highest_degree varchar(50),
  phone_number varchar(20),
  foreign key (id_user) references users(id)
);
-- Tabla con información de los estudiantes (vincula a users)
-- Constraints: PRIMARY KEY, UNIQUE (control_number), FOREIGN KEY (a users y modalities)
create table students (
  id int auto_increment primary key,
  control_number int unique,
  id_user int,
  id_modality int,
  semester int,
  foreign key (id_user) references users(id),
  foreign key (id_modality) references modalities(id)
);
-- Tabla que indica las materias que esta inscrito un alumno
-- Constraints: PRIMARY KEY, FOREIGN KEY (a users y subjects)
create table student_subjects (
  id int auto_increment primary key,
  id_user int,
  id_subject int,
  foreign key (id_user) references users(id),
  foreign key (id_subject) references subjects(id)
);
-- Tabla que indica las materias que imparte un docente
-- Constraints: PRIMARY KEY, FOREIGN KEY (a users y subjects)
create table teacher_subjects (
  id int auto_increment primary key,
  id_user int not null,
  id_subject int not null,
  id_modality int not null,
  id_level int not null,
  foreign key (id_user) references users(id),
  foreign key (id_subject) references subjects(id),
  foreign key (id_modality) references modalities(id),
  foreign key (id_level) references education_levels(id)
);

-- Tabla de asistencia 
create table attendance(
id int auto_increment primary key,
id_student_subject int,
dates date,
present tinyint(1) not null default 0,
foreign key (id_student_subject) references student_subjects(id)
);
-- tabla estudiantes por materia
CREATE TABLE student_subject_enrollment (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_user INT NOT NULL,
  id_subject INT NOT NULL,
  id_modality INT NOT NULL,
  semester INT NOT NULL,
  enrollment_year YEAR,
  enrollment_period ENUM('Enero-Junio', 'Agosto-Diciembre'),
  FOREIGN KEY (id_user) REFERENCES users(id),
  FOREIGN KEY (id_subject) REFERENCES subjects(id),
  FOREIGN KEY (id_modality) REFERENCES modalities(id)
);
-- calificaciones por unidad
CREATE TABLE grades_per_unit (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_enrollment INT,
  unit_number INT,
  grade DECIMAL(5,2),
  FOREIGN KEY (id_enrollment) REFERENCES student_subject_enrollment(id)
);
-- unidades por semestre y materia
CREATE TABLE subject_units_by_semester (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_subject INT,
  id_modality INT,
  semester INT,
  total_units INT,
  FOREIGN KEY (id_subject) REFERENCES subjects(id),
  FOREIGN KEY (id_modality) REFERENCES modalities(id)
);
-- calendario escolar
create table school_calendar(
id int auto_increment primary key,
dates date,
is_school_day tinyint(1) default 1,
description varchar(255)
);
-- tabla grupos
CREATE TABLE group_subject_assignment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_group INT,
    id_subject INT,
    id_teacher INT,
    FOREIGN KEY (id_group) REFERENCES grupos(id),
    FOREIGN KEY (id_subject) REFERENCES subjects(id),
    FOREIGN KEY (id_teacher) REFERENCES users(id)
);
-- tabla horario
CREATE TABLE schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_assignment INT, -- Apunta a group_subject_assignment
    day_of_week ENUM('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'),
    start_time TIME,
    end_time TIME,
    FOREIGN KEY (id_assignment) REFERENCES group_subject_assignment(id)
);
-- insert perfiles
insert into perfil (name_perfil) values ('administrador');
insert into perfil (name_perfil) values ('docente');
insert into perfil (name_perfil) values ('alumno');
-- insert permisos 
insert into permissions (id, name_permissions) values (1.0, 'ver usuarios');
insert into permissions (id, name_permissions) values (5.0, 'ver materias');
insert into permissions (id, name_permissions) values (5.1, 'añadir materia');
-- insert permisos por perfil
insert into permissionsxprofile (id_perfil, id_permissions) select 1, id from permissions;
-- insert usuarios de prueba
insert into users (email, pass, first_name, last_name, id_perfil) values ('admin@stbh.com', 'admin', 'admin', 'sistema', 1);
insert into users (email, pass, first_name, last_name, id_perfil) values ('d2507001@stbh.com', 'docente', 'Ranulfo', 'Hernandez Rodriguez', 2);
insert into users (email, pass, first_name, last_name, id_perfil) values ('a2507001@stbh.com', 'alumno', 'Eliezer', 'Hernandez Geronimo', 3);
-- insert niveles educativos
insert into education_levels (name_level) values ('bachillerato');
insert into education_levels (name_level) values ('básico');
-- insert modalidades
insert into modalities (name_modality) values ('internado');
insert into modalities (name_modality) values ('sabatino');
insert into modalities (name_modality) values ('online');
-- relacion entre la modalidad y el nivel educativo
insert into modality_level (id_modality, id_level) values (1, 1); -- internado → bachillerato
insert into modality_level (id_modality, id_level) values (1, 2); -- internado → basico
insert into modality_level (id_modality, id_level) values (2, 2); -- sabatino → básico
insert into modality_level (id_modality, id_level) values (3, 2); -- online → básico
-- insert materias
insert into subjects (name_subject, code, semester) values ('INTRODUCCION AL NUEVO TESTAMENTO II', 'INT2', 2);
insert into subjects (name_subject, code, semester) values ('INTRODUCCION AL NUEVO TESTAMENTO I', 'INT1', 1);
insert into subjects (name_subject, code, semester) values ('INTRODUCCION AL ANTIGUO TESTAMENTO II', 'IAT2', 2);
insert into subjects (name_subject, code, semester) values ('INTRODUCCION AL ANTIGUO TESTAMENTO I', 'IAT1', 1);
insert into subjects (name_subject, code, semester) values ('IGLECRECIMIENTO II', 'IGC2', 2);
insert into subjects (name_subject, code, semester) values ('IGLECRECIMIENTO I', 'IGC1', 1);
insert into subjects (name_subject, code, semester) values ('HOMILETICA II', 'HOM2', 2);
insert into subjects (name_subject, code, semester) values ('HOMILETICA I', 'HOM1', 1);
insert into subjects (name_subject, code, semester) values ('HISTORIA DEL CRISTIANISMO II', 'HDC2', 2);
insert into subjects (name_subject, code, semester) values ('HISTORIA DEL CRISTIANISMO I', 'HDC1', 1);
insert into subjects (name_subject, code, semester) values ('FUNDAMENTOS DE DIRECCION DE CANTO II', 'FDC2', 2);
insert into subjects (name_subject, code, semester) values ('FUNDAMENTOS DE DIRECCION DE CANTO I', 'FDC1', 1);
insert into subjects (name_subject, code, semester) values ('DOCTRINA CRISTIANA II', 'DTC2', 2);
insert into subjects (name_subject, code, semester) values ('DOCTRINA CRISTIANA I', 'DTC1', 1);
-- relación  materia - modalidad - nivel
insert into subject_modality_level (id_subject, id_modality, id_level) values (1, 1, 2), (1, 2, 2), (1, 3, 2);
insert into subject_modality_level (id_subject, id_modality, id_level) values (2, 1, 2), (2, 2, 2), (2, 3, 2);
insert into subject_modality_level (id_subject, id_modality, id_level) values (3, 1, 2), (3, 2, 2), (3, 3, 2);
insert into subject_modality_level (id_subject, id_modality, id_level) values (4, 1, 2), (4, 2, 2), (4, 3, 2);
insert into subject_modality_level (id_subject, id_modality, id_level) values (5, 1, 2), (5, 2, 2), (5, 3, 2);
insert into subject_modality_level (id_subject, id_modality, id_level) values (6, 1, 2), (6, 2, 2), (6, 3, 2);
insert into subject_modality_level (id_subject, id_modality, id_level) values (7, 1, 2), (7, 2, 2), (7, 3, 2);
insert into subject_modality_level (id_subject, id_modality, id_level) values (8, 1, 2), (8, 2, 2), (8, 3, 2);
insert into subject_modality_level (id_subject, id_modality, id_level) values (9, 1, 2), (9, 2, 2), (9, 3, 2);
insert into subject_modality_level (id_subject, id_modality, id_level) values (10, 1, 2), (10, 2, 2), (10, 3, 2);
insert into subject_modality_level (id_subject, id_modality, id_level) values (11, 1, 2), (11, 2, 2), (11, 3, 2);
insert into subject_modality_level (id_subject, id_modality, id_level) values (12, 1, 2), (12, 2, 2), (12, 3, 2);
insert into subject_modality_level (id_subject, id_modality, id_level) values (13, 1, 2), (13, 2, 2), (13, 3, 2);
insert into subject_modality_level (id_subject, id_modality, id_level) values (14, 1, 2), (14, 2, 2), (14, 3, 2);
-- inserta los grupos como nuevo grupo
INSERT INTO grupos (name, id_modality_level) VALUES ('Grupo A - Internado Bachillerato', 1),
('Grupo B - Internado Básico', 2),
('Grupo C - Sabatino Básico', 3),
('Grupo D - Online Básico', 4);
-- inserta la relacion que hay entre el grupo, materia y docente
-- Suponiendo:
-- Grupo A (id = 1), Grupo B (id = 2), Grupo C (id = 3), Grupo D (id = 4)
-- Materias: id = 1 a 4 (por ejemplo)
-- Docente: id_user = 2 (Ranulfo)
INSERT INTO group_subject_assignment (id_group, id_subject, id_teacher) VALUES (1, 1, 2), -- Grupo A - INTRODUCCIÓN AL NUEVO TESTAMENTO II
(1, 2, 2), -- Grupo A - INTRODUCCIÓN AL NUEVO TESTAMENTO I
(2, 3, 2), -- Grupo B - INTRODUCCIÓN AL ANTIGUO TESTAMENTO II
(3, 4, 2), -- Grupo C - INTRODUCCIÓN AL ANTIGUO TESTAMENTO I
(4, 1, 2); -- Grupo D - INTRODUCCIÓN AL NUEVO TESTAMENTO II

-- Suponiendo que los ID de group_subject_assignment son 1 a 5
-- Puedes adaptarlos luego según los ID generados
-- inserta los horarios en los grupos ya creados
INSERT INTO schedules (id_assignment, day_of_week, start_time, end_time) VALUES (1, 'Lunes', '06:00:00', '07:00:00'),
-- Grupo A (Internado) – Lunes a Viernes
(1, 'Martes', '06:00:00', '07:00:00'),
(2, 'Miércoles', '06:00:00', '07:00:00'),
(2, 'Jueves', '06:00:00', '07:00:00'),

-- Grupo B (Internado Básico) – Viernes
(3, 'Viernes', '07:00:00', '08:00:00'),

-- Grupo C (Sabatino) – Sábado
(4, 'Sábado', '08:00:00', '09:30:00'),

-- Grupo D (Online) – Lunes y Martes en la noche
(5, 'Lunes', '18:00:00', '20:00:00'),
(5, 'Martes', '18:00:00', '20:00:00');

insert into teaching (id_user, highest_degree, phone_number) values (2, 'Licenciatura en Teología', '8461029084');

SELECT 
    g.name AS grupo,
    mo.name_modality,
    el.name_level,
    s.name_subject,
    u.first_name,
    u.last_name,
    sc.day_of_week,
    sc.start_time,
    sc.end_time
FROM group_subject_assignment gsa
JOIN grupos g ON gsa.id_group = g.id
JOIN modality_level ml ON g.id_modality_level = ml.id
JOIN modalities mo ON ml.id_modality = mo.id
JOIN education_levels el ON ml.id_level = el.id
JOIN subjects s ON gsa.id_subject = s.id
JOIN users u ON gsa.id_teacher = u.id
JOIN schedules sc ON sc.id_assignment = gsa.id
ORDER BY g.name, sc.day_of_week, sc.start_time;


-- insert 
INSERT INTO student_subject_enrollment (id_user, id_subject, id_modality, semester, enrollment_year, enrollment_period) VALUES
(3, 1, 1, 2, 2025, 'Enero-Junio'),
(3, 2, 1, 1, 2025, 'Enero-Junio'),
(3, 3, 1, 2, 2025, 'Enero-Junio');
-- grades_per_unit – Calificaciones por unidad y curso inscrito
INSERT INTO grades_per_unit (id_enrollment, unit_number, grade) VALUES
(1, 1, 90.0),
(1, 2, 90.5),
(2, 1, 80.0),
(2, 2, 80.5),
(3, 1, 90.2),
(3, 2, 90.8);
-- Total de unidades por materia, modalidad y semestre
INSERT INTO subject_units_by_semester (id_subject, id_modality, semester, total_units) VALUES
(1, 1, 2, 2),
(2, 1, 1, 2),
(3, 1, 2, 2);

-- Calificaciones de un alumno en un ciclo específico
SELECT 
  u.first_name, u.last_name,
  s.name_subject,
  e.semester, e.enrollment_year, e.enrollment_period,
  g.unit_number, g.grade
FROM grades_per_unit g
JOIN student_subject_enrollment e ON g.id_enrollment = e.id
JOIN users u ON e.id_user = u.id
JOIN subjects s ON e.id_subject = s.id
WHERE u.id = 3 AND e.enrollment_year = 2025 AND e.enrollment_period = 'Enero-Junio';

SELECT 
  g.name AS grupo,
  m.name_modality,
  e.name_level,
  s.name_subject,
  u.first_name,
  u.last_name
FROM group_subject_assignment ga
JOIN grupos g ON ga.id_group = g.id
JOIN modality_level ml ON g.id_modality_level = ml.id
JOIN modalities m ON ml.id_modality = m.id
JOIN education_levels e ON ml.id_level = e.id
JOIN subjects s ON ga.id_subject = s.id
JOIN users u ON ga.id_teacher = u.id
ORDER BY g.name;



