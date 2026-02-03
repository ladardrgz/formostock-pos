/*
Convenciones de nomenclatura utilizadas en la base de datos:

- Tablas:
  Se nombran con el prefijo "tb_", seguido del nombre de la entidad en minúsculas y en plural.
  Ejemplo: tb_paises, tb_provincias, tb_ciudades.

- Columnas:
  Se utilizan nombres en snake_case, siempre en minúsculas.
  Ejemplo: id_pais, nombre_pais.

- Claves primarias:
  Se definen utilizando el prefijo "id_" seguido del nombre de la entidad.
  Ejemplo: id_pais, id_provincia.

- Claves foráneas:
  Se nombran utilizando el nombre de la entidad referenciada seguido del sufijo "_id".
  Ejemplo: pais_id, provincia_id.

*/

CREATE DATABASE bdformostock;

USE bdformostock;

-- Tabla de países
CREATE TABLE tb_paises (
    idPais INT PRIMARY KEY AUTO_INCREMENT,
    nombrePais VARCHAR(50)
);
INSERT INTO tb_paises (nombrePais) VALUES ('Argentina');

-- Tabla de provincias
CREATE TABLE tb_provincias (
    idProvincia INT PRIMARY KEY AUTO_INCREMENT,
    nombreProvincia VARCHAR(50),
    pais_id INT,
    FOREIGN KEY (pais_id) REFERENCES tb_paises(idPais)
);
INSERT INTO tb_provincias (nombreProvincia, pais_id) VALUES
('Buenos Aires', 1), ('Catamarca', 1), ('Chaco', 1), ('Chubut', 1),
('Ciudad Autónoma de Buenos Aires', 1), ('Córdoba', 1), ('Corrientes', 1), ('Entre Ríos', 1), ('Formosa', 1), ('Jujuy', 1),
('La Pampa', 1), ('La Rioja', 1), ('Mendoza', 1), ('Misiones', 1), ('Neuquén', 1), ('Río Negro', 1), ('Salta', 1), ('San Juan', 1),
('San Luis', 1), ('Santa Cruz', 1), ('Santa Fe', 1), ('Santiago del Estero', 1), ('Tierra del Fuego', 1), ('Tucumán', 1);

-- Tabla de localidades
CREATE TABLE tb_localidades (
    idLocalidad INT PRIMARY KEY AUTO_INCREMENT,
    nombreLocalidad VARCHAR(50),
    provincia_id INT,
    FOREIGN KEY (provincia_id) REFERENCES tb_provincias(idProvincia)
);
INSERT INTO tb_localidades (nombreLocalidad, provincia_id) VALUES
('Formosa', 9), ('Pirané', 9), ('Pozo del Tigre', 9), ('Laishí', 9),
('San Martín II', 9), ('Villa Dos Trece', 9), ('Villafañe', 9), ('Ramón Lista', 9), ('Río Muerto', 9), ('Pilcomayo', 9), ('Gral Belgrano', 9),
('Pilagás', 9), ('Matacos', 9), ('Bermejo', 9), ('Las Lomitas', 9), ('Guemes', 9);

-- Tabla de barrios
CREATE TABLE tb_barrios (
    idBarrio INT PRIMARY KEY AUTO_INCREMENT,
    nombreBarrio VARCHAR(50),
    localidad_id INT,
    FOREIGN KEY (localidad_id) REFERENCES tb_localidades(idLocalidad)
);

INSERT INTO tb_barrios (nombreBarrio, localidad_id) VALUES
  ('1 de Mayo', 1),
  ('2 de Abril', 1),
  ('6 de Enero', 1),
  ('8 de Marzo', 1),
  ('8 de Octubre', 1),
  ('12 de Octubre', 1),
  ('16 de Julio', 1),
  ('25 de Mayo', 1),
  ('Acceso a Curuzú La Novia', 1),
  ('Acceso Tres Marías', 1),
  ('Benedetto Fachini', 1),
  ('Bernardino Rivadavia', 1),
  ('Caracolito', 1),
  ('Carlos Menem Jr.', 1),
  ('COVIFOL', 1),
  ('Don Bosco', 1),
  ('Loteo Don Juan', 1),
  ('El Palmar', 1),
  ('El Palomar', 1),
  ('El Paraíso', 1),
  ('El Porvenir', 1),
  ('El Pucú', 1),
  ('El Quebrachito', 1),
  ('El Quebranto', 1),
  ('El Resguardo', 1),
  ('Emilio Tomás', 1),
  ('Eva Perón', 1),
  ('Fleming', 1),
  ('Fontana', 1),
  ('Frente al Aeropuerto', 1),
  ('Guadalupe', 1),
  ('Ibyrá Pitá', 1),
  ('Illia I', 1),
  ('Itatí I', 1),
  ('Itatí II', 1),
  ('Juan Manuel de Rosas', 1),
  ('Juan Pablo II', 1),
  ('La Alborada', 1),
  ('La Estrella', 1),
  ('La Floresta', 1),
  ('La Palomita', 1),
  ('Laguna Siam', 1),
  ('La Nueva Formosa', 1),
  ('Las Delicias', 1),
  ('Las Orquídeas', 1),
  ('Libertad', 1),
  ('Lisbel Rivira', 1),
  ('Lote 4', 1),
  ('Lote 67', 1),
  ('Lote 110', 1),
  ('Lote 111', 1),
  ('Lote Rural 3 Bis', 1),
  ('Lote Rural 148', 1),
  ('Lote Rural 222', 1),
  ('Los Inmigrantes', 1),
  ('Los Naranjos', 1),
  ('Los Pinos', 1),
  ('Luján', 1),
  ('Malvinas', 1),
  ('Mariano Moreno', 1),
  ('Medalla Milagrosa', 1),
  ('Militar', 1),
  ('Namqom', 1),
  ('Nueva Pompeya', 1),
  ('Obrero', 1),
  ('Parque Urbano', 1),
  ('Parque Urbano I', 1),
  ('Parque Urbano II', 1),
  ('PROCREAR', 1),
  ('Roberto Sotelo', 1),
  ('Sagrado Corazón', 1),
  ('Sagrado Corazón de María', 1),
  ('San Agustín', 1),
  ('San Andrés', 1),
  ('San Andrés II', 1),
  ('San Antonio', 1),
  ('San Antonio I', 1),
  ('San Antonio II', 1),
  ('San Carlos', 1),
  ('San Cayetano', 1),
  ('San Fernando', 1),
  ('San Francisco', 1),
  ('San Isidro Labrador', 1),
  ('San Jorge', 1),
  ('San José', 1),
  ('San José Obrero', 1),
  ('San Juan', 1),
  ('San Juan I', 1),
  ('San Juan II', 1),
  ('San Juan Bautista', 1),
  ('San Lorenzo', 1),
  ('San Martín', 1),
  ('San Martín Norte', 1),
  ('San Martín Sur', 1),
  ('San Miguel', 1),
  ('San Pedro', 1),
  ('San Pío X', 1),
  ('San Roque', 1),
  ('Santa Isabel', 1),
  ('Santa Rosa', 1),
  ('Simón Bolívar', 1),
  ('Solano Lima', 1),
  ('Stella Maris', 1),
  ('Urbanización Maradona', 1),
  ('Venezuela', 1),
  ('Vial', 1),
  ('Villa 49', 1),
  ('Villa del Carmen', 1),
  ('Villa del Carmen 1', 1),
  ('Villa del Carmen 2', 1),
  ('Villa del Rosario', 1),
  ('Villa Hermosa', 1),
  ('Villa Jardín', 1),
  ('Villa Lourdes', 1),
  ('Virgen de Lourdes', 1),
  ('Virgen de Pompeya', 1),
  ('Virgen del Pilar', 1),
  ('Virgen del Rosario', 1);

-- Tabla de domicilios 
CREATE TABLE tb_domicilios (
    idDomicilio INT PRIMARY KEY AUTO_INCREMENT,
    descripcionDomicilio VARCHAR(150),
    barrio_id INT,
    FOREIGN KEY (barrio_id) REFERENCES tb_barrios(idBarrio)
);

-- Tabla de estados lógicos 
CREATE TABLE tb_estados_logicos (
    idEstLog INT PRIMARY KEY AUTO_INCREMENT,
    nombreEstLog VARCHAR(50)
);

-- ============================
-- BLOQUE: GENERALES
-- ============================
INSERT INTO tb_estados_logicos (nombreEstLog) VALUES
('Activo'),
('Inactivo');

-- ============================
-- BLOQUE: FACTURACIÓN / FINANZAS
-- ============================
INSERT INTO tb_estados_logicos (nombreEstLog) VALUES
('Facturado'),
('Pagado'),
('Pagada parcialmente'),
('Pendiente de pago'),
('En cobro'),
('Condonada'),
('Exenta'),
('Atrasada'),
('Aplazada'),
('Próxima a vencer'),
('Vencida');

-- ============================
-- BLOQUE: ÓRDENES / TICKETS / PROCESOS
-- ============================
INSERT INTO tb_estados_logicos (nombreEstLog) VALUES
('Pendiente'),
('En proceso'),
('Procesando'),
('En evaluación'),
('En espera de revisión'),
('Pendiente de revisión'),
('Verificado'),
('No verificado'),
('Revisado'),
('Aceptado'),
('Rechazado'),
('Completado'),
('Anulada'),
('Cancelado'),
('En litigio');

-- ============================
-- BLOQUE: INVENTARIO / DISPONIBILIDAD
-- ============================
INSERT INTO tb_estados_logicos (nombreEstLog) VALUES
('Disponible'),
('No disponible'),
('Agotado'),
('En oferta'),
('En espera de reposición'),
('Descatalogado'),
('Suspendido'),
('Bloqueado');

-- ============================
-- BLOQUE: ENVÍOS / LOGÍSTICA
-- ============================
INSERT INTO tb_estados_logicos (nombreEstLog) VALUES
('Enviado'),
('Entregado');

-- ============================
-- BLOQUE: DEVOLUCIONES
-- ============================
INSERT INTO tb_estados_logicos (nombreEstLog) VALUES
('Devuelto');

-- ============================
-- BLOQUE: CAJA
-- ============================
INSERT INTO tb_estados_logicos (nombreEstLog) VALUES
('Caja abierta'),
('Caja cerrada');

-- Tabla tipos de documentos 
CREATE TABLE tb_tipo_documentos (
    idTipoDocumento INT PRIMARY KEY AUTO_INCREMENT,
    nombreTipoDoc VARCHAR(100)
);

INSERT INTO tb_tipo_documentos (nombreTipoDoc) VALUES
('DNI'), ('CDI'), ('CUIT'), ('CUIL'), ('DNIe'), ('LC');

-- Tabla detalle de documento 
CREATE TABLE tb_detalle_documento (
    idDetalleDocumento INT PRIMARY KEY AUTO_INCREMENT,
    tipo_documento_id INT,
    valorDocumento VARCHAR(100),
    FOREIGN KEY (tipo_documento_id) REFERENCES tb_tipo_documentos(idTipoDocumento)
);

-- Tabla tipo de contacto 
CREATE TABLE tb_tipo_contacto (
    idTipoContacto INT PRIMARY KEY AUTO_INCREMENT,
    nombreTipoContacto VARCHAR(100)
);

INSERT INTO tb_tipo_contacto (nombreTipoContacto) VALUES
('Número de teléfono'),
('Correo electrónico');

-- Tabla detalle de contacto 
CREATE TABLE tb_detalle_contacto (
    idDetalleContacto INT PRIMARY KEY AUTO_INCREMENT,
    valorDetalleContacto VARCHAR(150),
    tipo_contacto_id INT,
    FOREIGN KEY (tipo_contacto_id) REFERENCES tb_tipo_contacto(idTipoContacto)
);

