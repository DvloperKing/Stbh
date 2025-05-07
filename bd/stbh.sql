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
create table grupos (
    id int auto_increment primary key,
    name varchar(100),
    id_modality_level int,
    foreign key (id_modality_level) references modality_level(id)
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

-- Tabla que registra la asistencia de un estudiante en una materia específica en un día determinado
create table attendance(
id int auto_increment primary key,
id_enrollment int,
attendance_date date,
present tinyint(1) not null default 0,
foreign key (id_enrollment) references student_subject_enrollment(id)
);

-- tabla que registra qué materias cursa cada estudiante, en qué modalidad, en qué semestre y en qué periodo del año.
CREATE TABLE student_subject_enrollment (
  id int auto_increment primary key,
  id_user int not null,
  id_subject int not null,
  id_modality int not null,
  semester int not null,
  enrollment_year year,
  enrollment_period enum('Enero-Junio', 'Agosto-Diciembre'),
  foreign key (id_user) references users(id),
  foreign key (id_subject) references subjects(id),
  foreign key (id_modality) references modalities(id)
);
-- Tabla que registra las calificaciones por unidad de cada materia que cursa un alumno.
create table grades_per_unit (
  id int auto_increment primary key,
  id_enrollment int,
  unit_number int,
  grade decimal(5,2),
  foreign key (id_enrollment) references student_subject_enrollment(id)
);
-- Tabla que registra cuántas unidades tiene una materia específica según la modalidad y el semestre en que se imparte
CREATE TABLE subject_units_by_semester (
  id int auto_increment primary key,
  id_subject int,
  id_modality int,
  semester int,
  total_units int,
  foreign key (id_subject) references subjects(id),
  foreign key (id_modality) references modalities(id)
);
-- calendario escolar
create table school_calendar(
id int auto_increment primary key,
dates date,
is_school_day tinyint(1) default 1,
description varchar(255)
);
-- Tabla que  asigna materias a un grupo específico con un docente responsable
create table group_subject_assignment (
    id int auto_increment primary key,
    id_group int,
    id_subject int,
    id_teacher int,
    foreign key (id_group) references grupos(id),
    foreign key (id_subject) references subjects(id),
    foreign key (id_teacher) references users(id)
);
-- Tabla que registra el horario en que se imparte una materia específica a un grupo determinado
create table schedules (
    id int auto_increment primary key,
    id_assignment int, -- Apunta a group_subject_assignment
    day_of_week enum('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'),
    start_time time,
    end_time time,
    foreign key (id_assignment) references group_subject_assignment(id)
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
insert into users (email, pass, first_name, last_name, id_perfil) values ('a2507002@stbh.com', 'alumno', 'Eliel', 'Hernandez Geronimo', 3);
insert into users (email, pass, first_name, last_name, id_perfil) values ('a2507003@stbh.com', 'alumno', 'Ami', 'Hernandez Geronimo', 3);

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

-- inserta grupos en la tabla grupos, especificando el nombre del grupo y la modalidad-nivel educativo a la que pertenecen
insert into grupos (name, id_modality_level) values ('Grupo A', 1);
insert into grupos (name, id_modality_level) values ('Grupo B', 2);
insert into grupos (name, id_modality_level) values ('Grupo C', 3);
insert into grupos (name, id_modality_level) values ('Grupo D', 4);

-- inserta la relacion que hay entre el grupo, materia y docente
-- Suponiendo:
-- Grupo A (id = 1), Grupo B (id = 2), Grupo C (id = 3), Grupo D (id = 4)
-- Materias: id = 1 a 4 (por ejemplo)
-- Docente: id_user = 2 (Ranulfo)
insert into group_subject_assignment (id_group, id_subject, id_teacher) values (2, 2, 2); -- Grupo B - INTRODUCCIÓN AL NUEVO TESTAMENTO I
insert into group_subject_assignment (id_group, id_subject, id_teacher) values (2, 4, 2); -- Grupo B - INTRODUCCION AL ANTIGUO TESTAMENTO I
insert into group_subject_assignment (id_group, id_subject, id_teacher) values (3, 7, 2); -- Grupo C - HOMILETICA II
insert into group_subject_assignment (id_group, id_subject, id_teacher) values (4, 13, 2); -- Grupo D - DOCTRINA CRISTIANA II

-- inserta los horarios semanales de clases para las materias que ya fueron asignadas a los grupos mediante la tabla group_subject_assignment.
-- Grupo B (Internado) – Lunes a Viernes 6:00 - 14:00
insert into schedules (id_assignment, day_of_week, start_time, end_time) values (1, 'Lunes', '06:00:00', '07:00:00'); -- Grupo B - INTRODUCCIÓN AL NUEVO TESTAMENTO I
insert into schedules (id_assignment, day_of_week, start_time, end_time) values (2, 'Martes', '07:00:00', '08:00:00'); -- Grupo B - INTRODUCCION AL ANTIGUO TESTAMENTO I
-- Grupo C (Sabatino) – Sábado 8:00 - 14:30
insert into schedules (id_assignment, day_of_week, start_time, end_time) values (3, 'Sábado', '08:00:00', '09:00:00'); -- Grupo C - HOMILETICA II
-- Grupo D (Online) – Lunes 18:00 - 22:00 y martes de 18:00 - 20:00
insert into schedules (id_assignment, day_of_week, start_time, end_time) values (4, 'Lunes', '18:00:00', '20:00:00');-- Grupo D - DOCTRINA CRISTIANA II

-- registra en la tabla teaching, que representa los datos académicos y de contacto del docente
insert into teaching (id_user, highest_degree, phone_number) values (2, 'Licenciatura en Teología', '8461029084');

-- Insert que registra las inscripciones de alumnos a materias específicas en la tabla student_subject_enrollment
-- Grupo B - Modalidad Internado (id_modality = 1)
insert into student_subject_enrollment (id_user, id_subject, id_modality, semester, enrollment_year, enrollment_period) values (3, 2, 1, 1, 2025, 'Enero-Junio'); -- INTRODUCCIÓN AL NUEVO TESTAMENTO I
insert into student_subject_enrollment (id_user, id_subject, id_modality, semester, enrollment_year, enrollment_period) values (3, 4, 1, 1, 2025, 'Enero-Junio'); -- INTRODUCCIÓN AL ANTIGUO TESTAMENTO I
-- Grupo C - Modalidad Sabatino (id_modality = 2)
insert into student_subject_enrollment (id_user, id_subject, id_modality, semester, enrollment_year, enrollment_period) values (4, 7, 2, 2, 2025, 'Enero-Junio'); -- HOMILÉTICA II
-- Grupo D - Modalidad Online (id_modality = 3)
insert into student_subject_enrollment (id_user, id_subject, id_modality, semester, enrollment_year, enrollment_period) values (5, 13, 3, 2, 2025, 'Enero-Junio'); -- DOCTRINA CRISTIANA II

-- inserta cuántas unidades tiene una materia específica dependiendo de la modalidad, semestre y materia
insert into subject_units_by_semester (id_subject, id_modality, semester, total_units) values (2, 1, 1, 4);  -- materia 2, modalidad internado, 4 unidades
insert into subject_units_by_semester (id_subject, id_modality, semester, total_units) values (4, 1, 1, 3);  -- materia 4, modalidad internado, 3 unidades
insert into subject_units_by_semester (id_subject, id_modality, semester, total_units) values (7, 2, 2, 6);  -- materia 7, modalidad sabatino, 6 unidades
insert into subject_units_by_semester (id_subject, id_modality, semester, total_units) values (13, 3, 2, 5); -- materia 13, modalidad online, 5 unidades

-- Alumno Eliezer - INTRODUCCIÓN AL NUEVO TESTAMENTO I (4 unidades)
insert into grades_per_unit (id_enrollment, unit_number, grade) values (1, 2, 90.5);
insert into grades_per_unit (id_enrollment, unit_number, grade) values (1, 1, 90.2);
insert into grades_per_unit (id_enrollment, unit_number, grade) values (1, 3, 80.9);
insert into grades_per_unit (id_enrollment, unit_number, grade) values (1, 4, 90.3);

-- Alumno Eliezer - INTRODUCCIÓN AL ANTIGUO TESTAMENTO I (3 unidades)
insert into grades_per_unit (id_enrollment, unit_number, grade) values (2, 1, 80.7);
insert into grades_per_unit (id_enrollment, unit_number, grade) values (2, 2, 90.0);
insert into grades_per_unit (id_enrollment, unit_number, grade) values (2, 3, 90.1);

-- Alumno Eliel - HOMILÉTICA II (6 unidades)
insert into grades_per_unit (id_enrollment, unit_number, grade) values (3, 1, 90.6);
insert into grades_per_unit (id_enrollment, unit_number, grade) values (3, 2, 80.8);
insert into grades_per_unit (id_enrollment, unit_number, grade) values (3, 3, 90.0);
insert into grades_per_unit (id_enrollment, unit_number, grade) values (3, 4, 90.3);
insert into grades_per_unit (id_enrollment, unit_number, grade) values (3, 5, 90.1);
insert into grades_per_unit (id_enrollment, unit_number, grade) values (3, 6, 80.9);

-- Alumna Ami - DOCTRINA CRISTIANA II (5 unidades)
insert into grades_per_unit (id_enrollment, unit_number, grade) values (4, 1, 90.0);
insert into grades_per_unit (id_enrollment, unit_number, grade) values (4, 2, 90.4);
insert into grades_per_unit (id_enrollment, unit_number, grade) values (4, 3, 80.8);
insert into grades_per_unit (id_enrollment, unit_number, grade) values (4, 4, 90.2);
insert into grades_per_unit (id_enrollment, unit_number, grade) values (4, 5, 90.0);

-- CONSULTAS 

-- consulta que  muestra un horario completo de clases para cada grupo, incluyendo materia, modalidad, nivel educativo, docente y horario 
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

-- Consulta que muestra las calificaciones por unidad de un alumno específico (id = 5) durante el año 2025 y en el periodo 'Enero-Junio'
SELECT 
  u.first_name, u.last_name,
  s.name_subject,
  e.semester, e.enrollment_year, e.enrollment_period,
  g.unit_number, g.grade
FROM grades_per_unit g
JOIN student_subject_enrollment e ON g.id_enrollment = e.id
JOIN users u ON e.id_user = u.id
JOIN subjects s ON e.id_subject = s.id
WHERE u.id = 5 AND e.enrollment_year = 2025 AND e.enrollment_period = 'Enero-Junio';

-- Consulta que muestra las asignaciones de materias a grupos, indicando qué materia se imparte, en qué grupo, por quién, y bajo qué modalidad y nivel educativo.
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

-- consulta que muestra un listado de inscripciones de alumnos a materias, con información relevante como la modalidad, semestre, año y periodo
SELECT 
    u.first_name AS nombre,
    u.last_name AS apellido,
    s.name_subject AS materia,
    m.name_modality AS modalidad,
    e.semester,
    e.enrollment_year AS año,
    e.enrollment_period AS periodo
FROM student_subject_enrollment e
JOIN users u ON e.id_user = u.id
JOIN subjects s ON e.id_subject = s.id
JOIN modalities m ON e.id_modality = m.id
ORDER BY u.last_name, u.first_name, e.enrollment_year, e.enrollment_period;