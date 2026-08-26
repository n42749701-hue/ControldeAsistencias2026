-- 1. INSERTAR DATOS EN DOCENTES
INSERT INTO DOCENTES (CI, nombre, apellido, telefono) VALUES
('1111111', 'Carlos', 'Mendoza', '71020304'),
('2222222', 'Ana', 'Rodriguez', '72030405'),
('3333333', 'Jorge', 'Vaca', '73040506'),
('4444444', 'Elena', 'Gomez', '74050607');

-- 2. INSERTAR DATOS EN ESTUDIANTES
INSERT INTO ESTUDIANTES (CI, nombre, apellido, fecha_nacimiento, direccion, telefono) VALUES
('5555555', 'Luis', 'Perez', '2010-05-12', 'Av. Bush 2do Anillo', '61011122'),
('6666666', 'Maria', 'Suarez', '2011-08-22', 'Calle Beni 3er Anillo', '62022233'),
('7777777', 'Pedro', 'Ortiz', '2010-01-05', 'Urb. Los Tobis', '63033344'),
('8888888', 'Lucia', 'Rojas', '2011-11-30', 'Av. Banzer 4to Anillo', '64044455');

-- 3. INSERTAR DATOS EN USUARIOS
-- Contraseñas de ejemplo en formato hash ficticio
INSERT INTO USUARIOS (username, password_hash, rol, cod_docente, cod_estudiante) VALUES
('admin01', '$2y$10$AdminHash1234567890abcdef', 'Administrador', NULL, NULL),
('carlos.m', '$2y$10$DocenteHash1234567890abc', 'Docente', 1, NULL),
('ana.r', '$2y$10$DocenteHash0987654321xyz', 'Docente', 2, NULL),
('luis.p', '$2y$10$EstudianteHash1122334455', 'Estudiante', NULL, 1);

-- 4. INSERTAR DATOS EN CURSOS
INSERT INTO CURSOS (nombre_curso, nivel, paralelo) VALUES
('Primero de Secundaria', 'Secundaria', 'A'),
('Primero de Secundaria', 'Secundaria', 'B'),
('Segundo de Secundaria', 'Secundaria', 'A'),
('Segundo de Secundaria', 'Secundaria', 'B');

-- 5. INSERTAR DATOS EN MATERIAS
INSERT INTO MATERIAS (nombre_materia) VALUES
('Matemáticas'),
('Lenguaje y Comunicación'),
('Física Elementar'),
('Historia y Geografía');

-- 6. INSERTAR DATOS EN ASIGNACIONES (Docente - Materia - Curso)
INSERT INTO ASIGNACIONES (cod_docente, cod_materia, cod_curso) VALUES
(1, 1, 1), -- Carlos Mendoza dicta Matemáticas en 1ro A
(2, 2, 1), -- Ana Rodriguez dicta Lenguaje en 1ro A
(3, 3, 3), -- Jorge Vaca dicta Física en 2do A
(4, 4, 4); -- Elena Gomez dicta Historia en 2do B

-- 7. INSERTAR DATOS EN INSCRIPCIONES (Estudiante - Curso)
INSERT INTO INSCRIPCIONES (cod_estudiante, cod_curso, gestion) VALUES
(1, 1, 2026), -- Luis Perez inscrito en 1ro A
(2, 1, 2026), -- Maria Suarez inscrita en 1ro A
(3, 3, 2026), -- Pedro Ortiz inscrito en 2do A
(4, 4, 2026); -- Lucia Rojas inscrita en 2do B

-- 8. INSERTAR DATOS EN ASISTENCIAS
-- Las asistencias las registra el usuario del docente (ID 2 = carlos.m o ID 3 = ana.r)
INSERT INTO ASISTENCIAS (cod_estudiante, cod_asignacion, cod_usuario_registro, fecha, estado, observacion) VALUES
(1, 1, 2, '2026-02-23', 'Presente', 'Llegó puntual'),
(2, 1, 2, '2026-02-23', 'Retraso', 'Trancadera en el 3er anillo'),
(3, 3, 3, '2026-02-23', 'Ausente', 'Sin justificativo'),
(4, 4, 1, '2026-02-23', 'Licencia', 'Permiso médico presentado a dirección');

-- SELECT * FROM ASIGNACIONES;
-- SELECT * FROM ASISTENCIAS;
-- SELECT * FROM CURSOS;
-- SELECT * FROM DOCENTES;
-- SELECT * FROM ESTUDIANTES;
-- SELECT * FROM INSCRIPCIONES;
-- SELECT * FROM MATERIAS;
-- SELECT * FROM USUARIOS;