DROP DATABASE IF EXISTS DBcontroldeasistencia;
CREATE DATABASE DBcontroldeasistencia;
USE DBcontroldeasistencia;

-- Tabla de estudiantes
CREATE TABLE ESTUDIANTES(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    CI VARCHAR(20) NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    fecha_nacimiento DATE,
    direccion VARCHAR(250),
    telefono VARCHAR(15)
)ENGINE=InnoDB;

-- Tabla de docentes
CREATE TABLE DOCENTES(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    CI VARCHAR(20) NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    telefono VARCHAR(15)
)ENGINE=InnoDB;

-- Tabla de cursos
CREATE TABLE CURSOS(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre_curso VARCHAR(50) NOT NULL,
    nivel VARCHAR(30) NOT NULL,
    paralelo VARCHAR(10) NOT NULL
)ENGINE=InnoDB;

-- Tabla de materias
CREATE TABLE MATERIAS(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre_materia VARCHAR(100) NOT NULL
)ENGINE=InnoDB;

-- Relación docente-materia-curso
CREATE TABLE ASIGNACIONES(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    cod_docente INT NOT NULL,
    cod_materia INT NOT NULL,
    cod_curso INT NOT NULL,
    FOREIGN KEY(cod_docente) REFERENCES DOCENTES(id),
    FOREIGN KEY(cod_materia) REFERENCES MATERIAS(id),
    FOREIGN KEY(cod_curso) REFERENCES CURSOS(id)
)ENGINE=InnoDB;

-- Inscripción de estudiantes en cursos
CREATE TABLE INSCRIPCIONES(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    cod_estudiante INT NOT NULL,
    cod_curso INT NOT NULL,
    gestion YEAR NOT NULL,
    FOREIGN KEY(cod_estudiante) REFERENCES ESTUDIANTES(id),
    FOREIGN KEY(cod_curso) REFERENCES CURSOS(id)
)ENGINE=InnoDB;

-- Registro de asistencia
CREATE TABLE ASISTENCIAS(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    cod_estudiante INT NOT NULL,
    cod_asignacion INT NOT NULL,
    fecha DATE NOT NULL,
    estado ENUM('Presente','Ausente','Licencia','Retraso') NOT NULL,
    observacion VARCHAR(250),
    FOREIGN KEY(cod_estudiante) REFERENCES ESTUDIANTES(id),
    FOREIGN KEY(cod_asignacion) REFERENCES ASIGNACIONES(id)
)ENGINE=InnoDB;