-- 1. INSERTAR DOCENTES
INSERT INTO DOCENTES (nombre, apellido, telefono) VALUES
('Carlos', 'Mendoza', '71234567'),
('Ana', 'Rodriguez', '72345678'),
('Walter', 'Gomez', '73456789');

-- 2. INSERTAR ESTUDIANTES
INSERT INTO ESTUDIANTES (nombre, apellido, direccion, telefono) VALUES
('Luis', 'Flores', 'Av. Las Delicias #123', '61234567'),
('Maria', 'Gomez', 'Calle Bolivar #45', '62345678'),
('Pedro', 'Mamani', 'Zona Central Nro 789', '63456789'),
('Lucia', 'Torres', 'Barrio Lindo Calle 2', '64567890');

-- 3. INSERTAR USUARIOS (Vincular un docente, un estudiante y uno administrativo)
-- password_hash usa textos simulados comunes en pruebas
INSERT INTO USUARIOS (username, password_hash, id_docente, id_estudiante) VALUES
('carlos.m', '$2y$10$xyz123docentemendoza', 1, NULL),      -- Usuario Docente (Carlos)
('luis.f', '$2y$10$abc456estudianteflores', NULL, 1),     -- Usuario Estudiante (Luis)
('admin.sistema', '$2y$10$admin987passwordhash', NULL, NULL);-- Usuario Administrador puro

-- 4. INSERTAR CURSOS
INSERT INTO CURSOS (nombre_curso, nivel, paralelo) VALUES
('Primero de Secundaria', 'Secundaria', 'A'),
('Primero de Secundaria', 'Secundaria', 'B'),
('Segundo de Secundaria', 'Secundaria', 'A');

-- 5. INSERTAR MATERIAS
INSERT INTO MATERIAS (nombre_materia) VALUES
('Matemáticas'),
('Lenguaje y Literatura'),
('Física');

-- 6. RELACIÓN DOCENTE-MATERIA-CURSO (ASIGNACIONES)
INSERT INTO ASIGNACIONES (cod_docente, cod_materia, cod_curso) VALUES
(1, 1, 1), -- Carlos Mendoza enseña Matemáticas en 1ro A
(1, 3, 3), -- Carlos Mendoza enseña Física en 2do A
(2, 2, 1); -- Ana Rodriguez enseña Lenguaje en 1ro A

-- 7. INSCRIPCIÓN DE ESTUDIANTES EN CURSOS (INSCRIPCIONES)
INSERT INTO INSCRIPCIONES (cod_estudiante, cod_curso, gestion) VALUES
(1, 1, 2026), -- Luis Flores inscrito en 1ro A (Gestión 2026)
(2, 1, 2026), -- Maria Gomez inscrita en 1ro A (Gestión 2026)
(3, 2, 2026), -- Pedro Mamani inscrito en 1ro B (Gestión 2026)
(4, 3, 2026); -- Lucia Torres inscrita en 2do A (Gestión 2026)

-- 8. REGISTRO DE ASISTENCIA (ASISTENCIAS)
-- Se simula que el usuario 'admin.sistema' (id: 3) o el docente 'carlos.m' (id: 1) registran la asistencia
INSERT INTO ASISTENCIAS (cod_estudiante, cod_asignacion, cod_usuario_registro, fecha, estado, observacion) VALUES
(1, 1, 1, '2026-03-02', 'Presente', 'Llegó puntual a matemáticas'),
(2, 1, 1, '2026-03-02', 'Retraso', 'Demora por transporte público'),
(1, 3, 1, '2026-03-03', 'Presente', NULL),
(4, 2, 3, '2026-03-03', 'Ausente', 'Falta sin justificar registrada por admin'),
(2, 1, 1, '2026-03-04', 'Licencia', 'Presentó justificativo médico');


 SELECT * FROM ASIGNACIONES;
 SELECT * FROM ASISTENCIAS;
 SELECT * FROM CURSOS;
 SELECT * FROM DOCENTES;
 SELECT * FROM ESTUDIANTES;
 SELECT * FROM INSCRIPCIONES;
 SELECT * FROM MATERIAS;
 SELECT * FROM USUARIOS