---
[EN] 
FormoStock
Point of sale (POS) system with Inventory Management for IT Supplies

# Technical Overview #

FormoStock is a web-based point of sale (POS) system designed for comprehensive management of inventory, sales, and commercial operations related to IT supplies.

This project represents my first complete software project, developed individually and covering all stages of the software development lifecycle, including requirements analysis, system design, database modeling, implementation, test data loading, and functional validation.

The system was built with a scalable architecture, allowing future expansion and the addition of new modules.

# Implemented features #

Inventory Management

Product creation, update, and maintenance.

Real-time stock level control.

Low-inventory alerts.

Supplier Management

Supplier registration and data maintenance.

Purchase order management.

Product reception handling.

Purchase Orders

Creation and management of purchase orders.

Automatic inventory updates upon product reception.

Return Management

Product return registration.

Automatic stock adjustment after returns.

Sales and Cash Register

Sales transaction recording.

Cash register control.

Income reconciliation.

Customer Management

Customer registration and maintenance.

Purchase history tracking.

Customer preferences storage.

Employees and Security

Employee management.

Role and permission assignment.

User authentication.

Role-Based Access Control (RBAC).

Data validation and secure credential handling using password hashing.

# Technologies used #

PHP (Backend)

JavaScript (Client-side logic)

HTML5

CSS3

Bootstrap 5

Relational database, designed from scratch

# Project structure #

/docs
Contains the software requirements specification (SRS) document used as the foundation for system development.

/bd
Database scripts and test data loaders.

/app 
Application source code.

# Installation and Testing Guide #

Prerequisites

Local web server (XAMPP, WAMP, Laragon, or similar).

PHP enabled.

MySQL or compatible database engine.

# Step-by-Step Setup #

Download the project
Download the compressed (.zip) file from this repository.

Extract the files
Unzip the downloaded file.

Copy to your web server directory
Place the project folder inside your web server root directory, for example:

htdocs (XAMPP)

www (WAMP)

Configure database connection

Open the database configuration file.

Verify or update database credentials, host, port, and database name if you are using a non-default setup.

Load the database

Access the database installation script.

Execute it with a single click to automatically create the database schema and load test data.

Run the system

Open the project URL in your browser: http://localhost/FormoStock

Log in using the test credentials provided below.

Test Credentials

Username: admin

Password: admin

Role: Administrator (full system access)

These credentials are provided for testing purposes only. The system includes proper validations, role-based access control, and secure password hashing.

# Technical Notes #

Database fully designed and normalized from scratch.

Test data preloaded to facilitate system evaluation.

Modular and scalable architecture.

Code organized by functional modules.

Author

Developed entirely by Lada Elizabet Rodriguez

----

[ES]

FormoStock
Sistema de Punto de Venta (POS) con Gestión de Stock para Insumos Informáticos

# Technical Overview #

FormoStock es un sistema web de Punto de Venta (POS) diseñado para la gestión integral de inventario, ventas y operaciones comerciales relacionadas con insumos informáticos.

Este proyecto representa mi primer proyecto de software completo, desarrollado de forma individual y abarcando todas las etapas del ciclo de vida del software, incluyendo análisis de requisitos, diseño del sistema, modelado de base de datos, implementación, carga de datos de prueba y validación funcional.

El sistema fue construido con una arquitectura escalable, lo que permite futuras ampliaciones y la incorporación de nuevos módulos.

# Implemented features #

Inventory Management

Alta, actualización y mantenimiento de productos.

Control de niveles de stock en tiempo real.

Alertas por bajo nivel de inventario.

Supplier Management

Registro y mantenimiento de proveedores.

Gestión de órdenes de compra.

Gestión de recepción de productos.

Purchase Orders

Creación y gestión de órdenes de compra.

Actualización automática del inventario al recibir productos.

Return Management

Registro de devoluciones de productos.

Ajuste automático del stock tras las devoluciones.

Sales and Cash Register

Registro de transacciones de venta.

Control de caja.

Conciliación de ingresos.

Customer Management

Registro y mantenimiento de clientes.

Seguimiento del historial de compras.

Almacenamiento de preferencias de clientes.

Employees and Security

Gestión de empleados.

Asignación de roles y permisos.

Autenticación de usuarios.

Control de acceso basado en roles (RBAC).

Validación de datos y manejo seguro de credenciales mediante hasheo de contraseñas.

# Technologies used #

PHP (Backend)

JavaScript (Lógica del lado del cliente)

HTML5

CSS3

Bootstrap 5

Base de datos relacional, diseñada desde cero

# Project Structure #
/docs
  Documento de Especificación de Requisitos de Software (SRS).

/bd
  Scripts de base de datos y carga de datos de prueba.

/app
  Código fuente de la aplicación.

Installation and Testing Guide

Prerequisites

Servidor web local (XAMPP, WAMP, Laragon o similar).

PHP habilitado.

Motor de base de datos MySQL o compatible.

# Step-by-Step Setup #

Descargar el proyecto
Descargar el archivo comprimido (.zip) desde este repositorio.

Extraer los archivos
Descomprimir el archivo descargado.

Copiar al directorio del servidor web
Colocar la carpeta del proyecto dentro del directorio raíz del servidor web, por ejemplo:

htdocs (XAMPP)

www (WAMP)

Configurar la conexión a la base de datos

Abrir el archivo de configuración de la base de datos.

Verificar o actualizar credenciales, host, puerto y nombre de la base de datos si se utiliza una configuración no estándar.

Cargar la base de datos

Acceder al script de instalación de la base de datos.

Ejecutarlo con un solo clic para crear automáticamente el esquema y cargar los datos de prueba.

Ejecutar el sistema

Abrir el proyecto en el navegador:
http://localhost/FormoStock

Iniciar sesión utilizando las credenciales de prueba indicadas a continuación.

Test Credentials

Usuario: admin

Contraseña: admin

Rol: Administrador (acceso completo al sistema)

Estas credenciales se proporcionan únicamente con fines de prueba. El sistema implementa validaciones, control de acceso por roles y hasheo seguro de contraseñas.

# Technical Notes #

Base de datos completamente diseñada y normalizada desde cero.

Datos de prueba precargados para facilitar la evaluación del sistema.

Arquitectura modular y escalable.

Código organizado por módulos funcionales.

Author

Desarrollado íntegramente por Lada Elizabet Rodriguez