-- Tabla personas físicas 
CREATE TABLE tb_personas_fisicas (
    idPersonaFisica INT PRIMARY KEY AUTO_INCREMENT,
    nombres VARCHAR(50),
    apellidos VARCHAR(50),
    fechaNacimiento DATE,
    sexo VARCHAR(50),
    detalle_documento_id INT,
    detalle_contacto_id INT,
    estado_persona_id INT,
    FOREIGN KEY (detalle_documento_id) REFERENCES tb_detalle_documento(idDetalleDocumento),
    FOREIGN KEY (detalle_contacto_id) REFERENCES tb_detalle_contacto(idDetalleContacto),
    FOREIGN KEY (estado_persona_id) REFERENCES tb_estados_logicos(idEstLog)
);

-- Esta tabla se encarga de almacenar la información relacionada con los domicilios de una persona
CREATE TABLE tb_domicilios_personas (
    idDomicilioPersona INT PRIMARY KEY AUTO_INCREMENT,
    valorDomicilio VARCHAR(150),
    persona_fisica_id INT,
    domicilio_id INT,
    FOREIGN KEY (persona_fisica_id) REFERENCES tb_personas_fisicas(idPersonaFisica),
    FOREIGN KEY (domicilio_id) REFERENCES tb_domicilios(idDomicilio)
);

-- Tabla de permisos
CREATE TABLE tb_permisos (
    idPermiso INT PRIMARY KEY AUTO_INCREMENT,
    descripcionPermiso VARCHAR(100),
    activoPermiso TINYINT(1) DEFAULT 1
);

INSERT INTO tb_permisos (descripcionPermiso) VALUES
('Usuarios'), ('Clientes'), ('Productos'), ('Caja'), ('Ventas'),
('Proveedores'), ('Sucursales'), ('Configuración');

-- Tabla de roles 
CREATE TABLE tb_roles (
    idRol INT PRIMARY KEY AUTO_INCREMENT,
    nombreRol VARCHAR(30),
    activoRol TINYINT(1) DEFAULT 1
);

INSERT INTO tb_roles (nombreRol) VALUES
('Administrador'), ('Propietario'), ('Cajero');

-- Tabla puente roles-permisos 
CREATE TABLE tb_rolespermisos (
    idRolPermiso INT PRIMARY KEY AUTO_INCREMENT,
    rol_id INT,
    permiso_id INT,
    FOREIGN KEY (rol_id) REFERENCES tb_roles(idRol) ON DELETE CASCADE,
    FOREIGN KEY (permiso_id) REFERENCES tb_permisos(idPermiso) ON DELETE CASCADE
);

INSERT INTO tb_rolespermisos (rol_id, permiso_id) VALUES
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6), (1, 7), (1, 8); -- Administrador

INSERT INTO tb_rolespermisos (rol_id, permiso_id) VALUES
(2, 1), (2, 2), (2, 3), (2, 4), (2, 5), (2, 6), (2, 7), (2, 8); -- Propietario

INSERT INTO tb_rolespermisos (rol_id, permiso_id) VALUES
(3, 1), (3, 2), (3, 3), (3, 4), (3, 5); -- Cajero

-- Esta tabla está diseñada para almacenar información sobre los usuarios autorizados para gestionar el sistema
CREATE TABLE tb_usuarios (
    idUsuario INT PRIMARY KEY AUTO_INCREMENT,
    nombreCuentaUsuario VARCHAR(30),
    contraseñaUsuario VARCHAR(32),
    emailUsuario VARCHAR(150),
    contraseñaTemporal VARCHAR(32) DEFAULT NULL,
    expiracionContraseñaTemporal DATETIME DEFAULT NULL,
    estado_usuario_id INT,
    persona_fisica_id INT,
    rol_id INT,
    FOREIGN KEY (estado_usuario_id) REFERENCES tb_estados_logicos(idEstLog),
    FOREIGN KEY (persona_fisica_id) REFERENCES tb_personas_fisicas(idPersonaFisica),
    FOREIGN KEY (rol_id) REFERENCES tb_roles(idRol)
);

-- Tabla sesiones del sistema
CREATE TABLE tb_sesiones (
    idSesion INT PRIMARY KEY AUTO_INCREMENT,
    idUsuario INT NOT NULL,
    activo TINYINT(1) DEFAULT 1,
    fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_expiracion DATETIME NULL,
    FOREIGN KEY (idUsuario) REFERENCES tb_usuarios(idUsuario)
);

-- Esta tabla está diseñada para gestionar la información de los clientes
CREATE TABLE tb_clientes (
    idCliente INT PRIMARY KEY AUTO_INCREMENT,
    persona_fisica_id INT,
    FOREIGN KEY (persona_fisica_id) REFERENCES tb_personas_fisicas(idPersonaFisica)
);

-- Tabla de gestión de personas jurídicas cómo proveedores
CREATE TABLE tb_personas_juridicas (
    idPersonaJuridica INT PRIMARY KEY AUTO_INCREMENT,
    razonSocial VARCHAR(100),
    persona_fisica_id INT,
    estado_persona_juridica_id INT,
    FOREIGN KEY (persona_fisica_id) REFERENCES tb_personas_fisicas(idPersonaFisica),
    FOREIGN KEY (estado_persona_juridica_id) REFERENCES tb_estados_logicos(idEstLog)
);

-- Esta tabla está diseñada para organizar y clasificar los productos en diferentes categorías
CREATE TABLE tb_categorias_productos (
    idCategoriaProducto INT PRIMARY KEY AUTO_INCREMENT,
    nombreCategoriaProducto VARCHAR(100)
);

INSERT INTO tb_categorias_productos (nombreCategoriaProducto) VALUES
-- Categorías base
('Computadoras, notebooks y tablets'),
('Accesorios informáticos'),
('Componentes de hardware'),
('Almacenamiento y redes'),
('Impresoras, escáneres y consumibles'),
('Energía y respaldo'),
('Audio y multimedia'),
('Seguridad informática'),
('Software y aplicaciones'),
('Gaming y accesorios'),

-- Categorías adicionales
('Monitores y pantallas'),
('Periféricos'),
('Cables y conectividad'),
('Servidores y data center'),
('Redes empresariales'),
('Telefonía y comunicación'),
('Domótica y hogar inteligente'),
('Streaming y creación de contenido'),
('Oficina y ergonomía'),
('Realidad virtual y aumentada'),
('Educación y robótica'),
('Componentes electrónicos'),
('Repuestos y mantenimiento'),
('Almacenamiento externo'),
('Almacenamiento interno'),
('Refrigeración y ventilación'),
('Motherboards y chipsets'),
('Fuentes y gabinetes'),
('Iluminación RGB'),
('Consolas y videojuegos'),
('Accesorios para consolas'),
('Electrónica de consumo'),
('Wearables'),
('Cámaras y fotografía'),
('Video vigilancia'),
('Licencias y suscripciones');

-- Tabla de marcas de productos 
CREATE TABLE tb_marcas_productos (
    idMarcaProducto INT PRIMARY KEY AUTO_INCREMENT,
    nombreMarcaProducto VARCHAR(100)
);
INSERT INTO tb_marcas_productos (nombreMarcaProducto) VALUES
-- Marcas base
('Apple'),
('HP'),
('Dell'),
('ASUS'),
('Lenovo'),
('Intel'),
('AMD'),
('NVIDIA'),
('Microsoft'),
('Samsung'),
('Logitech'),
('Corsair'),
('Kingston'),
('Seagate'),
('Western Digital'),
('MSI'),
('Gigabyte'),
('Razer'),
('Alienware'),
('Canon'),
('Epson'),
('Xiaomi'),
('Huawei'),
('Netgear'),
('TP-Link'),
('Bose'),
('Banghó'),
('EXO'),

-- Hardware y componentes
('ASRock'),
('ZOTAC'),
('EVGA'),
('Thermaltake'),
('Cooler Master'),
('NZXT'),
('Crucial'),
('ADATA'),
('Patriot'),
('PNY'),

-- Periféricos y gaming
('HyperX'),
('SteelSeries'),
('Redragon'),
('Acer'),
('BenQ'),
('ViewSonic'),
('AOC'),

-- Almacenamiento y redes
('Synology'),
('QNAP'),
('Ubiquiti'),
('D-Link'),
('Tenda'),

-- Audio, video y multimedia
('Sony'),
('JBL'),
('Sennheiser'),
('Beats'),
('Marshall'),

-- Impresión y oficina
('Brother'),
('Lexmark'),
('Ricoh'),
('Kyocera'),

-- Electrónica y consumo
('LG'),
('Philips'),
('Panasonic'),
('Sharp'),

-- Consolas y entretenimiento
('Nintendo'),
('PlayStation'),
('Xbox'),

-- Accesorios y varios
('Anker'),
('Belkin'),
('Baseus'),
('Aukey'),
('LogiTech G');

-- Tabla tipos de impuestos
CREATE TABLE tb_tipo_impuestos (
    idTipoImpuesto INT PRIMARY KEY AUTO_INCREMENT,
    nombreImpuesto VARCHAR(50)
);

-- Tabla detalles de impuestos
CREATE TABLE tb_detalle_impuestos (
    idDetalleImpuesto INT PRIMARY KEY AUTO_INCREMENT,
    valorDetalleImpuesto DECIMAL(5,2),
    tipo_impuesto_id INT,
    FOREIGN KEY (tipo_impuesto_id) REFERENCES tb_tipo_impuestos(idTipoImpuesto)
);

-- Esta tabla se encarga de almacenar la información de proveedores (script viejo)
CREATE TABLE tb_proveedores (
    idProveedor INT PRIMARY KEY AUTO_INCREMENT,
    persona_juridica_id INT,
    FOREIGN KEY (persona_juridica_id) REFERENCES tb_personas_juridicas(idPersonaJuridica)
);
-- Esta tabla se encarga de almacenar información detallada sobre los productos en el inventario. (script viejo)
CREATE TABLE tb_productos (
    idProducto INT PRIMARY KEY AUTO_INCREMENT,
    codigoBarrasProducto VARCHAR(50),
    numeroDeSerieProducto VARCHAR(50),
    descripcionProducto VARCHAR(150),
    precioProducto DECIMAL(20,2),
    stockMinProducto INT,
    stockMaxProducto INT,
    garantiaProducto VARCHAR(30),
    imagenProducto VARCHAR(255),
    impuesto_id INT,
    categoria_id INT,
    marca_id INT,
    estado_producto_id INT,
    proveedor_id INT,
    FOREIGN KEY (impuesto_id) REFERENCES tb_detalle_impuestos(idDetalleImpuesto),
    FOREIGN KEY (categoria_id) REFERENCES tb_categorias_productos(idCategoriaProducto),
    FOREIGN KEY (marca_id) REFERENCES tb_marcas_productos(idMarcaProducto),
    FOREIGN KEY (estado_producto_id) REFERENCES tb_estados_logicos(idEstLog),
    FOREIGN KEY (proveedor_id) REFERENCES tb_proveedores(idProveedor)
);

-- Esta tabla almacena la información general sobre las órdenes de compra 
CREATE TABLE tb_ordenes_compra (
    idOrdenCompra INT PRIMARY KEY AUTO_INCREMENT,
    fechaOrden DATE,
    proveedor_id INT,
    totalOrdenCompra DECIMAL(12,2),
    estado_orden_id INT,
    FOREIGN KEY (proveedor_id) REFERENCES tb_proveedores(idProveedor),
    FOREIGN KEY (estado_orden_id) REFERENCES tb_estados_logicos(idEstLog)
);

-- Esta tabla almacena los detalles de los productos incluidos en cada orden de compra 
CREATE TABLE tb_detalle_orden_compra (
    idDetalleOrdenCompra INT PRIMARY KEY AUTO_INCREMENT,
    orden_compra_id INT,
    producto_id INT,
    cantidadProducto INT,
    precioProducto DECIMAL(12,2),
    subTotalProducto DECIMAL(12,2),
    FOREIGN KEY (orden_compra_id) REFERENCES tb_ordenes_compra(idOrdenCompra),
    FOREIGN KEY (producto_id) REFERENCES tb_productos(idProducto)
);

-- Tabla de sucursales 
CREATE TABLE tb_sucursal (
    idSucursal INT PRIMARY KEY AUTO_INCREMENT,
    nombreSucursal VARCHAR(50),
    persona_juridica_id INT,
    FOREIGN KEY (persona_juridica_id) REFERENCES tb_personas_juridicas(idPersonaJuridica)
);

