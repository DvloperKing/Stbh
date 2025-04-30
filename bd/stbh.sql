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
-- calificaciones
create table grades(
id int auto_increment primary key,
id_student_subject int,
unit_number int,
grade decimal(5,2),
foreign key (id_student_subject) references student_subjects(id)
);
-- tabla de unidades 
create table subject_units(
id_subject int,
total_units int,
foreign key (id_subject) references subjects(id)
);
-- calendario escolar
create table school_calendar(
id int auto_increment primary key,
dates date,
is_school_day tinyint(1) default 1,
description varchar(255)
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

-- Insert de materias a estudiantes
insert into student_subjects (id_user, id_subject) values (1, 1);
insert into student_subjects (id_user, id_subject) values (1, 2);
insert into student_subjects (id_user, id_subject) values (1, 3);
insert into student_subjects (id_user, id_subject) values (1, 4);
insert into student_subjects (id_user, id_subject) values (1, 5);
-- Insertar asistencias
insert into attendance (id_student_subject, dates, present) values (1, '2025-04-07', 1);
insert into attendance (id_student_subject, dates, present) values (1, '2025-04-21', 1);
insert into attendance (id_student_subject, dates, present) values (1, '2025-04-22', 1);
insert into attendance (id_student_subject, dates, present) values (1, '2025-04-01', 1);
insert into attendance (id_student_subject, dates, present) values (1, '2025-04-02', 1);
insert into attendance (id_student_subject, dates, present) values (1, '2025-04-30', 1);
insert into attendance (id_student_subject, dates, present) values (2, '2025-04-01', 1);
insert into attendance (id_student_subject, dates, present) values (2, '2025-05-06', 1);
insert into attendance (id_student_subject, dates, present) values (2, '2025-05-07', 1);
insert into attendance (id_student_subject, dates, present) values (1, '2025-05-05', 1);
insert into attendance (id_student_subject, dates, present) values (1, '2025-05-06', 1);
insert into attendance (id_student_subject, dates, present) values (1, '2025-05-07', 1);
insert into attendance (id_student_subject, dates, present) values (1, '2025-05-08', 1);
insert into attendance (id_student_subject, dates, present) values (1, '2025-05-09', 1);

-- Insertar calificaciones para id_student_subject 1
insert into grades (id_student_subject, unit_number, grade) values (1, 1, 85.00);
insert into grades (id_student_subject, unit_number, grade) values (1, 2, 90.00);
insert into grades (id_student_subject, unit_number, grade) values (1, 3, 88.50);
insert into grades (id_student_subject, unit_number, grade) values (1, 4, 92.00);
insert into grades (id_student_subject, unit_number, grade) values (1, 5, 87.00);
insert into grades (id_student_subject, unit_number, grade) values (1, 6, 91.00);

-- Insertar cantidad de unidades para la materia 1
insert into subject_units (id_subject, total_units) values (1, 6);

-- Insertar días escolares
insert into school_calendar (dates, is_school_day, description) values ('2025-04-01', 1, 'Inicio de clases');
insert into school_calendar (dates, is_school_day, description) values ('2025-04-07', 1, 'Clase regular');
insert into school_calendar (dates, is_school_day, description) values ('2025-04-21', 1, 'Clase regular');
insert into school_calendar (dates, is_school_day, description) values ('2025-05-01', 0, 'Día del Trabajo (no hay clases)');
insert into school_calendar (dates, is_school_day, description) values ('2025-05-05', 1, 'Clase regular');

insert into teaching (id_user, highest_degree, phone_number) values (2, 'Licenciatura en Teología', '8461029084');

SELECT u.id, u.email, u.pass, u.id_perfil, t.id_user
FROM users u
JOIN teaching t ON u.id = t.id_user
WHERE u.email = 'd2507001@stbh.com';

select * from users;
select * from perfil;
SELECT * FROM student_subjects;
select * from attendance;


-- CONSULTA PARA MODULO ALUMNO

INSERT INTO student_subjects (id_user, id_subject)
SELECT 3 AS id_user, s.id
FROM subjects s
JOIN subject_modality_level sml ON sml.id_subject = s.id
WHERE s.semester = 1
  AND sml.id_modality = 3
  AND sml.id_level = 2
  AND NOT EXISTS (
    SELECT 1 FROM student_subjects ss
    WHERE ss.id_user = 3 AND ss.id_subject = s.id
  );
  
SELECT 
  CONCAT(u.first_name, ' ', u.last_name) AS alumno,
  s.name_subject,
  s.code,
  s.semester,
  m.name_modality,
  e.name_level
FROM student_subjects ss
JOIN subjects s ON ss.id_subject = s.id
JOIN users u ON ss.id_user = u.id
JOIN students st ON st.id_user = u.id
JOIN subject_modality_level sml ON sml.id_subject = s.id
JOIN modalities m ON m.id = sml.id_modality
JOIN education_levels e ON e.id = sml.id_level
WHERE ss.id_user = 3
  AND sml.id_modality = st.id_modality
  AND sml.id_level = (
    SELECT ml.id_level
    FROM modality_level ml
    WHERE ml.id_modality = st.id_modality
    LIMIT 1
  );


-- CONSULTA PARA MODULO DOCENTE
-- Asignar todas las materias del semestre 1 (nivel básico, modalidad internado) al docente id_user = 2
INSERT INTO teacher_subjects (id_user, id_subject, id_modality, id_level)
SELECT * FROM (
  SELECT 2 AS id_user, sub.id AS id_subject, 1 AS id_modality, 2 AS id_level
  FROM subjects sub
  JOIN subject_modality_level sml ON sml.id_subject = sub.id
  WHERE sub.semester = 1 AND sml.id_modality = 1 AND sml.id_level = 2
) AS posibles
WHERE NOT EXISTS (
  SELECT 1 FROM teacher_subjects ts
  WHERE ts.id_user = posibles.id_user 
    AND ts.id_subject = posibles.id_subject 
    AND ts.id_modality = posibles.id_modality 
    AND ts.id_level = posibles.id_level
);
SELECT 
  CONCAT(u.first_name, ' ', u.last_name) AS docente,
  s.name_subject,
  s.code,
  s.semester,
  m.name_modality,
  e.name_level
FROM teacher_subjects ts
JOIN users u ON u.id = ts.id_user
JOIN subjects s ON s.id = ts.id_subject
JOIN modalities m ON m.id = ts.id_modality
JOIN education_levels e ON e.id = ts.id_level
WHERE ts.id_user = 2;
