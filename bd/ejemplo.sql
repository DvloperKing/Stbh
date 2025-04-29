-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3307
-- Tiempo de generación: 25-04-2025 a las 22:14:36
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

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
  `id_student_subject` int(11) NOT NULL,
  `date` date NOT NULL,
  `present` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `attendance`
--

INSERT INTO `attendance` (`id`, `id_student_subject`, `date`, `present`) VALUES
(1, 1, '2025-04-07', 1),
(2, 1, '2025-04-21', 1),
(3, 1, '2025-04-22', 1),
(4, 1, '2025-04-01', 1),
(5, 1, '2025-04-02', 1),
(6, 1, '2025-04-30', 1),
(7, 2, '2025-04-01', 1),
(8, 2, '2025-05-06', 1),
(9, 2, '2025-05-07', 1),
(10, 1, '2025-05-05', 1),
(11, 1, '2025-05-06', 1),
(12, 1, '2025-05-07', 1),
(13, 1, '2025-05-08', 1),
(14, 1, '2025-05-09', 1);

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
(1, 'Universidad');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grades`
--

CREATE TABLE `grades` (
  `id` int(11) NOT NULL,
  `id_student_subject` int(11) DEFAULT NULL,
  `unit_number` int(11) DEFAULT NULL,
  `grade` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grades`
--