-- Tabla de inventario por sucursal 
CREATE TABLE tb_inventario_sucursal (
    idInventarioSucursal INT PRIMARY KEY AUTO_INCREMENT,
    sucursal_id INT,
    producto_id INT,
    stockSucursal INT,
    FOREIGN KEY (sucursal_id) REFERENCES tb_sucursal(idSucursal),
    FOREIGN KEY (producto_id) REFERENCES tb_productos(idProducto)
);

-- Tabla de caja 
CREATE TABLE tb_caja (
    idCaja INT PRIMARY KEY AUTO_INCREMENT,
    nombreCaja VARCHAR(50),
    saldoInicialCaja DECIMAL(14,2),
    saldoActualCaja DECIMAL(14,2),
    fechaAperturaCaja DATETIME,
    fechaCierreCaja DATETIME,
    estado_caja_id INT,
    montoArqueoCaja DECIMAL(20,4),
    FOREIGN KEY (estado_caja_id) REFERENCES tb_estados_logicos(idEstLog)
);

-- Tabla de formas de pago 
CREATE TABLE tb_formas_pago (
    idFormaPago INT PRIMARY KEY AUTO_INCREMENT,
    nombreFormaPago VARCHAR(30),
    comisionFormaPago INT
);

-- Tabla de transacciones de pago en caja 
CREATE TABLE tb_transacciones_pago_caja (
    idTransaccionPago INT PRIMARY KEY AUTO_INCREMENT,
    caja_id INT,
    forma_pago_id INT,
    montoTransaccionPago DECIMAL(14,2),
    fechaTransaccionPago DATE,
    FOREIGN KEY (caja_id) REFERENCES tb_caja(idCaja),
    FOREIGN KEY (forma_pago_id) REFERENCES tb_formas_pago(idFormaPago)
);

-- Tabla de periodos 
CREATE TABLE tb_periodos (
    idPeriodo INT PRIMARY KEY AUTO_INCREMENT,
    nombrePeriodo VARCHAR(100),
    fechaInicioPeriodo DATE,
    fechaFinPeriodo DATE,
    añoPeriodo INT,
    estado_periodo_id INT,
    FOREIGN KEY (estado_periodo_id) REFERENCES tb_estados_logicos(idEstLog)
);

-- Tabla de factura cabecera 
CREATE TABLE tb_factura_cabecera (
    idFaCab INT PRIMARY KEY AUTO_INCREMENT,
    cantidadTotalFaCab INT,
    fechaDeEmisionFaCab DATETIME,
    fechaDeVencimientoFaCab DATE,
    montoTotalFaCab DECIMAL(12,2),
    cliente_id INT,
    caja_id INT,
    sucursal_id INT,
    forma_pago_id INT,
    periodo_id INT,
    estado_factura_id INT,
    FOREIGN KEY (cliente_id) REFERENCES tb_clientes(idCliente),
    FOREIGN KEY (caja_id) REFERENCES tb_caja(idCaja),
    FOREIGN KEY (sucursal_id) REFERENCES tb_sucursal(idSucursal),
    FOREIGN KEY (forma_pago_id) REFERENCES tb_formas_pago(idFormaPago),
    FOREIGN KEY (periodo_id) REFERENCES tb_periodos(idPeriodo),
    FOREIGN KEY (estado_factura_id) REFERENCES tb_estados_logicos(idEstLog)
);

-- Tabla de factura detalle 
CREATE TABLE tb_factura_detalle (
    idFaDet INT PRIMARY KEY AUTO_INCREMENT,
    factura_cabecera_id INT,
    producto_id INT,
    cantidadProductoFaDet INT,
    subTotalFaDet DECIMAL(12,2),
    FOREIGN KEY (factura_cabecera_id) REFERENCES tb_factura_cabecera(idFaCab),
    FOREIGN KEY (producto_id) REFERENCES tb_productos(idProducto)
);

-- Tabla de periodo productos 
CREATE TABLE tb_periodo_productos (
    idPeriodoProducto INT PRIMARY KEY AUTO_INCREMENT,
    periodo_id INT,
    factura_detalle_id INT,
    cantidadVendidaPeriodoProducto INT,
    FOREIGN KEY (periodo_id) REFERENCES tb_periodos(idPeriodo),
    FOREIGN KEY (factura_detalle_id) REFERENCES tb_factura_detalle(idFaDet)
);

-- Tabla historial ventas clientes
CREATE TABLE tb_historial_ventas_clientes (
    idCompra INT PRIMARY KEY AUTO_INCREMENT,
    cliente_id INT,
    factura_cabecera_id INT,
    fechaCompra DATE,
    FOREIGN KEY (cliente_id) REFERENCES tb_clientes(idCliente),
    FOREIGN KEY (factura_cabecera_id) REFERENCES tb_factura_cabecera(idFaCab)
);

-- Tabla tipos de notas 
CREATE TABLE tb_tipos_notas (
    idTipoNota INT PRIMARY KEY AUTO_INCREMENT,
    tipoNota VARCHAR(50)
);
-- Tabla de devoluciones 
CREATE TABLE tb_devoluciones (
    idDevolucion INT PRIMARY KEY AUTO_INCREMENT,
    factura_cabecera_id INT,
    factura_detalle_id INT,
    tipoDevolucion VARCHAR(50),
    fechaDevolucion DATE,
    cantidadDevolucion INT,
    motivoDevolucion VARCHAR(150),
    condicionesDeEntrega VARCHAR(150),
    tipo_nota_id INT,
    FOREIGN KEY (factura_cabecera_id) REFERENCES tb_factura_cabecera(idFaCab),
    FOREIGN KEY (factura_detalle_id) REFERENCES tb_factura_detalle(idFaDet),
    FOREIGN KEY (tipo_nota_id) REFERENCES tb_tipos_notas(idTipoNota)
);

-- Tabla de notas de personas 
CREATE TABLE tb_notas_personas (
    idNota INT PRIMARY KEY AUTO_INCREMENT,
    fechaEmisionNota DATE,
    montoNota DECIMAL(14,2),
    cliente_id INT,
    tipo_nota_id INT,
    devolucion_id INT,
    estado_nota_id INT,
    FOREIGN KEY (cliente_id) REFERENCES tb_clientes(idCliente),
    FOREIGN KEY (tipo_nota_id) REFERENCES tb_tipos_notas(idTipoNota),
    FOREIGN KEY (devolucion_id) REFERENCES tb_devoluciones(idDevolucion),
    FOREIGN KEY (estado_nota_id) REFERENCES tb_estados_logicos(idEstLog)
);

-- Tabla de auditoría de tablas 
CREATE TABLE tb_auditoria_tablas (
    idAuditoria INT PRIMARY KEY AUTO_INCREMENT,
    tablaAfectada VARCHAR(150),
    accion_realizada VARCHAR(10),
    registro_afectado INT,
    valorAnterior VARCHAR(50),
    valorNuevo VARCHAR(50),
    usuario_responsable INT,
    fechaRegistro DATETIME,
    ipUsuario VARCHAR(50),
    detalleAdicional VARCHAR(50),
    moduloAfectado VARCHAR(50),
    FOREIGN KEY (usuario_responsable) REFERENCES tb_usuarios(idUsuario)
);

-- Inserción de documentos para personas físicas y usuarios (35)

INSERT INTO tb_detalle_documento (tipo_documento_id, valorDocumento) VALUES
(1, '44460144'),  -- Lada Rodriguez
(1, '44123456'),  -- Miguel García
(1, '52234567'),  -- María Gaona
(1, '53456789'),  -- Fernando Gomez
(1, '41678901'),  -- Micaela Gimenez
(1, '40789012'),  -- Marcos Rodríguez
(1, '57890123'),  -- Ana Martínez
(1, '50901234'),  -- Luis Pérez
(1, '40012345'),  -- Laura López
(1, '59023456'),  -- José Sánchez
(1, '49034567'),  -- Lucía Fernández
(1, '38045678'),  -- Javier Ramírez
(1, '52056789'),  -- Sofía Torres
(1, '54067890'),  -- David Hernández
(1, '49078901'),  -- Camila Ruiz
(1, '44089012'),  -- Andrés González
(1, '42090123'),  -- Isabel Mendoza
(1, '59091234'),  -- Juan Vega
(1, '46092345'),  -- Clara Cabrera
(1, '41093456'),  -- Pedro Castro
(1, '44350291'),  -- Elena Ortiz
(1, '55123456'),  -- Tomás López
(1, '56234567'),  -- Paula García
(1, '57345678'),  -- Emiliano Díaz
(1, '58456789'),  -- Valeria Gómez
(1, '59567890'),  -- Juan Pablo Fernández
(1, '50678901'),  -- Gabriela Castro
(1, '51789012'),  -- Alejandro Ruiz
(1, '52890123'),  -- Laura Martínez
(1, '53901234'),  -- Federico Ramírez
(1, '55012345'),  -- Lorena González
(1, '56123456'),  -- Javier López
(1, '57234567'),  -- Marcela Torres
(1, '58345678'),  -- Martín Silva
(1, '59456789');  -- Natalia López

INSERT INTO tb_personas_fisicas (nombres, apellidos, fechaNacimiento, sexo, detalle_documento_id, detalle_contacto_id, estado_persona_id) VALUES
('Lada', 'Rodriguez', '2002-11-30', 'Femenino', 1, NULL, 1),
('Miguel', 'García', '1990-05-15', 'Masculino', 2, NULL, 1),
('María', 'Gaona', '1990-05-15', 'Femenino', 3, NULL, 1),
('Fernando', 'Gomez', '1990-05-15', 'Masculino', 4, NULL, 1),
('Micaela', 'Gimenez', '1990-05-15', 'Femenino', 5, NULL, 1),
('Marcos', 'Rodríguez', '1985-07-20', 'Masculino', 6, NULL, 1),
('Ana', 'Martínez', '1992-08-30', 'Femenino', 7, NULL, 1),
('Luis', 'Pérez', '1978-02-10', 'Masculino', 8, NULL, 1),
('Laura', 'López', '1989-11-25', 'Femenino', 9, NULL, 1),
('José', 'Sánchez', '1983-04-05', 'Masculino', 10, NULL, 1),
('Lucía', 'Fernández', '1991-12-12', 'Femenino', 11, NULL, 1),
('Javier', 'Ramírez', '1975-06-15', 'Masculino', 12, NULL, 1),
('Sofía', 'Torres', '1988-03-22', 'Femenino', 13, NULL, 1),
('David', 'Hernández', '1993-09-17', 'Masculino', 14, NULL, 1),
('Camila', 'Ruiz', '1995-01-11', 'Femenino', 15, NULL, 1),
('Andrés', 'González', '1982-10-30', 'Masculino', 16, NULL, 1),
('Isabel', 'Mendoza', '1996-04-04', 'Femenino', 17, NULL, 1),
('Juan', 'Vega', '1979-05-23', 'Masculino', 18, NULL, 1),
('Clara', 'Cabrera', '1994-07-07', 'Femenino', 19, NULL, 1),
('Pedro', 'Castro', '1980-01-18', 'Masculino', 20, NULL, 1),
('Elena', 'Ortiz', '1987-09-29', 'Femenino', 21, NULL, 1),
('Tomás', 'López', '1990-11-10', 'Masculino', 22, NULL, 1),
('Paula', 'García', '1985-06-25', 'Femenino', 23, NULL, 1),
('Emiliano', 'Díaz', '1992-03-15', 'Masculino', 24, NULL, 1),
('Valeria', 'Gómez', '1988-12-04', 'Femenino', 25, NULL, 1),
('Juan Pablo', 'Fernández', '1993-07-18', 'Masculino', 26, NULL, 1),
('Gabriela', 'Castro', '1991-08-23', 'Femenino', 27, NULL, 1),
('Alejandro', 'Ruiz', '1987-10-12', 'Masculino', 28, NULL, 1),
('Laura', 'Martínez', '1984-05-30', 'Femenino', 29, NULL, 1),
('Federico', 'Ramírez', '1995-11-14', 'Masculino', 30, NULL, 1),
('Lorena', 'González', '1986-02-19', 'Femenino', 31, NULL, 1),
('Javier', 'López', '1992-09-25', 'Masculino', 32, NULL, 1),
('Marcela', 'Torres', '1989-04-08', 'Femenino', 33, NULL, 1),
('Martín', 'Silva', '1993-01-21', 'Masculino', 34, NULL, 1),
('Natalia', 'López', '1988-06-30', 'Femenino', 35, NULL, 1);

