-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3307
-- Tiempo de generación: 08-05-2025 a las 01:54:48
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `stbh`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `id_student_subject` int(11) DEFAULT NULL,
  `dates` date DEFAULT NULL,
  `present` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `education_levels`
--

CREATE TABLE `education_levels` (
  `id` int(11) NOT NULL,
  `name_level` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `education_levels`
--

INSERT INTO `education_levels` (`id`, `name_level`) VALUES
(1, 'bachillerato'),
(2, 'básico');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grades_per_unit`
--

CREATE TABLE `grades_per_unit` (
  `id` int(11) NOT NULL,
  `id_enrollment` int(11) DEFAULT NULL,
  `unit_number` int(11) DEFAULT NULL,
  `grade` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grades_per_unit`
--

INSERT INTO `grades_per_unit` (`id`, `id_enrollment`, `unit_number`, `grade`) VALUES
(1, 1, 1, 90.00),
(2, 1, 2, 90.50),
(3, 2, 1, 80.00),
(4, 2, 2, 80.50),
(5, 3, 1, 90.20),
(6, 3, 2, 90.80);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `group_subject_assignment`
--

CREATE TABLE `group_subject_assignment` (
  `id` int(11) NOT NULL,
  `id_group` int(11) DEFAULT NULL,
  `id_subject` int(11) DEFAULT NULL,
  `id_teacher` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `group_subject_assignment`
--

INSERT INTO `group_subject_assignment` (`id`, `id_group`, `id_subject`, `id_teacher`) VALUES
(1, 1, 1, 2),
(2, 1, 2, 2),
(3, 2, 3, 2),
(4, 3, 4, 2),
(5, 4, 1, 2),
(6, NULL, 1, 1),
(7, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE `grupos` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `id_modality_level` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grupos`
--

INSERT INTO `grupos` (`id`, `name`, `id_modality_level`) VALUES
(1, 'Grupo A - Internado Bachillerato', 1),
(2, 'Grupo B - Internado Básico', 2),
(3, 'Grupo C - Sabatino Básico', 3),
(4, 'Grupo D - Online Básico', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modalities`
--

CREATE TABLE `modalities` (
  `id` int(11) NOT NULL,
  `name_modality` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `modalities`
--

INSERT INTO `modalities` (`id`, `name_modality`) VALUES
(1, 'internado'),
(3, 'online'),
(2, 'sabatino');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modality_level`
--

CREATE TABLE `modality_level` (
  `id` int(11) NOT NULL,
  `id_modality` int(11) DEFAULT NULL,
  `id_level` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `modality_level`
--

INSERT INTO `modality_level` (`id`, `id_modality`, `id_level`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 2),
(4, 3, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil`
--

CREATE TABLE `perfil` (
  `id` int(11) NOT NULL,
  `name_perfil` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `perfil`
--

INSERT INTO `perfil` (`id`, `name_perfil`) VALUES
(1, 'administrador'),
(2, 'docente'),
(3, 'alumno');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `id` decimal(3,1) NOT NULL,
  `name_permissions` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permissions`
--

INSERT INTO `permissions` (`id`, `name_permissions`) VALUES
(1.0, 'ver usuarios'),
(5.0, 'ver materias'),
(5.1, 'añadir materia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissionsxprofile`
--

CREATE TABLE `permissionsxprofile` (
  `id_perfil` int(11) DEFAULT NULL,
  `id_permissions` decimal(3,1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permissionsxprofile`
--

INSERT INTO `permissionsxprofile` (`id_perfil`, `id_permissions`) VALUES
(1, 1.0),
(1, 5.0),
(1, 5.1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `id_assignment` int(11) DEFAULT NULL,
  `day_of_week` enum('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo') DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `schedules`
--

INSERT INTO `schedules` (`id`, `id_assignment`, `day_of_week`, `start_time`, `end_time`) VALUES
(1, 1, 'Lunes', '06:00:00', '07:00:00'),
(2, 1, 'Martes', '06:00:00', '07:00:00'),
(3, 2, 'Miércoles', '06:00:00', '07:00:00'),
(4, 2, 'Jueves', '06:00:00', '07:00:00'),
(5, 3, 'Viernes', '07:00:00', '08:00:00'),
(6, 4, 'Sábado', '08:00:00', '09:30:00'),
(7, 5, 'Lunes', '18:00:00', '20:00:00'),
(8, 5, 'Martes', '18:00:00', '20:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `school_calendar`
--

CREATE TABLE `school_calendar` (
  `id` int(11) NOT NULL,
  `dates` date DEFAULT NULL,
  `is_school_day` tinyint(1) DEFAULT 1,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `school_calendar`
--

INSERT INTO `school_calendar` (`id`, `dates`, `is_school_day`, `description`) VALUES
(1, '2025-05-06', 1, NULL),
(2, '2025-05-07', 1, NULL),
(3, '2025-05-08', 1, NULL),
(4, '2025-05-09', 1, NULL),
(5, '2025-05-10', 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `control_number` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_modality` int(11) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `student_group_assignment`
--

CREATE TABLE `student_group_assignment` (
  `id` int(11) NOT NULL,
  `student_subject_id` int(11) NOT NULL,
  `group_subject_assignment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `student_group_assignment`
--

INSERT INTO `student_group_assignment` (`id`, `student_subject_id`, `group_subject_assignment_id`) VALUES
(12, 27, 2),
(13, 29, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `student_subjects`
--

CREATE TABLE `student_subjects` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_subject` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `student_subjects`
--

INSERT INTO `student_subjects` (`id`, `id_user`, `id_subject`) VALUES
(27, 1, 2),
(28, 1, 3),
(29, 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `student_subject_enrollment`
--

CREATE TABLE `student_subject_enrollment` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_subject` int(11) NOT NULL,
  `id_modality` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `enrollment_year` year(4) DEFAULT NULL,
  `enrollment_period` enum('Enero-Junio','Agosto-Diciembre') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `student_subject_enrollment`
--

INSERT INTO `student_subject_enrollment` (`id`, `id_user`, `id_subject`, `id_modality`, `semester`, `enrollment_year`, `enrollment_period`) VALUES
(1, 3, 1, 1, 2, '2025', 'Enero-Junio'),
(2, 3, 2, 1, 1, '2025', 'Enero-Junio'),
(3, 3, 3, 1, 2, '2025', 'Enero-Junio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `name_subject` varchar(60) DEFAULT NULL,
  `code` varchar(20) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `subjects`
--

INSERT INTO `subjects` (`id`, `name_subject`, `code`, `semester`) VALUES
(1, 'INTRODUCCION AL NUEVO TESTAMENTO II', 'INT2', 2),
(2, 'INTRODUCCION AL NUEVO TESTAMENTO I', 'INT1', 1),
(3, 'INTRODUCCION AL ANTIGUO TESTAMENTO II', 'IAT2', 2),
(4, 'INTRODUCCION AL ANTIGUO TESTAMENTO I', 'IAT1', 1),
(5, 'IGLECRECIMIENTO II', 'IGC2', 2),
(6, 'IGLECRECIMIENTO I', 'IGC1', 1),
(7, 'HOMILETICA II', 'HOM2', 2),
(8, 'HOMILETICA I', 'HOM1', 1),
(9, 'HISTORIA DEL CRISTIANISMO II', 'HDC2', 2),
(10, 'HISTORIA DEL CRISTIANISMO I', 'HDC1', 1),
(11, 'FUNDAMENTOS DE DIRECCION DE CANTO II', 'FDC2', 2),
(12, 'FUNDAMENTOS DE DIRECCION DE CANTO I', 'FDC1', 1),
(13, 'DOCTRINA CRISTIANA II', 'DTC2', 2),
(14, 'DOCTRINA CRISTIANA I', 'DTC1', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subject_modality_level`
--

CREATE TABLE `subject_modality_level` (
  `id` int(11) NOT NULL,
  `id_subject` int(11) DEFAULT NULL,
  `id_modality` int(11) DEFAULT NULL,
  `id_level` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `subject_modality_level`
--

INSERT INTO `subject_modality_level` (`id`, `id_subject`, `id_modality`, `id_level`) VALUES
(1, 1, 1, 2),
(2, 1, 2, 2),
(3, 1, 3, 2),
(4, 2, 1, 2),
(5, 2, 2, 2),
(6, 2, 3, 2),
(7, 3, 1, 2),
(8, 3, 2, 2),
(9, 3, 3, 2),
(10, 4, 1, 2),
(11, 4, 2, 2),
(12, 4, 3, 2),
(13, 5, 1, 2),
(14, 5, 2, 2),
(15, 5, 3, 2),
(16, 6, 1, 2),
(17, 6, 2, 2),
(18, 6, 3, 2),
(19, 7, 1, 2),
(20, 7, 2, 2),
(21, 7, 3, 2),
(22, 8, 1, 2),
(23, 8, 2, 2),
(24, 8, 3, 2),
(25, 9, 1, 2),
(26, 9, 2, 2),
(27, 9, 3, 2),
(28, 10, 1, 2),
(29, 10, 2, 2),
(30, 10, 3, 2),
(31, 11, 1, 2),
(32, 11, 2, 2),
(33, 11, 3, 2),
(34, 12, 1, 2),
(35, 12, 2, 2),
(36, 12, 3, 2),
(37, 13, 1, 2),
(38, 13, 2, 2),
(39, 13, 3, 2),
(40, 14, 1, 2),
(41, 14, 2, 2),
(42, 14, 3, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subject_units_by_semester`
--

CREATE TABLE `subject_units_by_semester` (
  `id` int(11) NOT NULL,
  `id_subject` int(11) DEFAULT NULL,
  `id_modality` int(11) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `total_units` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `subject_units_by_semester`
--

INSERT INTO `subject_units_by_semester` (`id`, `id_subject`, `id_modality`, `semester`, `total_units`) VALUES
(1, 1, 1, 2, 2),
(2, 2, 1, 1, 2),
(3, 3, 1, 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `teacher_subjects`
--

CREATE TABLE `teacher_subjects` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_subject` int(11) NOT NULL,
  `id_modality` int(11) NOT NULL,
  `id_level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `teaching`
--

CREATE TABLE `teaching` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `highest_degree` varchar(50) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `teaching`
--

INSERT INTO `teaching` (`id`, `id_user`, `highest_degree`, `phone_number`) VALUES
(1, 2, 'Licenciatura en Teología', '8461029084');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  `pass` varchar(45) DEFAULT NULL,
  `first_name` varchar(35) DEFAULT NULL,
  `last_name` varchar(60) DEFAULT NULL,
  `id_perfil` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `email`, `pass`, `first_name`, `last_name`, `id_perfil`) VALUES
(1, 'admin@stbh.com', 'admin', 'admin', 'sistema', 1),
(2, 'd2507001@stbh.com', 'docente', 'Ranulfo', 'Hernandez Rodriguez', 2),
(3, 'a2507001@stbh.com', 'alumno', 'Eliezer', 'Hernandez Geronimo', 3);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_student_subject` (`id_student_subject`);

--
-- Indices de la tabla `education_levels`
--
ALTER TABLE `education_levels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name_level` (`name_level`);

--
-- Indices de la tabla `grades_per_unit`
--
ALTER TABLE `grades_per_unit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_enrollment` (`id_enrollment`);

--
-- Indices de la tabla `group_subject_assignment`
--
ALTER TABLE `group_subject_assignment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_group` (`id_group`),
  ADD KEY `id_subject` (`id_subject`),
  ADD KEY `id_teacher` (`id_teacher`);

--
-- Indices de la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_modality_level` (`id_modality_level`);

--
-- Indices de la tabla `modalities`
--
ALTER TABLE `modalities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name_modality` (`name_modality`);

--
-- Indices de la tabla `modality_level`
--
ALTER TABLE `modality_level`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_modality` (`id_modality`),
  ADD KEY `id_level` (`id_level`);

--
-- Indices de la tabla `perfil`
--
ALTER TABLE `perfil`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `permissionsxprofile`
--
ALTER TABLE `permissionsxprofile`
  ADD KEY `id_perfil` (`id_perfil`),
  ADD KEY `id_permissions` (`id_permissions`);

--
-- Indices de la tabla `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_assignment` (`id_assignment`);

--
-- Indices de la tabla `school_calendar`
--
ALTER TABLE `school_calendar`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `control_number` (`control_number`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_modality` (`id_modality`);

--
-- Indices de la tabla `student_group_assignment`
--
ALTER TABLE `student_group_assignment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_subject_id` (`student_subject_id`),
  ADD KEY `group_subject_assignment_id` (`group_subject_assignment_id`);

--
-- Indices de la tabla `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_subject` (`id_subject`);

--
-- Indices de la tabla `student_subject_enrollment`
--
ALTER TABLE `student_subject_enrollment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_subject` (`id_subject`),
  ADD KEY `id_modality` (`id_modality`);

--
-- Indices de la tabla `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indices de la tabla `subject_modality_level`
--
ALTER TABLE `subject_modality_level`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_subject` (`id_subject`),
  ADD KEY `id_modality` (`id_modality`),
  ADD KEY `id_level` (`id_level`);

--
-- Indices de la tabla `subject_units_by_semester`
--
ALTER TABLE `subject_units_by_semester`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_subject` (`id_subject`),
  ADD KEY `id_modality` (`id_modality`);

--
-- Indices de la tabla `teacher_subjects`
--
ALTER TABLE `teacher_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_subject` (`id_subject`),
  ADD KEY `id_modality` (`id_modality`),
  ADD KEY `id_level` (`id_level`);

--
-- Indices de la tabla `teaching`
--
ALTER TABLE `teaching`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_perfil` (`id_perfil`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `education_levels`
--
ALTER TABLE `education_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `grades_per_unit`
--
ALTER TABLE `grades_per_unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `group_subject_assignment`
--
ALTER TABLE `group_subject_assignment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `modalities`
--
ALTER TABLE `modalities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `modality_level`
--
ALTER TABLE `modality_level`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `perfil`
--
ALTER TABLE `perfil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `school_calendar`
--
ALTER TABLE `school_calendar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `student_group_assignment`
--
ALTER TABLE `student_group_assignment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `student_subjects`
--
ALTER TABLE `student_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `student_subject_enrollment`
--
ALTER TABLE `student_subject_enrollment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `subject_modality_level`
--
ALTER TABLE `subject_modality_level`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `subject_units_by_semester`
--
ALTER TABLE `subject_units_by_semester`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `teacher_subjects`
--
ALTER TABLE `teacher_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `teaching`
--
ALTER TABLE `teaching`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`id_student_subject`) REFERENCES `student_subjects` (`id`);

--
-- Filtros para la tabla `grades_per_unit`
--
ALTER TABLE `grades_per_unit`
  ADD CONSTRAINT `grades_per_unit_ibfk_1` FOREIGN KEY (`id_enrollment`) REFERENCES `student_subject_enrollment` (`id`);

--
-- Filtros para la tabla `group_subject_assignment`
--
ALTER TABLE `group_subject_assignment`
  ADD CONSTRAINT `group_subject_assignment_ibfk_1` FOREIGN KEY (`id_group`) REFERENCES `grupos` (`id`),
  ADD CONSTRAINT `group_subject_assignment_ibfk_2` FOREIGN KEY (`id_subject`) REFERENCES `subjects` (`id`),
  ADD CONSTRAINT `group_subject_assignment_ibfk_3` FOREIGN KEY (`id_teacher`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD CONSTRAINT `grupos_ibfk_1` FOREIGN KEY (`id_modality_level`) REFERENCES `modality_level` (`id`);

--
-- Filtros para la tabla `modality_level`
--
ALTER TABLE `modality_level`
  ADD CONSTRAINT `modality_level_ibfk_1` FOREIGN KEY (`id_modality`) REFERENCES `modalities` (`id`),
  ADD CONSTRAINT `modality_level_ibfk_2` FOREIGN KEY (`id_level`) REFERENCES `education_levels` (`id`);

--
-- Filtros para la tabla `permissionsxprofile`
--
ALTER TABLE `permissionsxprofile`
  ADD CONSTRAINT `permissionsxprofile_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `perfil` (`id`),
  ADD CONSTRAINT `permissionsxprofile_ibfk_2` FOREIGN KEY (`id_permissions`) REFERENCES `permissions` (`id`);

--
-- Filtros para la tabla `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`id_assignment`) REFERENCES `group_subject_assignment` (`id`);

--
-- Filtros para la tabla `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`id_modality`) REFERENCES `modalities` (`id`);

--
-- Filtros para la tabla `student_group_assignment`
--
ALTER TABLE `student_group_assignment`
  ADD CONSTRAINT `student_group_assignment_ibfk_1` FOREIGN KEY (`student_subject_id`) REFERENCES `student_subjects` (`id`),
  ADD CONSTRAINT `student_group_assignment_ibfk_2` FOREIGN KEY (`group_subject_assignment_id`) REFERENCES `group_subject_assignment` (`id`);

--
-- Filtros para la tabla `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD CONSTRAINT `student_subjects_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `student_subjects_ibfk_2` FOREIGN KEY (`id_subject`) REFERENCES `subjects` (`id`);

--
-- Filtros para la tabla `student_subject_enrollment`
--
ALTER TABLE `student_subject_enrollment`
  ADD CONSTRAINT `student_subject_enrollment_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `student_subject_enrollment_ibfk_2` FOREIGN KEY (`id_subject`) REFERENCES `subjects` (`id`),
  ADD CONSTRAINT `student_subject_enrollment_ibfk_3` FOREIGN KEY (`id_modality`) REFERENCES `modalities` (`id`);

--
-- Filtros para la tabla `subject_modality_level`
--
ALTER TABLE `subject_modality_level`
  ADD CONSTRAINT `subject_modality_level_ibfk_1` FOREIGN KEY (`id_subject`) REFERENCES `subjects` (`id`),
  ADD CONSTRAINT `subject_modality_level_ibfk_2` FOREIGN KEY (`id_modality`) REFERENCES `modalities` (`id`),
  ADD CONSTRAINT `subject_modality_level_ibfk_3` FOREIGN KEY (`id_level`) REFERENCES `education_levels` (`id`);

--
-- Filtros para la tabla `subject_units_by_semester`
--
ALTER TABLE `subject_units_by_semester`
  ADD CONSTRAINT `subject_units_by_semester_ibfk_1` FOREIGN KEY (`id_subject`) REFERENCES `subjects` (`id`),
  ADD CONSTRAINT `subject_units_by_semester_ibfk_2` FOREIGN KEY (`id_modality`) REFERENCES `modalities` (`id`);

--
-- Filtros para la tabla `teacher_subjects`
--
ALTER TABLE `teacher_subjects`
  ADD CONSTRAINT `teacher_subjects_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `teacher_subjects_ibfk_2` FOREIGN KEY (`id_subject`) REFERENCES `subjects` (`id`),
  ADD CONSTRAINT `teacher_subjects_ibfk_3` FOREIGN KEY (`id_modality`) REFERENCES `modalities` (`id`),
  ADD CONSTRAINT `teacher_subjects_ibfk_4` FOREIGN KEY (`id_level`) REFERENCES `education_levels` (`id`);

--
-- Filtros para la tabla `teaching`
--
ALTER TABLE `teaching`
  ADD CONSTRAINT `teaching_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `perfil` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