INSERT INTO `grades` (`id`, `id_student_subject`, `unit_number`, `grade`) VALUES
(1, 1, 1, 70.00),
(2, 1, 2, 90.00),
(3, 1, 3, 55.00),
(4, 2, 1, 9.00),
(5, 2, 2, 8.70),
(6, 1, 1, 70.00),
(7, 1, 2, 90.00),
(8, 1, 3, 55.00),
(9, 1, 4, 87.00),
(10, 1, 5, 92.00),
(11, 2, 1, 78.00),
(12, 2, 2, 81.00),
(13, 2, 3, 80.00),
(14, 2, 4, 79.00),
(16, 1, 1, 70.00),
(17, 1, 2, 90.00),
(18, 1, 3, 55.00),
(19, 1, 4, 87.00),
(20, 1, 5, 92.00),
(21, 2, 1, 78.00),
(22, 2, 2, 81.00),
(23, 2, 3, 80.00),
(24, 2, 4, 79.00),
(25, 1, 1, 70.00),
(26, 1, 2, 90.00),
(27, 1, 3, 55.00),
(28, 1, 4, 87.00),
(29, 1, 5, 92.00),
(30, 2, 1, 78.00),
(31, 2, 2, 81.00),
(32, 2, 3, 80.00),
(33, 2, 4, 79.00),
(34, 8, 1, 80.00),
(35, 8, 2, 74.00),
(36, 8, 3, 81.00),
(37, 8, 4, 62.00),
(38, 8, 5, 42.00),
(39, 1, 1, 70.00),
(40, 1, 2, 90.00),
(41, 1, 3, 55.00),
(42, 1, 4, 87.00),
(43, 1, 5, 92.00),
(44, 2, 1, 78.00),
(45, 2, 2, 81.00),
(46, 2, 3, 80.00),
(47, 2, 4, 79.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modalities`
--

CREATE TABLE `modalities` (
  `id` int(11) NOT NULL,
  `name_modality` varchar(20) DEFAULT NULL,
  `id_level` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `modalities`
--

INSERT INTO `modalities` (`id`, `name_modality`, `id_level`) VALUES
(1, 'Presencial', 1);

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
(1, 1, 1);

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
(1, 'docente'),
(2, 'alumno');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `id` decimal(3,1) NOT NULL,
  `name_permissions` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissionsxprofile`
--

CREATE TABLE `permissionsxprofile` (
  `id_perfil` int(11) DEFAULT NULL,
  `id_permissions` decimal(3,1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `school_calendar`
--

CREATE TABLE `school_calendar` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `is_school_day` tinyint(1) NOT NULL DEFAULT 1,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `school_calendar`
--

INSERT INTO `school_calendar` (`id`, `date`, `is_school_day`, `description`) VALUES
(1, '2025-04-01', 1, NULL),
(2, '2025-04-02', 1, NULL),
(3, '2025-04-03', 1, NULL),
(4, '2025-04-04', 1, NULL),
(5, '2025-04-05', 0, NULL),
(6, '2025-04-06', 0, NULL),
(7, '2025-04-07', 1, NULL),
(8, '2025-04-08', 1, NULL),
(9, '2025-04-09', 1, NULL),
(10, '2025-04-10', 1, NULL),
(11, '2025-04-11', 1, NULL),
(12, '2025-04-12', 0, NULL),
(13, '2025-04-13', 0, NULL),
(14, '2025-04-14', 1, NULL),
(15, '2025-04-15', 1, NULL),
(16, '2025-04-16', 1, NULL),
(17, '2025-04-17', 1, NULL),
(18, '2025-04-18', 1, NULL),
(19, '2025-04-19', 0, NULL),
(20, '2025-04-20', 0, NULL),
(21, '2025-04-21', 1, NULL),
(22, '2025-04-22', 1, NULL),
(23, '2025-04-23', 1, NULL),
(24, '2025-04-24', 1, NULL),
(25, '2025-04-25', 1, NULL),
(26, '2025-04-26', 0, NULL),
(27, '2025-04-27', 0, NULL),
(28, '2025-04-28', 1, NULL),
(29, '2025-04-29', 1, NULL),
(30, '2025-04-30', 1, NULL),
(31, '2025-05-01', 1, NULL),
(32, '2025-05-02', 1, NULL),
(33, '2025-05-03', 0, NULL),
(34, '2025-05-04', 0, NULL),
(35, '2025-05-05', 1, NULL),
(36, '2025-05-06', 1, NULL),
(37, '2025-05-07', 1, NULL),
(38, '2025-05-08', 1, NULL),
(39, '2025-05-09', 1, NULL),
(40, '2025-05-10', 0, NULL),
(41, '2025-05-11', 0, NULL),
(42, '2025-05-12', 1, NULL),
(43, '2025-05-13', 1, NULL),
(44, '2025-05-14', 1, NULL),
(45, '2025-05-15', 1, NULL),
(46, '2025-05-16', 1, NULL),
(47, '2025-05-17', 0, NULL),
(48, '2025-05-18', 0, NULL),
(49, '2025-05-19', 1, NULL),
(50, '2025-05-20', 1, NULL),
(51, '2025-05-21', 1, NULL),
(52, '2025-05-22', 1, NULL),
(53, '2025-05-23', 1, NULL),
(54, '2025-05-24', 0, NULL),
(55, '2025-05-25', 0, NULL),
(56, '2025-05-26', 1, NULL),
(57, '2025-05-27', 1, NULL),
(58, '2025-05-28', 1, NULL),
(59, '2025-05-29', 1, NULL),
(60, '2025-05-30', 1, NULL),
(61, '2025-05-31', 0, NULL),
(62, '2025-06-01', 0, NULL),
(63, '2025-06-02', 1, NULL),
(64, '2025-06-03', 1, NULL),
(65, '2025-06-04', 1, NULL),
(66, '2025-06-05', 1, NULL),
(67, '2025-06-06', 1, NULL),
(68, '2025-06-07', 0, NULL),
(69, '2025-06-08', 0, NULL),
(70, '2025-06-09', 1, NULL),
(71, '2025-06-10', 1, NULL),
(72, '2025-06-11', 1, NULL),
(73, '2025-06-12', 1, NULL),
(74, '2025-06-13', 1, NULL),
(75, '2025-06-14', 0, NULL),
(76, '2025-06-15', 0, NULL),
(77, '2025-06-16', 1, NULL),
(78, '2025-06-17', 1, NULL),
(79, '2025-06-18', 1, NULL),
(80, '2025-06-19', 1, NULL),
(81, '2025-06-20', 1, NULL),
(82, '2025-06-21', 0, NULL),
(83, '2025-06-22', 0, NULL),
(84, '2025-06-23', 1, NULL),
(85, '2025-06-24', 1, NULL),
(86, '2025-06-25', 1, NULL),
(87, '2025-06-26', 1, NULL),
(88, '2025-06-27', 1, NULL),
(89, '2025-06-28', 0, NULL),
(90, '2025-06-29', 0, NULL),
(91, '2025-06-30', 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `control_number` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_modality` int(11) DEFAULT NULL,
  `first_name` varchar(35) DEFAULT NULL,
  `last_name` varchar(60) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `id_grupo` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `students`
--

INSERT INTO `students` (`id`, `control_number`, `id_user`, `id_modality`, `first_name`, `last_name`, `semester`, `id_grupo`) VALUES
(1, 20230001, 2, 1, 'Ana', 'López', 1, 'A1'),
(2, 10002, 3, 1, 'Sergio', 'Urias', 2, 'B2');

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
(1, 2, 1),
(2, 2, 1),
(7, 1, 1),
(8, 3, 1),
(9, 1, 1),
(10, 3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `name_subject` varchar(20) DEFAULT NULL,
  `code` varchar(20) DEFAULT NULL,
  `semester` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `subjects`
--

INSERT INTO `subjects` (`id`, `name_subject`, `code`, `semester`) VALUES
(1, 'Matemáticas I', 'MAT101', 1),
(2, 'Física', 'FIS101', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subject_group`
--

CREATE TABLE `subject_group` (
  `id_grupo` varchar(5) NOT NULL,
  `horario` time NOT NULL,
  `id_subjects` int(11) DEFAULT NULL,
  `id_modality_level` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `subject_group`
--

INSERT INTO `subject_group` (`id_grupo`, `horario`, `id_subjects`, `id_modality_level`) VALUES
('', '00:00:00', NULL, 1),
('A1', '08:00:00', 1, 1),
('A2', '13:00:00', 2, 1),
('A5', '09:00:00', 2, 1),
('B2', '10:00:00', 1, 1),
('C3', '12:00:00', 1, 1);

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subject_units`
--

CREATE TABLE `subject_units` (
  `id_subject` int(11) NOT NULL,
  `total_units` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `subject_units`
--

INSERT INTO `subject_units` (`id_subject`, `total_units`) VALUES
(1, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `teacher_subjects`
--

CREATE TABLE `teacher_subjects` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_subject` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `teaching`
--

CREATE TABLE `teaching` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `first_name` varchar(35) DEFAULT NULL,
  `last_name` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `teaching`
--

INSERT INTO `teaching` (`id`, `id_user`, `first_name`, `last_name`) VALUES
(1, 1, 'Carlos', 'García');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  `pass` varchar(45) DEFAULT NULL,
  `id_perfil` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `email`, `pass`, `id_perfil`) VALUES
(1, 'docente1@correo.com', 'pass123', 1),
(2, 'alumno1@correo.com', 'pass456', 2),
(3, 'student1@example.com', '1234', 1),
(4, 'student2@example.com', '1234', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_student_subject` (`id_student_subject`,`date`);

--
-- Indices de la tabla `education_levels`
--
ALTER TABLE `education_levels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name_level` (`name_level`);

--
-- Indices de la tabla `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_student_subject` (`id_student_subject`);

--
-- Indices de la tabla `modalities`
--
ALTER TABLE `modalities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name_modality` (`name_modality`),
  ADD KEY `id_level` (`id_level`);

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
-- Indices de la tabla `school_calendar`
--
ALTER TABLE `school_calendar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `date` (`date`);

--
-- Indices de la tabla `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `control_number` (`control_number`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_modality` (`id_modality`),
  ADD KEY `fk_students_group` (`id_grupo`);

--
-- Indices de la tabla `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_subject` (`id_subject`);

--
-- Indices de la tabla `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indices de la tabla `subject_group`
--
ALTER TABLE `subject_group`
  ADD PRIMARY KEY (`id_grupo`),
  ADD KEY `id_subjects` (`id_subjects`),
  ADD KEY `id_modality_level` (`id_modality_level`);

--
-- Indices de la tabla `subject_modality_level`
--
ALTER TABLE `subject_modality_level`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_subject` (`id_subject`),
  ADD KEY `id_modality` (`id_modality`),
  ADD KEY `id_level` (`id_level`);

--
-- Indices de la tabla `subject_units`
--
ALTER TABLE `subject_units`
  ADD PRIMARY KEY (`id_subject`);

--
-- Indices de la tabla `teacher_subjects`
--
ALTER TABLE `teacher_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_subject` (`id_subject`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `education_levels`
--
ALTER TABLE `education_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `grades`
--
ALTER TABLE `grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `modalities`
--
ALTER TABLE `modalities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `modality_level`
--
ALTER TABLE `modality_level`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `perfil`
--
ALTER TABLE `perfil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `school_calendar`
--
ALTER TABLE `school_calendar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=752;

--
-- AUTO_INCREMENT de la tabla `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `student_subjects`
--
ALTER TABLE `student_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `subject_modality_level`
--
ALTER TABLE `subject_modality_level`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`id_student_subject`) REFERENCES `student_subjects` (`id`);

--
-- Filtros para la tabla `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`id_student_subject`) REFERENCES `student_subjects` (`id`);

--
-- Filtros para la tabla `modalities`
--
ALTER TABLE `modalities`
  ADD CONSTRAINT `modalities_ibfk_1` FOREIGN KEY (`id_level`) REFERENCES `education_levels` (`id`);

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
-- Filtros para la tabla `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_students_group` FOREIGN KEY (`id_grupo`) REFERENCES `subject_group` (`id_grupo`),
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`id_modality`) REFERENCES `modalities` (`id`);

--
-- Filtros para la tabla `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD CONSTRAINT `student_subjects_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `student_subjects_ibfk_2` FOREIGN KEY (`id_subject`) REFERENCES `subjects` (`id`);

--
-- Filtros para la tabla `subject_group`
--
ALTER TABLE `subject_group`
  ADD CONSTRAINT `subject_group_ibfk_1` FOREIGN KEY (`id_subjects`) REFERENCES `subjects` (`id`),
  ADD CONSTRAINT `subject_group_ibfk_2` FOREIGN KEY (`id_modality_level`) REFERENCES `modality_level` (`id`);

--
-- Filtros para la tabla `subject_modality_level`
--
ALTER TABLE `subject_modality_level`
  ADD CONSTRAINT `subject_modality_level_ibfk_1` FOREIGN KEY (`id_subject`) REFERENCES `subjects` (`id`),
  ADD CONSTRAINT `subject_modality_level_ibfk_2` FOREIGN KEY (`id_modality`) REFERENCES `modalities` (`id`),
  ADD CONSTRAINT `subject_modality_level_ibfk_3` FOREIGN KEY (`id_level`) REFERENCES `education_levels` (`id`);

--
-- Filtros para la tabla `subject_units`
--
ALTER TABLE `subject_units`
  ADD CONSTRAINT `subject_units_ibfk_1` FOREIGN KEY (`id_subject`) REFERENCES `subjects` (`id`);

--
-- Filtros para la tabla `teacher_subjects`
--
ALTER TABLE `teacher_subjects`
  ADD CONSTRAINT `teacher_subjects_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `teacher_subjects_ibfk_2` FOREIGN KEY (`id_subject`) REFERENCES `subjects` (`id`);

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