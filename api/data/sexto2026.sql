	-- Elimina la base de datos si existe
	DROP DATABASE IF EXISTS DBAsistencia;
	-- Creamos la base de datos
	CREATE DATABASE DBAsistencia;
	-- Usamos la base de datos DBSe xto2026
	USE DBAsistencia;
	-- crear tabla de usuario
	CREATE TABLE usuarios(
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	nombreUsuario VARCHAR(100) NOT NULL,
	email VARCHAR(100) UNIQUE NOT NULL,
	password VARCHAR(255) NOT NULL
	)ENGINE=InnoDB;
	-- crea la tabla de imagenes
	CREATE TABLE imagenes(
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	urlImagen VARCHAR(255) NOT NULL,
	usuarioId INT NOT NULL,
	CONSTRAINT fk_user_imagen FOREIGN KEY(usuarioId) REFERENCES usuarios(id)
	)ENGINE=InnoDB;
	
	-- adicionar datos a la tabla usuarios
	INSERT INTO usuarios(nombreUsuario,email,password)
	values('Roberto','Roberto@gmail.com','robert123');
	
	INSERT INTO usuarios(nombreUsuario,email,password)
	values('Maria','maria@gmail.com','maria123');
	
	-- modificar el nombre del usuario
	UPDATE usuarios 
	SET nombreUsuario='Gabriel',
	WHERE id=1;
		-- modificar el correo del usuario
	UPDATE usuarios 
	SET email='gabriel@gmail.com'
	WHERE id=1;
	
	-- actualizar varios registros
	UPDATE usuarios 
	SET nombreUsuario='gabriel@gmail.com',
			email='gabriel@gmail.com',
			`password`='123456'
	WHERE id=2;
	-- adicionar un nuevo usuarios
	INSERT INTO usuarios(nombreUsuario,email,password)
	values('Daniel','daniel@gmail.com','123456');
	-- aliminar un usuarios
	DELETE FROM usuarios 
	WHERE id=2;
	
	--  mostrar los datos de una TABLESPACE
	SELECT * FROM usuarios;
	
	