INSERT INTO tb_usuarios (nombreCuentaUsuario, contraseñaUsuario, emailUsuario, estado_usuario_id, persona_fisica_id, rol_id) VALUES
('admin', md5('admin'), 'ladardrgz@gmail.com', 1, 1, 1),
('juan_perez', md5('juan123'), 'juan.perez@example.com', 1, 2, 3),
('maria_gonzalez', md5('maria_pass'), 'maria.gonzalez@example.com', 1, 3, 3),
('carlos_fernandez', md5('carlos_pwd'), 'carlos.fernandez@example.com', 1, 4, 3),
('valentina_rodriguez', md5('vale456'), 'valentina.rodriguez@example.com', 1, 5, 3),
('marcos_rdz', md5('marcos321'), 'marcos.rdz@example.com', 1, 6, 3),
('sofia_martinez', md5('sofia101'), 'sofia.martinez@example.com', 1, 7, 3),
('luis_perez', md5('luis1234'), 'luis.perez@example.com', 1, 8, 3),
('camila_ramirez', md5('camila103'), 'camila.ramirez@example.com', 1, 9, 3),
('jose_sanchez', md5('jose5678'), 'jose.sanchez@example.com', 1, 10, 3),
('florencia_torres', md5('florencia105'), 'florencia.torres@example.com', 1, 11, 3),
('pablo_gomez', md5('pablo106'), 'pablo.gomez@example.com', 1, 12, 3),
('julieta_dominguez', md5('julieta107'), 'julieta.dominguez@example.com', 1, 13, 3),
('nicolas_morales', md5('nicolas108'), 'nicolas.morales@example.com', 1, 14, 3),
('agustina_silva', md5('agustina109'), 'agustina.silva@example.com', 1, 15, 3),
('sebastian_ortiz', md5('sebastian110'), 'sebastian.ortiz@example.com', 1, 16, 3),
('paula_mendoza', md5('paula111'), 'paula.mendoza@example.com', 1, 17, 3),
('martina_jimenez', md5('martina112'), 'martina.jimenez@example.com', 1, 18, 3),
('emilio_rojas', md5('emilio113'), 'emilio.rojas@example.com', 1, 19, 3),
('rocio_paredes', md5('rocio114'), 'rocio.paredes@example.com', 1, 20, 3),
('alvaro_escobar', md5('alvaro115'), 'alvaro.escobar@example.com', 1, 21, 3),
('paola_garzon', md5('paola116'), 'paola.garzon@example.com', 1, 22, 3),
('jorge_vega', md5('jorge117'), 'jorge.vega@example.com', 1, 23, 3),
('beatriz_murillo', md5('beatriz118'), 'beatriz.murillo@example.com', 1, 24, 3),
('oscar_nieto', md5('oscar119'), 'oscar.nieto@example.com', 1, 25, 3),
('laura_araujo', md5('laura120'), 'laura.araujo@example.com', 1, 26, 3),
('gustavo_ortega', md5('gustavo121'), 'gustavo.ortega@example.com', 1, 27, 3),
('carla_martinez', md5('carla122'), 'carla.martinez@example.com', 1, 28, 3),
('daniel_rios', md5('daniel123'), 'daniel.rios@example.com', 1, 29, 2),
('veronica_mora', md5('veronica124'), 'veronica.mora@example.com', 1, 30, 2),
('tomas_lopez', md5('tomas91011'), 'tomas.lopez@example.com', 1, 31, 2),  
('javier_lopez', md5('javier321'), 'javier.lopez@example.com', 1, 32, 2), 
('marcela_torres', md5('marcela321'), 'marcela.torres@example.com', 1, 33, 2),  
('martin_silva', md5('martin321'), 'martin.silva@example.com', 1, 34, 2),  
('natalia_lopez', md5('natalia321'), 'natalia.lopez@example.com', 1, 35, 2);  

-- Inserción de documentos para personas físicas y clientes con número de telefono (10) (+10=46)

-- Insertar detalle de documentos de personas del sistema
INSERT INTO tb_detalle_documento (tipo_documento_id, valorDocumento) VALUES
(1, '78123456'),  -- Gabriela Fernández
(1, '78901234'),  -- Esteban Morales
(1, '79012345'),  -- Lorena Rodríguez
(1, '79876543'),  -- Manuel Fernández
(1, '80345678'),  -- Patricia González
(1, '81098765'),  -- Arturo Pérez
(1, '82123456'),  -- Carolina Ríos
(1, '83567890'),  -- Ricardo López
(1, '84678901'),  -- Teresa Martínez
(1, '85901234');  -- Andrés López

-- Insertar nuevas personas físicas
INSERT INTO tb_personas_fisicas (nombres, apellidos, fechaNacimiento, sexo, detalle_documento_id, detalle_contacto_id, estado_persona_id) VALUES
('Gabriela', 'Fernández', '1981-02-15', 'Femenino', 36, NULL, 1),
('Esteban', 'Morales', '1978-03-20', 'Masculino', 37, NULL, 1),
('Lorena', 'Rodríguez', '1989-04-10', 'Femenino', 38, NULL, 1),
('Manuel', 'Fernández', '1992-05-25', 'Masculino', 39, NULL, 1),
('Patricia', 'González', '1986-06-30', 'Femenino', 40, NULL, 1),
('Arturo', 'Pérez', '1984-07-15', 'Masculino', 41, NULL, 1),
('Carolina', 'Ríos', '1990-08-20', 'Femenino', 42, NULL, 1),
('Ricardo', 'López', '1983-09-10', 'Masculino', 43, NULL, 1),
('Teresa', 'Martínez', '1985-10-05', 'Femenino', 44, NULL, 1),
('Andrés', 'López', '1991-11-12', 'Masculino', 45, NULL, 1);

-- Insertar clientes con los IDs disponibles
INSERT INTO tb_clientes (persona_fisica_id) VALUES
(36), -- Gabriela Fernández
(37), -- Esteban Morales
(38), -- Lorena Rodríguez
(39), -- Manuel Fernández
(40), -- Patricia González
(41), -- Arturo Pérez
(42), -- Carolina Ríos
(43), -- Ricardo López
(44), -- Teresa Martínez
(45); -- Andrés López

-- Insertar números de teléfono ficticios en tb_detalle_contacto
INSERT INTO tb_detalle_contacto (valorDetalleContacto, tipo_contacto_id) VALUES
('3704123456', 1),  -- Gabriela Fernández
('3704234567', 1),  -- Esteban Morales
('3705345678', 1),  -- Lorena Rodríguez
('3705456789', 1),  -- Manuel Fernández
('3704567890', 1),  -- Patricia González
('3705678901', 1),  -- Arturo Pérez
('3704789012', 1),  -- Carolina Ríos
('3705890123', 1),  -- Ricardo López
('3704901234', 1),  -- Teresa Martínez
('3705012345', 1);  -- Andrés López

-- Asociar los números de teléfono a los clientes en tb_detalle_contacto
-- Nota: Si ya hay valores en tb_detalle_contacto, asegúrate de ajustar los IDs correctamente.

-- Asociar el número de teléfono a los clientes en tb_clientes
-- Primero, obtenemos los IDs de detalle de contacto que se acaban de insertar
SET @telefono_1 = (SELECT idDetalleContacto FROM tb_detalle_contacto WHERE valorDetalleContacto = '3704-123456');
SET @telefono_2 = (SELECT idDetalleContacto FROM tb_detalle_contacto WHERE valorDetalleContacto = '3704-234567');
SET @telefono_3 = (SELECT idDetalleContacto FROM tb_detalle_contacto WHERE valorDetalleContacto = '3705-345678');
SET @telefono_4 = (SELECT idDetalleContacto FROM tb_detalle_contacto WHERE valorDetalleContacto = '3705-456789');
SET @telefono_5 = (SELECT idDetalleContacto FROM tb_detalle_contacto WHERE valorDetalleContacto = '3704-567890');
SET @telefono_6 = (SELECT idDetalleContacto FROM tb_detalle_contacto WHERE valorDetalleContacto = '3705-678901');
SET @telefono_7 = (SELECT idDetalleContacto FROM tb_detalle_contacto WHERE valorDetalleContacto = '3704-789012');
SET @telefono_8 = (SELECT idDetalleContacto FROM tb_detalle_contacto WHERE valorDetalleContacto = '3705-890123');
SET @telefono_9 = (SELECT idDetalleContacto FROM tb_detalle_contacto WHERE valorDetalleContacto = '3704-901234');
SET @telefono_10 = (SELECT idDetalleContacto FROM tb_detalle_contacto WHERE valorDetalleContacto = '3705-012345');

-- Actualizar la tabla tb_personas_fisicas para asociar los detalles de contacto a cada cliente
UPDATE tb_personas_fisicas SET detalle_contacto_id = @telefono_1 WHERE idPersonaFisica = 36;
UPDATE tb_personas_fisicas SET detalle_contacto_id = @telefono_2 WHERE idPersonaFisica = 37;
UPDATE tb_personas_fisicas SET detalle_contacto_id = @telefono_3 WHERE idPersonaFisica = 38;
UPDATE tb_personas_fisicas SET detalle_contacto_id = @telefono_4 WHERE idPersonaFisica = 39;
UPDATE tb_personas_fisicas SET detalle_contacto_id = @telefono_5 WHERE idPersonaFisica = 40;
UPDATE tb_personas_fisicas SET detalle_contacto_id = @telefono_6 WHERE idPersonaFisica = 41;
UPDATE tb_personas_fisicas SET detalle_contacto_id = @telefono_7 WHERE idPersonaFisica = 42;
UPDATE tb_personas_fisicas SET detalle_contacto_id = @telefono_8 WHERE idPersonaFisica = 43;
UPDATE tb_personas_fisicas SET detalle_contacto_id = @telefono_9 WHERE idPersonaFisica = 44;
UPDATE tb_personas_fisicas SET detalle_contacto_id = @telefono_10 WHERE idPersonaFisica = 45;

-- Insertar domicilios ficticios en tb_domicilios
INSERT INTO tb_domicilios (descripcionDomicilio, barrio_id) VALUES
('Calle Libertador 123', 1),  -- Barrio Independencia
('Avenida San Martín 456', 2),  -- Barrio San Martín
('Calle 9 de Julio 789', 3),  -- Divino Niño
('Calle Belgrano 101', 4),  -- El Palamar
('Calle 25 de Mayo 202', 5),  -- El Palomar
('Avenida San Juan 303', 6),  -- El Paraíso
('Calle Mitre 404', 7),  -- El Porvenir
('Avenida Rivadavia 505', 8),  -- El Quebrachito
('Calle Corrientes 606', 9),  -- El Quebranto
('Calle Tucumán 707', 10);  -- La Delicia

