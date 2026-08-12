-- ==========================
-- ESTUDIANTES
-- ==========================
INSERT INTO ESTUDIANTES(CI,nombre,apellido,fecha_nacimiento,direccion,telefono)
VALUES
('1234567','Juan','Carlos','2008-05-10','Barrio Centro','70011111'),
('2345678','Maria','Sanchez','2009-03-15','Barrio Norte','70022222'),
('3456789','Carlos','Lorenzo','2008-11-20','Barrio Sur','70033333'),
('4567890','Ana','Maria','2009-07-08','Barrio Este','70044444'),
('5678901','Luis','Ferrera','2008-01-25','Barrio Oeste','70055555');

-- ==========================
-- USUARIOS
-- ==========================
INSERT INTO USUARIOS(ID,nombre,apellido,username,password_hash)
VALUES
('1234568','Juan','Perez','Juan','70011112'),
('2345679','Maria','Gomez','Maria','70022223'),
('3456710','Carlos','Lopez','Carlos','70033334'),
('4567811','Ana','Rojas','Ana','70044445'),
('5678912','Luis','Fernandez','Luis','70055556');

-- ==========================
-- DOCENTES
-- ==========================
INSERT INTO DOCENTES(CI,nombre,apellido,telefono)
VALUES
('1111111','Roberto','Martinez','72111111'),
('2222222','Patricia','Suarez','72222222'),
('3333333','Jorge','Vargas','72333333');

-- ==========================
-- CURSOS
-- ==========================
INSERT INTO CURSOS(nombre_curso,nivel,paralelo)
VALUES
('Primero Secundaria','Secundaria','A'),
('Segundo Secundaria','Secundaria','A'),
('Tercero Secundaria','Secundaria','B');

-- ==========================
-- MATERIAS
-- ==========================
INSERT INTO MATERIAS(nombre_materia)
VALUES
('Matematicas'),
('Lenguaje'),
('Historia'),
('Fisica');

-- ==========================
-- ASIGNACIONES
-- ==========================
INSERT INTO ASIGNACIONES(cod_docente,cod_materia,cod_curso)
VALUES
(1,1,1), -- Roberto - Matematicas - Primero
(2,2,1), -- Patricia - Lenguaje - Primero
(3,3,2), -- Jorge - Historia - Segundo
(1,4,3); -- Roberto - Fisica - Tercero

-- ==========================
-- INSCRIPCIONES
-- ==========================
INSERT INTO INSCRIPCIONES(cod_estudiante,cod_curso,gestion)
VALUES
(1,1,2025),
(2,1,2025),
(3,2,2025),
(4,3,2025),
(5,3,2025);

-- ==========================
-- ASISTENCIAS
-- ==========================
INSERT INTO ASISTENCIAS(cod_estudiante,cod_asignacion,fecha,estado,observacion)
VALUES
(1,1,'2025-03-01','Presente','Sin novedades'),
(2,1,'2025-03-01','Ausente','Falta injustificada'),
(3,3,'2025-03-01','Presente','Sin novedades'),
(4,4,'2025-03-01','Retraso','Llegó 10 minutos tarde'),
(5,4,'2025-03-01','Licencia','Permiso médico');
SELECT * FROM asignaciones;
SELECT * FROM asistencias;
SELECT * FROM cursos;
SELECT * FROM docentes;
SELECT * FROM estudiantes;
SELECT * FROM inscripciones;
SELECT * FROM materias;
SELECT * FROM usuarios;