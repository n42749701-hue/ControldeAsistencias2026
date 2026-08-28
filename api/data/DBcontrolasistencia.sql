-- DROP DATABASE IF EXISTS DBcontrolasistencia;
-- CREATE DATABASE DBcontrolasistencia;
-- USE DBcontrolasistencia;

-- creamos la tabla de cliente
CREATE TABLE CLIENTES(
 id int not null PRIMARY KEY auto_increment,
 ci VARCHAR(20) not NULL,
 nombre VARCHAR(50) not NULL,
 apellidos VARCHAR(50) not NULL,
 direccion VARCHAR(250),
 telefono VARCHAR(15)
)ENGINE=InnoDB;

-- crear tabla de empleado
CREATE TABLE EMPLEADOS(
id int not null PRIMARY key auto_increment,
ci VARCHAR(20) not null,
nombre VARCHAR(50) not null,
apellidos varchar(50) not NULL
)ENGINE=InnoDB;

-- NUEVA: tabla de usuarios vinculada a empleados para el control de acceso
CREATE TABLE USUARIOS(
id int not null PRIMARY KEY auto_increment,
username varchar(50) not null UNIQUE,
password_hash varchar(255) not null,
estado boolean default true,
cod_empleado int not null,
FOREIGN KEY(cod_empleado) REFERENCES EMPLEADOS(id)
)ENGINE=InnoDB;

-- crear tabla del producto (Modificada con control de registro)
CREATE TABLE PRODUCTOS(
id int not null PRIMARY KEY auto_increment,
codBarras varchar(100) not null,
descripcion varchar(100) not NULL,
stock INT not NULL CHECK(stock>=0),
precio_unitario DECIMAL(10,2) not null,
creado_por int, -- Usuario que registró el producto
fecha_registro datetime default now(),
FOREIGN KEY(creado_por) REFERENCES USUARIOS(id)
)ENGINE=InnoDB;

-- crear la tabla pedidos (Modificada con control de registro)
CREATE TABLE PEDIDOS(
id int not null PRIMARY key auto_increment,
cod_cliente int not null,
fecha_compra datetime not null,
cantidad int not null,
cod_empleado int not null,
creado_por int, -- Usuario que registró la venta/pedido
FOREIGN KEY(cod_cliente) REFERENCES CLIENTES(id),
FOREIGN KEY(cod_empleado) REFERENCES EMPLEADOS(id),
FOREIGN KEY(creado_por) REFERENCES USUARIOS(id)
)ENGINE=InnoDB;

-- creamos la tabla relacion pedidoProducto
CREATE TABLE PEDIDO_PRODUCTOS(
Id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
cod_producto int not null,
cod_pedido int not null,
cantidad int not null,
precio_unitario DECIMAL(10,2) not null,
descuento DECIMAL(10,2) DEFAULT(0.0),
FOREIGN KEY(cod_producto) REFERENCES PRODUCTOS(id),
FOREIGN KEY(cod_pedido) REFERENCES PEDIDOS(id)
)ENGINE=InnoDB;

-- crear tabla relacional empleado-pedido
CREATE TABLE EMPLEADO_PEDIDOS(
cod_pedido int not null,
cod_empleado int not null,
fecha date not null DEFAULT(NOW()),
PRIMARY KEY(cod_pedido,cod_empleado),
FOREIGN KEY(cod_pedido) REFERENCES PEDIDOS(id),
FOREIGN KEY(cod_empleado) REFERENCES EMPLEADOS(id)
)ENGINE=InnoDB;