-- Asociar domicilios a las personas físicas
INSERT INTO tb_domicilios_personas (valorDomicilio, persona_fisica_id, domicilio_id) VALUES
('Calle Libertador 123', 36, (SELECT idDomicilio FROM tb_domicilios WHERE descripcionDomicilio = 'Calle Libertador 123')),  -- Gabriela Fernández
('Avenida San Martín 456', 37, (SELECT idDomicilio FROM tb_domicilios WHERE descripcionDomicilio = 'Avenida San Martín 456')),  -- Esteban Morales
('Calle 9 de Julio 789', 38, (SELECT idDomicilio FROM tb_domicilios WHERE descripcionDomicilio = 'Calle 9 de Julio 789')),  -- Lorena Rodríguez
('Calle Belgrano 101', 39, (SELECT idDomicilio FROM tb_domicilios WHERE descripcionDomicilio = 'Calle Belgrano 101')),  -- Manuel Fernández
('Calle 25 de Mayo 202', 40, (SELECT idDomicilio FROM tb_domicilios WHERE descripcionDomicilio = 'Calle 25 de Mayo 202')),  -- Patricia González
('Avenida San Juan 303', 41, (SELECT idDomicilio FROM tb_domicilios WHERE descripcionDomicilio = 'Avenida San Juan 303')),  -- Arturo Pérez
('Calle Mitre 404', 42, (SELECT idDomicilio FROM tb_domicilios WHERE descripcionDomicilio = 'Calle Mitre 404')),  -- Carolina Ríos
('Avenida Rivadavia 505', 43, (SELECT idDomicilio FROM tb_domicilios WHERE descripcionDomicilio = 'Avenida Rivadavia 505')),  -- Ricardo López
('Calle Corrientes 606', 44, (SELECT idDomicilio FROM tb_domicilios WHERE descripcionDomicilio = 'Calle Corrientes 606')),  -- Teresa Martínez
('Calle Tucumán 707', 45, (SELECT idDomicilio FROM tb_domicilios WHERE descripcionDomicilio = 'Calle Tucumán 707'));  -- Andrés López

-- Insertar detalles de documentos para nuevas personas físicas para personas juridícas es decir proveedores (46+20=65)
INSERT INTO tb_detalle_documento (tipo_documento_id, valorDocumento) VALUES
(1, '90012345'),  -- Luis Fernández
(1, '90123456'),  -- Silvia García
(1, '90234567'),  -- Andrés Rojas
(1, '90345678'),  -- Verónica López
(1, '90456789'),  -- Javier Márquez
(1, '90567890'),  -- Paola Ríos
(1, '90678901'),  -- Felipe Córdoba
(1, '90789012'),  -- Natalia Salazar
(1, '90890123'),  -- Santiago Pinto
(1, '90901234'),  -- Margarita Ramírez
(1, '91012345'),  -- Rodrigo Morales
(1, '91123456'),  -- Carolina Gómez
(1, '91234567'),  -- Manuel Vega
(1, '91345678'),  -- Cecilia Sánchez
(1, '91456789'),  -- Alejandro Torres
(1, '91567890'),  -- Andrea Mendoza
(1, '91678901'),  -- Gustavo García
(1, '91789012'),  -- Valeria Álvarez
(1, '91890123'),  -- Oscar Moreno
(1, '91901234');  -- Lucía Castro

-- Insertar nuevas personas físicas
INSERT INTO tb_personas_fisicas (nombres, apellidos, fechaNacimiento, sexo, estado_persona_id, detalle_documento_id, detalle_contacto_id)
VALUES 
('Luis', 'Fernández', '1985-02-01', 'Masculino', 1, 46, NULL),
('Silvia', 'García', '1991-04-15', 'Femenino', 1, 47, NULL),
('Andrés', 'Rojas', '1978-06-22', 'Masculino', 1, 48, NULL),
('Verónica', 'López', '1983-08-30', 'Femenino', 1, 49, NULL),
('Javier', 'Márquez', '1990-10-10', 'Masculino', 1, 50, NULL),
('Paola', 'Ríos', '1987-12-01', 'Femenino', 1, 51, NULL),
('Felipe', 'Córdoba', '1982-03-21', 'Masculino', 1, 52, NULL),
('Natalia', 'Salazar', '1995-01-25', 'Femenino', 1, 53, NULL),
('Santiago', 'Pinto', '1988-05-05', 'Masculino', 1, 54, NULL),
('Margarita', 'Ramírez', '1993-07-18', 'Femenino', 1, 55, NULL),
('Rodrigo', 'Morales', '1980-11-10', 'Masculino', 1, 56, NULL),
('Carolina', 'Gómez', '1984-09-12', 'Femenino', 1, 57, NULL),
('Manuel', 'Vega', '1977-04-17', 'Masculino', 1, 58, NULL),
('Cecilia', 'Sánchez', '1992-02-14', 'Femenino', 1, 59, NULL),
('Alejandro', 'Torres', '1989-06-25', 'Masculino', 1, 60, NULL),
('Andrea', 'Mendoza', '1986-08-28', 'Femenino', 1, 61, NULL),
('Gustavo', 'García', '1981-05-15', 'Masculino', 1, 62, NULL),
('Valeria', 'Álvarez', '1994-01-12', 'Femenino', 1, 63, NULL),
('Oscar', 'Moreno', '1976-07-20', 'Masculino', 1, 64, NULL),
('Lucía', 'Castro', '1990-12-10', 'Femenino', 1, 65, NULL);

-- Insertar registros en tb_personas_juridicas
INSERT INTO tb_personas_juridicas (razonSocial, persona_fisica_id, estado_persona_juridica_id) VALUES
('Empresa Luis Fernández S.A.', 46, 1),
('Servicios Silvia García S.R.L.', 47, 1),
('Andrés Rojas Consultores', 48, 1),
('Verónica López y Asociados', 49, 1),
('Javier Márquez & Co.', 50, 1),
('Paola Ríos Servicios', 51, 1),
('Felipe Córdoba Ltda.', 52, 1),
('Natalia Salazar S.A.', 53, 1),
('Santiago Pinto Ltda.', 54, 1),
('Margarita Ramírez S.R.L.', 55, 1),
('Rodrigo Morales y Cía.', 56, 1),
('Carolina Gómez Consultores', 57, 1),
('Manuel Vega Servicios', 58, 1),
('Cecilia Sánchez Ltda.', 59, 1),
('Alejandro Torres S.A.', 60, 1),
('Andrea Mendoza y Asociados', 61, 1),
('Gustavo García Consultores', 62, 1),
('Valeria Álvarez S.R.L.', 63, 1),
('Oscar Moreno Ltda.', 64, 1),
('Lucía Castro S.A.', 65, 1);

-- Insertar registros en tb_proveedores
INSERT INTO tb_proveedores (persona_juridica_id) VALUES
(1),  -- Empresa Luis Fernández S.A.
(2),  -- Servicios Silvia García S.R.L.
(3),  -- Andrés Rojas Consultores
(4),  -- Verónica López y Asociados
(5),  -- Javier Márquez & Co.
(6),  -- Paola Ríos Servicios
(7),  -- Felipe Córdoba Ltda.
(8),  -- Natalia Salazar S.A.
(9),  -- Santiago Pinto Ltda.
(10), -- Margarita Ramírez S.R.L.
(11), -- Rodrigo Morales y Cía.
(12), -- Carolina Gómez Consultores
(13), -- Manuel Vega Servicios
(14), -- Cecilia Sánchez Ltda.
(15), -- Alejandro Torres S.A.
(16), -- Andrea Mendoza y Asociados
(17), -- Gustavo García Consultores
(18), -- Valeria Álvarez S.R.L.
(19), -- Oscar Moreno Ltda.
(20); -- Lucía Castro S.A.

INSERT INTO tb_sucursal (nombreSucursal, persona_juridica_id) VALUES
('FormoStock', 1),
('TechHub Central', 2),
('Innovate North', 3),
('FutureTech South', 4),
('CyberSpace East', 5),
('DataCore West', 6);

INSERT INTO tb_domicilios (descripcionDomicilio, barrio_id)
VALUES ('Calle Fotheringham 789', 2);  -- Barrio San Martín con idBarrio = 2

SET @domicilio_id = LAST_INSERT_ID();

INSERT INTO tb_domicilios_personas (valorDomicilio, persona_fisica_id, domicilio_id)
VALUES ('Calle Fotheringham 789', 46, @domicilio_id);

UPDATE tb_domicilios_personas
SET valorDomicilio = 'Calle Fotheringham 789', domicilio_id = @domicilio_id
WHERE persona_fisica_id = 46;

-- Insertar nuevas direcciones en tb_domicilios
INSERT INTO tb_domicilios (descripcionDomicilio, barrio_id) VALUES
('Calle Fontana 123', 2),  -- Barrio San Martín
('Calle España 456', 3),   -- Barrio Divino Niño
('Calle Moreno 789', 4),   -- Barrio El Palamar
('Calle Belgrano 101', 5), -- Barrio El Palomar
('Calle Rivadavia 202', 6);-- Barrio El Paraíso

-- Obtener los últimos IDs de domicilios insertados
SET @domicilio_47 = LAST_INSERT_ID();
SET @domicilio_48 = @domicilio_47 - 1;
SET @domicilio_49 = @domicilio_48 - 1;
SET @domicilio_50 = @domicilio_49 - 1;
SET @domicilio_51 = @domicilio_50 - 1;

-- Insertar relaciones en tb_domicilios_personas para los nuevos domicilios
INSERT INTO tb_domicilios_personas (valorDomicilio, persona_fisica_id, domicilio_id) VALUES
('Calle Fontana 123', 47, @domicilio_47),   -- Persona física con ID 47
('Calle España 456', 48, @domicilio_48),    -- Persona física con ID 48
('Calle Moreno 789', 49, @domicilio_49),    -- Persona física con ID 49
('Calle Belgrano 101', 50, @domicilio_50),  -- Persona física con ID 50
('Calle Rivadavia 202', 51, @domicilio_51); -- Persona física con ID 51

-- INSERCIÓN DE PRODUCTOS EN EL INVENTARIO CON PROVEEDORES
INSERT INTO tb_productos (codigoBarrasProducto, numeroDeSerieProducto, descripcionProducto, precioProducto, stockMinProducto, stockMaxProducto, garantiaProducto, imagenProducto, impuesto_id, categoria_id, marca_id, estado_producto_id, proveedor_id)
VALUES 
('CB0001', 'SN0001', 'Notebook Gamer Lenovo Legion 5 15ACH6A WQHD 2K 15.6" R5 5600H 16GB (2x8GB) 512GB SSD NVME RX6600M 8GB W11 165Hz Silver', 1169850.00, 5, 50, '12 meses', 'https://ejemplo.com/imagen/producto-1-ejemplo.jpg', NULL, NULL, NULL, 25, 1),
('CB0002', 'SN0002', 'Memoria Team DDR5 64GB (2x32GB) 6000Mhz T-CREATE EXPERT CL34 Black Intel XMP 3.0 / AMD EXPO', 279900.99, 10, 100, '6 meses', 'https://ejemplo.com/imagen/producto-2-ejemplo.jpg', NULL, NULL, NULL, 25, 1),
('CB0003', 'SN0003', 'Procesador AMD RYZEN 5 3600 4.2GHz Turbo AM4 Wraith Stealth Cooler', 154500.99, 20, 200, '3 meses', 'https://ejemplo.com/imagen/producto-3-ejemplo.jpg', NULL, NULL, NULL, 25, 1),
('CB0004', 'SN0004', 'Disco Duro SSD Samsung 980 PRO 1TB NVMe PCIe Gen 4.0', 340000.00, 15, 150, '5 años', 'https://ejemplo.com/imagen/producto-4-ejemplo.jpg', NULL, NULL, NULL, 25, 1),
('CB0005', 'SN0005', 'Tarjeta Gráfica NVIDIA GeForce RTX 3080 Ti Founders Edition', 1499900.50, 5, 60, '3 años', 'https://ejemplo.com/imagen/producto-5-ejemplo.jpg', NULL, NULL, NULL, 25, 1),

('CB0006', 'SN0006', 'Monitor Gaming ASUS ROG Swift PG259QN 24.5" FHD 360Hz', 899900.00, 5, 50, '2 años', 'https://ejemplo.com/imagen/producto-6-ejemplo.jpg', NULL, NULL, NULL, 25, 2),
('CB0007', 'SN0007', 'Teclado Mecánico Corsair K95 RGB Platinum XT', 229900.99, 10, 200, '2 años', 'https://ejemplo.com/imagen/producto-7-ejemplo.jpg', NULL, NULL, NULL, 25, 2),
('CB0008', 'SN0008', 'Mouse Inalámbrico Logitech MX Master 3 Advanced', 129900.00, 10, 120, '1 año', 'https://ejemplo.com/imagen/producto-8-ejemplo.jpg', NULL, NULL, NULL, 25, 2),
('CB0009', 'SN0009', 'Fuente de Poder Corsair RM850x 850W 80 Plus Gold', 189900.99, 10, 90, '10 años', 'https://ejemplo.com/imagen/producto-9-ejemplo.jpg', NULL, NULL, NULL, 25, 2),
('CB0010', 'SN0010', 'Silla Gamer Secretlab TITAN Evo 2022 SoftWeave Plus', 459900.50, 5, 30, '5 años', 'https://ejemplo.com/imagen/producto-10-ejemplo.jpg', NULL, NULL, NULL, 25, 2),

