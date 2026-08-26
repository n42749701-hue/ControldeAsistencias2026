-- DROP DATABASE IF EXISTS DBcontroldeasistencia;
-- CREATE DATABASE DBcontroldeasistencia;
USE defaultdb;

-- 1. TABLA DE DOCENTES (Se crea antes para poder referenciarla)
CREATE TABLE DOCENTES(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    CI VARCHAR(20) NOT NULL UNIQUE,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    telefono VARCHAR(15)
)ENGINE=InnoDB;

-- 2. TABLA DE ESTUDIANTES (Se crea antes para poder referenciarla)
CREATE TABLE ESTUDIANTES(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    CI VARCHAR(20) NOT NULL UNIQUE,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    fecha_nacimiento DATE,
    direccion VARCHAR(250),
    telefono VARCHAR(15)
)ENGINE=InnoDB;

-- 3. TABLA DE USUARIOS CONECTADA
-- Se añaden campos para vincular el usuario con un docente, estudiante o administrador
CREATE TABLE USUARIOS(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('Administrador', 'Docente', 'Estudiante') NOT NULL,
    cod_docente INT NULL,     -- Conexión opcional si el usuario es docente
    cod_estudiante INT NULL,
    FOREIGN KEY(cod_docente) REFERENCES DOCENTES(id) ON DELETE SET NULL,
    FOREIGN KEY(cod_estudiante) REFERENCES ESTUDIANTES(id) ON DELETE SET NULL
)ENGINE=InnoDB;

-- 4. TABLA DE CURSOS
CREATE TABLE CURSOS(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre_curso VARCHAR(50) NOT NULL,
    nivel VARCHAR(30) NOT NULL,
    paralelo VARCHAR(10) NOT NULL
)ENGINE=InnoDB;

-- 5. TABLA DE MATERIAS
CREATE TABLE MATERIAS(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre_materia VARCHAR(100) NOT NULL
)ENGINE=InnoDB;

-- 6. RELACIÓN DOCENTE-MATERIA-CURSO
CREATE TABLE ASIGNACIONES(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    cod_docente INT NOT NULL,
    cod_materia INT NOT NULL,
    cod_curso INT NOT NULL,
    FOREIGN KEY(cod_docente) REFERENCES DOCENTES(id),
    FOREIGN KEY(cod_materia) REFERENCES MATERIAS(id),
    FOREIGN KEY(cod_curso) REFERENCES CURSOS(id)
)ENGINE=InnoDB;

-- 7. INSCRIPCIÓN DE ESTUDIANTES EN CURSOS
CREATE TABLE INSCRIPCIONES(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    cod_estudiante INT NOT NULL,
    cod_curso INT NOT NULL,
    gestion YEAR NOT NULL,
    FOREIGN KEY(cod_estudiante) REFERENCES ESTUDIANTES(id),
    FOREIGN KEY(cod_curso) REFERENCES CURSOS(id)
)ENGINE=InnoDB;

-- 8. REGISTRO DE ASISTENCIA
-- Se añade 'cod_usuario_registro' para saber QUÉ usuario (ej. un docente o admin) tomó la asistencia
CREATE TABLE ASISTENCIAS(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    cod_estudiante INT NOT NULL,
    cod_asignacion INT NOT NULL,
    cod_usuario_registro INT NULL, -- Conexión con la tabla USUARIOS
    fecha DATE NOT NULL,
    estado ENUM('Presente','Ausente','Licencia','Retraso') NOT NULL,
    observacion VARCHAR(250),
    FOREIGN KEY(cod_estudiante) REFERENCES ESTUDIANTES(id),
    FOREIGN KEY(cod_asignacion) REFERENCES ASIGNACIONES(id),
    FOREIGN KEY(cod_usuario_registro) REFERENCES USUARIOS(id)
)ENGINE=InnoDB;
