DROP DATABASE IF EXISTS DBcontrolasistencia;
CREATE DATABASE DBcontrolasistencia;
USE DBcontrolasistencia;
-- creamos la tabla de cliente
CREATE TABLE CLIENTES(
id int not null PRIMARY KEY auto_increment,
CI varchar(20) not NULL,
nombre VARCHAR(50) not NULL, 
apellido varchar(50) not NULL,
direccion varchar(250),
telefono varchar(15)
)ENGINE=InnoDB;
-- crear tabla de empleado
CREATE TABLE EMPLEADOS(
id int not null PRIMARY key auto_increment,
CI VARCHAR(20) not null,
nombre VARCHAR(50) not null,
apellido VARCHAR(50) not null
)ENGINE=InnoDB;
-- crear la tabla pedidos
CREATE TABLE PEDIDOS(
id int not null PRIMARY key auto_increment,
cod_cliente int not null,
fecha_compra datetime not null,
cantidad int not null,
cod_empleado int not null,
FOREIGN KEY(cod_cliente) REFERENCES clientes(id),
FOREIGN KEY(cod_empleado) REFERENCES empleados(id)
)ENGINE=InnoDB;
-- crear tabla del producto
CREATE TABLE PRODUCTOS(
id int not null PRIMARY key auto_increment,
codBarras VARCHAR(100) not null,
descripcion VARCHAR(100) not null,
stock INT not null CHECK(stock>=0),
precio_unitario DECIMAL(10,2) not null
)ENGINE=InnoDB;

-- creamos la tabla relacion pedidoproducto
CREATE TABLE PEDIDO_PRODUCTOS(
id int not null PRIMARY key auto_increment,
cod_producto int not null,
cod_pedido int not null,
cantidad int not null,
precio_unitario DECIMAL(10,2) not null,
descuento DECIMAL(10,2) DEFAULT(0.0),
FOREIGN KEY(cod_producto) REFERENCES productos(id),
FOREIGN KEY(cod_pedido) REFERENCES pedidos(id)
)ENGINE=InnoDB;
-- crear tabla relacional empleado-pedido
CREATE TABLE EMPLEDO_PEDIDOS(
cod_pedido int not null,
cod_empleado int not null,
fecha date not null DEFAULT(NOW()),
PRIMARY KEY(cod_pedido,cod_empleado),
FOREIGN KEY(cod_pedido) REFERENCES pedidos(id),
FOREIGN KEY(cod_empleado) REFERENCES empleados(id)
)ENGINE=InnoDB;