('CB0011', 'SN0011', 'Cable HDMI 2.1 4K 60Hz 1.8m', 2999.99, 20, 300, '1 año', 'https://ejemplo.com/imagen/producto-11-ejemplo.jpg', NULL, NULL, NULL, 1, 3),
('CB0012', 'SN0012', 'Hub USB 3.0 7 Puertos', 12900.00, 15, 150, '6 meses', 'https://ejemplo.com/imagen/producto-12-ejemplo.jpg', NULL, NULL, NULL, 1, 3),
('CB0013', 'SN0013', 'Adaptador USB-C a HDMI', 4500.00, 20, 250, '1 año', 'https://ejemplo.com/imagen/producto-13-ejemplo.jpg', NULL, NULL, NULL, 1, 3),
('CB0014', 'SN0014', 'Disco Duro Externo WD My Passport 2TB', 89000.00, 10, 120, '3 años', 'https://ejemplo.com/imagen/producto-14-ejemplo.jpg', NULL, NULL, NULL, 1, 3),
('CB0015', 'SN0015', 'Laptop Dell XPS 13 9310 Core i7 16GB 512GB SSD', 1850000.00, 5, 60, '1 año', 'https://ejemplo.com/imagen/producto-15-ejemplo.jpg', NULL, NULL, NULL, 1, 3),

('CB0016', 'SN0016', 'Webcam Logitech C920 HD', 74900.00, 15, 140, '2 años', 'https://ejemplo.com/imagen/producto-16-ejemplo.jpg', NULL, NULL, NULL, 25, 4),
('CB0017', 'SN0017', 'Router TP-Link Archer AX73', 139900.00, 10, 80, '3 años', 'https://ejemplo.com/imagen/producto-17-ejemplo.jpg', NULL, NULL, NULL, 25, 4),
('CB0018', 'SN0018', 'Auriculares Gaming HyperX Cloud II', 119900.00, 10, 120, '2 años', 'https://ejemplo.com/imagen/producto-18-ejemplo.jpg', NULL, NULL, NULL, 25, 4),
('CB0019', 'SN0019', 'Docking Station USB-C Dell D6000', 239900.00, 5, 70, '2 años', 'https://ejemplo.com/imagen/producto-19-ejemplo.jpg', NULL, NULL, NULL, 25, 4),
('CB0020', 'SN0020', 'Pantalla Táctil ASUS VT229H 21.5" Full HD', 359900.00, 5, 50, '3 años', 'https://ejemplo.com/imagen/producto-20-ejemplo.jpg', NULL, NULL, NULL, 25, 4),

('CB0021', 'SN0021', 'SSD Crucial MX500 500GB', 44900.00, 20, 200, '5 años', 'https://ejemplo.com/imagen/producto-21-ejemplo.jpg', NULL, NULL, NULL, 25, 5),
('CB0022', 'SN0022', 'Tablet Samsung Galaxy Tab S7', 699900.00, 5, 40, '2 años', 'https://ejemplo.com/imagen/producto-22-ejemplo.jpg', NULL, NULL, NULL, 25, 5),
('CB0023', 'SN0023', 'Impresora HP OfficeJet Pro 9015', 119990.00, 5, 30, '1 año', 'https://ejemplo.com/imagen/producto-23-ejemplo.jpg', NULL, NULL, NULL, 25, 5),
('CB0024', 'SN0024', 'Teclado Logitech MX Keys', 18900.00, 15, 140, '2 años', 'https://ejemplo.com/imagen/producto-24-ejemplo.jpg', NULL, NULL, NULL, 25, 5),
('CB0025', 'SN0025', 'Monitor LG UltraWide 34WK95U-W 34" 5K', 1499900.00, 5, 50, '3 años', 'https://ejemplo.com/imagen/producto-25-ejemplo.jpg', NULL, NULL, NULL, 25, 5),

('CB0026', 'SN0026', 'Fuente de Poder EVGA SuperNOVA 750 G5', 109900.00, 10, 90, '10 años', 'https://ejemplo.com/imagen/producto-26-ejemplo.jpg', NULL, NULL, NULL, 25, 6),
('CB0027', 'SN0027', 'Mouse Gaming Razer DeathAdder V2', 59900.00, 10, 200, '2 años', 'https://ejemplo.com/imagen/producto-27-ejemplo.jpg', NULL, NULL, NULL, 25, 6),
('CB0028', 'SN0028', 'Teclado Razer BlackWidow V3', 109900.00, 10, 150, '2 años', 'https://ejemplo.com/imagen/producto-28-ejemplo.jpg', NULL, NULL, NULL, 25, 6),
('CB0029', 'SN0029', 'Silla Gamer AKRacing Core Series EX', 449900.00, 5, 30, '3 años', 'https://ejemplo.com/imagen/producto-29-ejemplo.jpg', NULL, NULL, NULL, 25, 6),
('CB0030', 'SN0030', 'GPU ZOTAC GeForce RTX 3070 Twin Edge OC', 859900.00, 5, 40, '3 años', 'https://ejemplo.com/imagen/producto-30-ejemplo.jpg', NULL, NULL, NULL, 25, 6),

('CB0031', 'SN0031', 'Disco Duro Seagate Expansion 4TB', 159900.00, 35, 70, '3 años', 'https://ejemplo.com/imagen/producto-31-ejemplo.jpg', NULL, NULL, NULL, 25, 7),
('CB0032', 'SN0032', 'Pantalla Gaming ASUS ROG Swift PG32UQX 32" 4K', 3599000.00, 5, 10, '2 años', 'https://ejemplo.com/imagen/producto-32-ejemplo.jpg', NULL, NULL, NULL, 25, 7),
('CB0033', 'SN0033', 'Tarjeta Madre ASUS ROG Strix X570-E', 429900.00, 20, 40, '3 años', 'https://ejemplo.com/imagen/producto-33-ejemplo.jpg', NULL, NULL, NULL, 25, 7),
('CB0034', 'SN0034', 'Kit de Refrigeración Líquida NZXT Kraken Z63', 249900.00, 15, 30, '5 años', 'https://ejemplo.com/imagen/producto-34-ejemplo.jpg', NULL, NULL, NULL, 25, 7),
('CB0035', 'SN0035', 'Cable de Poder C13 1.8m', 1500.00, 100, 200, '1 año', 'https://ejemplo.com/imagen/producto-35-ejemplo.jpg', NULL, NULL, NULL, 25, 7),

('CB0036', 'SN0036', 'Estación de Acoplamiento Dell WD19', 139900.00, 30, 60, '2 años', 'https://ejemplo.com/imagen/producto-36-ejemplo.jpg', NULL, NULL, NULL, 25, 8),
('CB0037', 'SN0037', 'Teclado para Juegos HyperX Alloy FPS', 49900.00, 80, 200, '1 año', 'https://ejemplo.com/imagen/producto-37-ejemplo.jpg', NULL, NULL, NULL, 25, 8),
('CB0038', 'SN0038', 'Auriculares Gaming Razer Kraken', 89900.00, 45, 90, '3 años', 'https://ejemplo.com/imagen/producto-38-ejemplo.jpg', NULL, NULL, NULL, 25, 8),
('CB0039', 'SN0039', 'Base de Carga Inalámbrica Anker', 49900.00, 75, 150, '2 años', 'https://ejemplo.com/imagen/producto-39-ejemplo.jpg', NULL, NULL, NULL, 25, 8),
('CB0040', 'SN0040', 'Smartphone Samsung Galaxy S21 FE', 799900.00, 20, 40, '1 año', 'https://ejemplo.com/imagen/producto-40-ejemplo.jpg', NULL, NULL, NULL, 25, 8),

('CB0041', 'SN0041', 'Monitor BenQ GW2480 24" Full HD', 219900.00, 60, 120, '3 años', 'https://ejemplo.com/imagen/producto-41-ejemplo.jpg', NULL, NULL, NULL, 25, 9),
('CB0042', 'SN0042', 'Proyector Epson Home Cinema 2150', 1999000.00, 5, 10, '2 años', 'https://ejemplo.com/imagen/producto-42-ejemplo.jpg', NULL, NULL, NULL, 25, 9),
('CB0043', 'SN0043', 'Router ASUS RT-AX88U', 299900.00, 25, 50, '3 años', 'https://ejemplo.com/imagen/producto-43-ejemplo.jpg', NULL, NULL, NULL, 25, 9),
('CB0044', 'SN0044', 'Teclado Ergonomico Microsoft Sculpt', 89900.00, 40, 80, '1 año', 'https://ejemplo.com/imagen/producto-44-ejemplo.jpg', NULL, NULL, NULL, 25, 9),
('CB0045', 'SN0045', 'Raspberry Pi 4 Model B 4GB', 44900.00, 70, 140, '6 meses', 'https://ejemplo.com/imagen/producto-45-ejemplo.jpg', NULL, NULL, NULL, 25, 9),

('CB0046', 'SN0046', 'Tarjeta Madre MSI B450M PRO-VDH MAX', 119900.00, 40, 80, '3 años', 'https://ejemplo.com/imagen/producto-46-ejemplo.jpg', NULL, NULL, NULL, 25, 10),
('CB0047', 'SN0047', 'Laptop Acer Aspire 5 A515', 699900.00, 30, 60, '1 año', 'https://ejemplo.com/imagen/producto-47-ejemplo.jpg', NULL, NULL, NULL, 25, 10),
('CB0048', 'SN0048', 'Sistema de Audio Logitech Z623', 199900.00, 20, 40, '2 años', 'https://ejemplo.com/imagen/producto-48-ejemplo.jpg', NULL, NULL, NULL, 25, 10),
('CB0049', 'SN0049', 'Disco Duro Interno Seagate Barracuda 1TB', 49900.00, 100, 200, '5 años', 'https://ejemplo.com/imagen/producto-49-ejemplo.jpg', NULL, NULL, NULL, 25, 10),
('CB0050', 'SN0050', 'Camara Web Razer Kiyo', 99900.00, 50, 100, '1 año', 'https://ejemplo.com/imagen/producto-50-ejemplo.jpg', NULL, NULL, NULL, 25, 10),

('CB0051', 'SN0051', 'Tarjeta de Sonido Creative Sound Blaster Z', 179900.00, 40, 80, '2 años', 'https://ejemplo.com/imagen/producto-51-ejemplo.jpg', NULL, NULL, NULL, 25, 11),
('CB0052', 'SN0052', 'Laptop HP Pavilion 15', 799900.00, 25, 50, '1 año', 'https://ejemplo.com/imagen/producto-52-ejemplo.jpg', NULL, NULL, NULL, 25, 11),
('CB0053', 'SN0053', 'Proyector BenQ TK850', 2999000.00, 5, 10, '2 años', 'https://ejemplo.com/imagen/producto-53-ejemplo.jpg', NULL, NULL, NULL, 25, 11),
('CB0054', 'SN0054', 'Tablet Lenovo Tab P11', 399900.00, 20, 40, '3 años', 'https://ejemplo.com/imagen/producto-54-ejemplo.jpg', NULL, NULL, NULL, 25, 11),
('CB0055', 'SN0055', 'Auricular Sony WH-1000XM4', 349900.00, 30, 60, '1 año', 'https://ejemplo.com/imagen/producto-55-ejemplo.jpg', NULL, NULL, NULL, 25, 11),

('CB0056', 'SN0056', 'Camara de Seguridad TP-Link Tapo C100', 89900.00, 70, 140, '2 años', 'https://ejemplo.com/imagen/producto-56-ejemplo.jpg', NULL, NULL, NULL, 25, 12),
('CB0057', 'SN0057', 'Teclado Logitech G915 TKL', 249900.00, 50, 100, '1 año', 'https://ejemplo.com/imagen/producto-57-ejemplo.jpg', NULL, NULL, NULL, 25, 12),
('CB0058', 'SN0058', 'Bocina Bluetooth Anker Soundcore 2', 39900.00, 100, 200, '5 años', 'https://ejemplo.com/imagen/producto-58-ejemplo.jpg', NULL, NULL, NULL, 25, 12),
('CB0059', 'SN0059', 'Consola Nintendo Switch', 299900.00, 15, 30, '1 año', 'https://ejemplo.com/imagen/producto-59-ejemplo.jpg', NULL, NULL, NULL, 25, 12),
('CB0060', 'SN0060', 'Estación de Juego Razer Raptor 27', 2999000.00, 5, 10, '2 años', 'https://ejemplo.com/imagen/producto-60-ejemplo.jpg', NULL, NULL, NULL, 25, 12);
-- Actualización de productos con las nuevas categorías y marcas
UPDATE tb_productos SET categoria_id = 1,  marca_id = 11 WHERE idProducto = 1;
UPDATE tb_productos SET categoria_id = 4,  marca_id = 2  WHERE idProducto = 2;
UPDATE tb_productos SET categoria_id = 4,  marca_id = 2  WHERE idProducto = 3;
UPDATE tb_productos SET categoria_id = 5,  marca_id = 6  WHERE idProducto = 4;
UPDATE tb_productos SET categoria_id = 4,  marca_id = 3  WHERE idProducto = 5;
UPDATE tb_productos SET categoria_id = 6,  marca_id = 9  WHERE idProducto = 6;
UPDATE tb_productos SET categoria_id = 8,  marca_id = 13 WHERE idProducto = 7;
UPDATE tb_productos SET categoria_id = 8,  marca_id = 12 WHERE idProducto = 8;
UPDATE tb_productos SET categoria_id = 4,  marca_id = 13 WHERE idProducto = 9;
UPDATE tb_productos SET categoria_id = 8,  marca_id = 19 WHERE idProducto = 10;
UPDATE tb_productos SET categoria_id = 2,  marca_id = 9  WHERE idProducto = 11;
UPDATE tb_productos SET categoria_id = 2,  marca_id = 12 WHERE idProducto = 12;
UPDATE tb_productos SET categoria_id = 2,  marca_id = 12 WHERE idProducto = 13;
UPDATE tb_productos SET categoria_id = 5,  marca_id = 16 WHERE idProducto = 14;
UPDATE tb_productos SET categoria_id = 1,  marca_id = 7  WHERE idProducto = 15;
UPDATE tb_productos SET categoria_id = 2,  marca_id = 12 WHERE idProducto = 16;
UPDATE tb_productos SET categoria_id = 5,  marca_id = 28 WHERE idProducto = 17;
UPDATE tb_productos SET categoria_id = 8,  marca_id = 19 WHERE idProducto = 18;
UPDATE tb_productos SET categoria_id = 9,  marca_id = 7  WHERE idProducto = 19;
UPDATE tb_productos SET categoria_id = 6,  marca_id = 9  WHERE idProducto = 20;
UPDATE tb_productos SET categoria_id = 5,  marca_id = 14 WHERE idProducto = 21;
UPDATE tb_productos SET categoria_id = 3,  marca_id = 6  WHERE idProducto = 22;
UPDATE tb_productos SET categoria_id = 7,  marca_id = 8  WHERE idProducto = 23;
UPDATE tb_productos SET categoria_id = 2,  marca_id = 12 WHERE idProducto = 24;
UPDATE tb_productos SET categoria_id = 10, marca_id = 21 WHERE idProducto = 25;
UPDATE tb_productos SET categoria_id = 4,  marca_id = 17 WHERE idProducto = 26;
UPDATE tb_productos SET categoria_id = 11, marca_id = 21 WHERE idProducto = 27;
UPDATE tb_productos SET categoria_id = 3,  marca_id = 25 WHERE idProducto = 28;
UPDATE tb_productos SET categoria_id = 8,  marca_id = 19 WHERE idProducto = 29;
UPDATE tb_productos SET categoria_id = 10, marca_id = 30 WHERE idProducto = 30;
UPDATE tb_productos SET categoria_id = 1,  marca_id = 9  WHERE idProducto = 31;
UPDATE tb_productos SET categoria_id = 1,  marca_id = 5  WHERE idProducto = 32;
UPDATE tb_productos SET categoria_id = 1,  marca_id = 8  WHERE idProducto = 33;
UPDATE tb_productos SET categoria_id = 1,  marca_id = 11 WHERE idProducto = 34;
UPDATE tb_productos SET categoria_id = 1,  marca_id = 7  WHERE idProducto = 35;

-- Actualizar un producto con ID 1
UPDATE tb_productos
SET categoria_id = 1, marca_id = 7
WHERE idProducto = 1;

-- Actualizar un producto con ID 2
UPDATE tb_productos
SET categoria_id = 2, marca_id = 12
WHERE idProducto = 2;

-- Actualizar un producto con ID 3
UPDATE tb_productos
SET categoria_id = 3, marca_id = 4
WHERE idProducto = 3;

-- Actualizar un producto con ID 4
UPDATE tb_productos
SET categoria_id = 4, marca_id = 16
WHERE idProducto = 4;

-- Actualizar un producto con ID 5
UPDATE tb_productos
SET categoria_id = 5, marca_id = 14
WHERE idProducto = 5;

-- Actualizar un producto con ID 6
UPDATE tb_productos
SET categoria_id = 6, marca_id = 1
WHERE idProducto = 6;

-- Actualizar un producto con ID 7
UPDATE tb_productos
SET categoria_id = 7, marca_id = 24
WHERE idProducto = 7;

-- Actualizar un producto con ID 8
UPDATE tb_productos
SET categoria_id = 8, marca_id = 17
WHERE idProducto = 8;

-- Actualizar un producto con ID 9
UPDATE tb_productos
SET categoria_id = 9, marca_id = 20
WHERE idProducto = 9;

-- Actualizar un producto con ID 10
UPDATE tb_productos
SET categoria_id = 10, marca_id = 19
WHERE idProducto = 10;

INSERT INTO tb_tipo_impuestos (nombreImpuesto) VALUES
('IVA'),
('Ingresos Brutos'),
('Impuesto a los Débitos y Créditos Bancarios'),
('Percepción Ganancias'),
('Percepción Bienes Personales');

-- IVA
INSERT INTO tb_detalle_impuestos (valorDetalleImpuesto, tipo_impuesto_id) VALUES
(21.00, 1),
(10.50, 1),
(27.00, 1);

-- Ingresos Brutos
INSERT INTO tb_detalle_impuestos (valorDetalleImpuesto, tipo_impuesto_id) VALUES
(3.50, 2),
(2.00, 2);

-- Impuesto a los débitos y créditos bancarios
INSERT INTO tb_detalle_impuestos (valorDetalleImpuesto, tipo_impuesto_id) VALUES
(0.60, 3);

-- Percepción Ganancias
INSERT INTO tb_detalle_impuestos (valorDetalleImpuesto, tipo_impuesto_id) VALUES
(30.00, 4);

-- Percepción Bienes Personales
INSERT INTO tb_detalle_impuestos (valorDetalleImpuesto, tipo_impuesto_id) VALUES
(30.00, 5);


-- Inserción de stock aleatorio para productos del 1 al 60 en la sucursal con id 1
INSERT INTO tb_inventario_sucursal (sucursal_id, producto_id, stockSucursal) VALUES
(1, 1, FLOOR(10 + RAND() * 4) * 5), -- Cantidades aleatorias entre 10 y 25
(1, 2, FLOOR(10 + RAND() * 4) * 5),
(1, 3, FLOOR(10 + RAND() * 4) * 5),
(1, 4, FLOOR(10 + RAND() * 4) * 5),
(1, 5, FLOOR(10 + RAND() * 4) * 5),
(1, 6, FLOOR(10 + RAND() * 4) * 5),
(1, 7, FLOOR(10 + RAND() * 4) * 5),
(1, 8, FLOOR(10 + RAND() * 4) * 5),
(1, 9, FLOOR(10 + RAND() * 4) * 5),
(1, 10, FLOOR(10 + RAND() * 4) * 5),
(1, 11, FLOOR(10 + RAND() * 4) * 5),
(1, 12, FLOOR(10 + RAND() * 4) * 5),
(1, 13, FLOOR(10 + RAND() * 4) * 5),
(1, 14, FLOOR(10 + RAND() * 4) * 5),
(1, 15, FLOOR(10 + RAND() * 4) * 5),
(1, 16, FLOOR(10 + RAND() * 4) * 5),
(1, 17, FLOOR(10 + RAND() * 4) * 5),
(1, 18, FLOOR(10 + RAND() * 4) * 5),
(1, 19, FLOOR(10 + RAND() * 4) * 5),
(1, 20, FLOOR(10 + RAND() * 4) * 5),
(1, 21, FLOOR(10 + RAND() * 4) * 5),
(1, 22, FLOOR(10 + RAND() * 4) * 5),
(1, 23, FLOOR(10 + RAND() * 4) * 5),
(1, 24, FLOOR(10 + RAND() * 4) * 5),
(1, 25, FLOOR(10 + RAND() * 4) * 5),
(1, 26, FLOOR(10 + RAND() * 4) * 5),
(1, 27, FLOOR(10 + RAND() * 4) * 5),
(1, 28, FLOOR(10 + RAND() * 4) * 5),
(1, 29, FLOOR(10 + RAND() * 4) * 5),
(1, 30, FLOOR(10 + RAND() * 4) * 5),
(1, 31, FLOOR(10 + RAND() * 4) * 5),
(1, 32, FLOOR(10 + RAND() * 4) * 5),
(1, 33, FLOOR(10 + RAND() * 4) * 5),
(1, 34, FLOOR(10 + RAND() * 4) * 5),
(1, 35, FLOOR(10 + RAND() * 4) * 5),
(1, 36, FLOOR(10 + RAND() * 4) * 5),
(1, 37, FLOOR(10 + RAND() * 4) * 5),
(1, 38, FLOOR(10 + RAND() * 4) * 5),
(1, 39, FLOOR(10 + RAND() * 4) * 5),
(1, 40, FLOOR(10 + RAND() * 4) * 5),
(1, 41, FLOOR(10 + RAND() * 4) * 5),
(1, 42, FLOOR(10 + RAND() * 4) * 5),
(1, 43, FLOOR(10 + RAND() * 4) * 5),
(1, 44, FLOOR(10 + RAND() * 4) * 5),
(1, 45, FLOOR(10 + RAND() * 4) * 5),
(1, 46, FLOOR(10 + RAND() * 4) * 5),
(1, 47, FLOOR(10 + RAND() * 4) * 5),
(1, 48, FLOOR(10 + RAND() * 4) * 5),
(1, 49, FLOOR(10 + RAND() * 4) * 5),
(1, 50, FLOOR(10 + RAND() * 4) * 5),
(1, 51, FLOOR(10 + RAND() * 4) * 5),
(1, 52, FLOOR(10 + RAND() * 4) * 5),
(1, 53, FLOOR(10 + RAND() * 4) * 5),
(1, 54, FLOOR(10 + RAND() * 4) * 5),
(1, 55, FLOOR(10 + RAND() * 4) * 5),
(1, 56, FLOOR(10 + RAND() * 4) * 5),
(1, 57, FLOOR(10 + RAND() * 4) * 5),
(1, 58, FLOOR(10 + RAND() * 4) * 5),
(1, 59, FLOOR(10 + RAND() * 4) * 5),
(1, 60, FLOOR(10 + RAND() * 4) * 5);

-- INSERTAR ÓRDENES DE COMPRA
INSERT INTO tb_ordenes_compra (fechaOrden, proveedor_id, totalOrdenCompra, estado_orden_id) VALUES
('2024-09-01', 1, 2999000.00, 22),
('2024-09-02', 2, 1140000.00, 22),
('2024-09-03', 3, 830000.00, 22);

-- INSERTAR DETALLES DE ÓRDENES DE COMPRA
INSERT INTO tb_detalle_orden_compra (orden_compra_id, producto_id, cantidadProducto, precioProducto, subTotalProducto) VALUES
(1, 1, 1, 1169850.00, 1169850.00),
(1, 2, 2, 279900.99, 559801.98),
(1, 3, 3, 154500.99, 463502.97),
(1, 4, 1, 340000.00, 340000.00),
(1, 5, 1, 1499900.50, 1499900.50),

(2, 6, 1, 899900.00, 899900.00),
(2, 7, 2, 229900.99, 459801.98),
(2, 8, 3, 129900.00, 389700.00),
(2, 9, 1, 189900.99, 189900.99),

(3, 10, 1, 459900.50, 459900.50),
(3, 11, 10, 2999.99, 29999.90),
(3, 12, 5, 12900.00, 64500.00),
(3, 13, 2, 4500.00, 9000.00),
(3, 14, 3, 89000.00, 267000.00);

-- ACTUALIZAR ESTADOS DE ÓRDENES DE COMPRA
UPDATE tb_ordenes_compra
SET estado_orden_id = 22
WHERE idOrdenCompra = 1;

UPDATE tb_ordenes_compra
SET estado_orden_id = 22
WHERE idOrdenCompra = 2;

UPDATE tb_ordenes_compra
SET estado_orden_id = 22
WHERE idOrdenCompra = 3;

INSERT INTO tb_caja (nombreCaja, saldoInicialCaja, saldoActualCaja, fechaAperturaCaja, fechaCierreCaja, estado_caja_id)
VALUES 
('Caja FS0001', 1000.00, 1000.00, '2024-09-01 09:00:00', '2024-10-01 19:00:00', 41),
('Caja FS0002', 1500.00, 1500.00, '2024-09-01 09:00:00', '2024-10-01 19:00:00', 41),
('Caja FS0003', 2000.00, 2000.00, '2024-09-01 09:00:00', '2024-10-01 19:00:00', 41),
('Caja FS0004', 2500.00, 2500.00, '2024-09-01 09:00:00', '2024-10-01 19:00:00', 41),
('Caja FS0005', 3000.00, 3000.00, '2024-09-01 09:00:00', '2024-10-01 19:00:00', 41);

INSERT INTO tb_formas_pago (nombreFormaPago, comisionFormaPago) VALUES
('Efectivo', 0),
('Débito', 0),
('Crédito', 0),
('Transferencia', 0),
('MercadoPago', 0);

-- Inserciones en tb_periodos
INSERT INTO tb_periodos (
    nombrePeriodo,
    fechaInicioPeriodo,
    fechaFinPeriodo,
    añoPeriodo,
    estado_periodo_id
) VALUES
('Primavera 2024', '2024-09-01', '2024-11-30', 2024, 1),
('Verano 2024',    '2024-12-01', '2025-02-28', 2024, 1),
('Otoño 2025',     '2025-03-01', '2025-05-31', 2025, 1);
-- =========================
-- FACTURA CLIENTE 1
-- =========================
INSERT INTO tb_factura_cabecera (
    cantidadTotalFaCab,
    fechaDeEmisionFaCab,
    fechaDeVencimientoFaCab,
    montoTotalFaCab,
    cliente_id,
    caja_id,
    sucursal_id,
    forma_pago_id,
    periodo_id,
    estado_factura_id
) VALUES (
    3,
    '2024-09-15 14:30:00',
    '2024-09-22',
    720.00,
    1,
    1,
    1,
    1,
    1,
    3
);

INSERT INTO tb_factura_detalle (
    factura_cabecera_id,
    producto_id,
    cantidadProductoFaDet,
    subTotalFaDet
) VALUES
(1, 1, 2, 200.00),
(1, 2, 1, 150.00),
(1, 3, 3, 370.00);

INSERT INTO tb_historial_ventas_clientes (
    cliente_id,
    factura_cabecera_id,
    fechaCompra
) VALUES
(1, 1, '2024-09-15');


-- =========================
-- FACTURA CLIENTE 2
-- =========================
INSERT INTO tb_factura_cabecera (
    cantidadTotalFaCab,
    fechaDeEmisionFaCab,
    fechaDeVencimientoFaCab,
    montoTotalFaCab,
    cliente_id,
    caja_id,
    sucursal_id,
    forma_pago_id,
    periodo_id,
    estado_factura_id
) VALUES (
    2,
    '2024-10-05 10:15:00',
    '2024-10-12',
    349900.00,
    2,
    1,
    1,
    2,
    1,
    3
);

INSERT INTO tb_factura_detalle (
    factura_cabecera_id,
    producto_id,
    cantidadProductoFaDet,
    subTotalFaDet
) VALUES
(2, 10, 1, 229900.00),
(2, 11, 1, 120000.00);

INSERT INTO tb_historial_ventas_clientes (
    cliente_id,
    factura_cabecera_id,
    fechaCompra
) VALUES
(2, 2, '2024-10-05');


-- =========================
-- FACTURA CLIENTE 3
-- =========================
INSERT INTO tb_factura_cabecera (
    cantidadTotalFaCab,
    fechaDeEmisionFaCab,
    fechaDeVencimientoFaCab,
    montoTotalFaCab,
    cliente_id,
    caja_id,
    sucursal_id,
    forma_pago_id,
    periodo_id,
    estado_factura_id
) VALUES (
    4,
    '2024-11-02 18:40:00',
    '2024-11-09',
    899800.00,
    3,
    2,
    1,
    1,
    2,
    3
);

INSERT INTO tb_factura_detalle (
    factura_cabecera_id,
    producto_id,
    cantidadProductoFaDet,
    subTotalFaDet
) VALUES
(3, 5, 1, 499900.00),
(3, 6, 1, 199900.00),
(3, 7, 1, 150000.00),
(3, 8, 1, 50000.00);

INSERT INTO tb_historial_ventas_clientes (
    cliente_id,
    factura_cabecera_id,
    fechaCompra
) VALUES
(3, 3, '2024-11-02');

INSERT INTO tb_periodos (
    nombrePeriodo,
    fechaInicioPeriodo,
    fechaFinPeriodo,
    añoPeriodo,
    estado_periodo_id
) VALUES
('Anual 2026', '2026-01-01', '2026-12-31', 2026, 1);

DELIMITER //

CREATE PROCEDURE sp_incrementar_stock_sucursal(
    IN p_producto_id INT,
    IN p_sucursal_id INT,
    IN p_cantidad INT
)
BEGIN
    -- Verificar si el producto existe en el inventario de la sucursal
    DECLARE v_stock_existente INT;

    SELECT stockSucursal INTO v_stock_existente
    FROM tb_inventario_sucursal
    WHERE producto_id = p_producto_id AND sucursal_id = p_sucursal_id;

    -- Si existe, actualizamos el stock, si no, insertamos un nuevo registro
    IF v_stock_existente IS NOT NULL THEN
        UPDATE tb_inventario_sucursal
        SET stockSucursal = stockSucursal + p_cantidad
        WHERE producto_id = p_producto_id AND sucursal_id = p_sucursal_id;
    ELSE
        INSERT INTO tb_inventario_sucursal (producto_id, sucursal_id, stockSucursal)
        VALUES (p_producto_id, p_sucursal_id, p_cantidad);
    END IF;
END //

DELIMITER ;

DELIMITER $$

CREATE TRIGGER actualizar_stock_producto_vendido
AFTER INSERT ON tb_factura_detalle
FOR EACH ROW
BEGIN
    DECLARE sucursal_actual INT;

    -- Obtener el ID de la sucursal de la factura cabecera
    SET sucursal_actual = (SELECT sucursal_id FROM tb_factura_cabecera WHERE idFaCab = NEW.factura_cabecera_id);

    -- Actualizar el stock del producto en la sucursal correspondiente
    UPDATE tb_inventario_sucursal
    SET stockSucursal = stockSucursal - NEW.cantidadProductoFaDet
    WHERE sucursal_id = sucursal_actual AND producto_id = NEW.producto_id;
END $$

DELIMITER ;

DELIMITER $$

CREATE TRIGGER after_factura_detalle_insert
AFTER INSERT ON tb_factura_detalle
FOR EACH ROW
BEGIN
    DECLARE periodo_id INT;
    DECLARE cantidadVendida INT;

    -- Paso 1: Obtener el periodo_id basado en la fecha de emisión de la factura
    SELECT p.idPeriodo
    INTO periodo_id
    FROM tb_periodos p
    JOIN tb_factura_cabecera f ON f.idFaCab = NEW.factura_cabecera_id
    WHERE f.fechaDeEmisionFaCab BETWEEN p.fechaInicioPeriodo AND p.fechaFinPeriodo
    LIMIT 1;

    -- Paso 2: Verificar si ya existe un registro para este producto en el mismo periodo
    SELECT IFNULL(SUM(cantidadVendidaPeriodoProducto), 0)
    INTO cantidadVendida
    FROM tb_periodo_productos
    WHERE periodo_id = periodo_id
    AND factura_detalle_id = NEW.idFaDet
    GROUP BY periodo_id, factura_detalle_id;

    -- Paso 3: Insertar o actualizar el registro con la cantidad vendida
    IF cantidadVendida = 0 THEN
        -- Insertar nuevo registro si no existe
        INSERT INTO tb_periodo_productos (periodo_id, factura_detalle_id, cantidadVendidaPeriodoProducto)
        VALUES (periodo_id, NEW.idFaDet, NEW.cantidadProductoFaDet);
    ELSE
        -- Si ya existe un registro, actualizar la cantidad vendida
        UPDATE tb_periodo_productos
        SET cantidadVendidaPeriodoProducto = cantidadVendida + NEW.cantidadProductoFaDet
        WHERE periodo_id = periodo_id AND factura_detalle_id = NEW.idFaDet;
    END IF;
END$$

DELIMITER ;

DELIMITER $$

CREATE TRIGGER trg_insert_historial_cliente
AFTER INSERT ON tb_factura_cabecera
FOR EACH ROW
BEGIN
    -- Verificar si la factura tiene un cliente asociado
    IF NEW.cliente_id IS NOT NULL THEN
        -- Insertar un registro en el historial de compras del cliente
        INSERT INTO tb_historial_ventas_clientes (cliente_id, factura_cabecera_id, fechaCompra)
        VALUES (NEW.cliente_id, NEW.idFaCab, NEW.fechaDeEmisionFaCab);
    END IF;
END $$

DELIMITER ;

DELIMITER $$

CREATE TRIGGER actualizar_saldo_caja 
AFTER INSERT ON tb_factura_cabecera
FOR EACH ROW
BEGIN
    -- Actualizar el saldo actual de la caja activa
    UPDATE tb_caja 
    SET saldoActualCaja = saldoActualCaja + NEW.montoTotalFaCab
    WHERE idCaja = NEW.caja_id 
    AND estado_caja_id = 40; -- Considerando que el estado 40 es 'caja abierta'
END$$

DELIMITER ;

DELIMITER $$

CREATE TRIGGER actualizar_estado_producto 
AFTER UPDATE ON tb_inventario_sucursal
FOR EACH ROW
BEGIN
    -- Verificamos si el stock ha cambiado a un valor menor o igual a cero
    IF NEW.stockSucursal <= 0 THEN
        -- Actualizamos el estado del producto a 27 si el stock es menor o igual a cero
        UPDATE tb_productos
        SET estado_producto_id = 27
        WHERE idProducto = NEW.producto_id;
    END IF;
END $$

DELIMITER ;

DELIMITER $$

CREATE TRIGGER actualizar_estado_disponible
AFTER UPDATE ON tb_inventario_sucursal
FOR EACH ROW
BEGIN
    -- Verificamos si el stock ha aumentado a un valor mayor que cero
    IF NEW.stockSucursal > 0 AND OLD.stockSucursal <= 0 THEN
        UPDATE tb_productos
        SET estado_producto_id = 25 
        WHERE idProducto = NEW.producto_id;
    END IF;
END $$

DELIMITER ;