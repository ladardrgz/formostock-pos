 <?php
if (!defined('ROOT')) {
    define('ROOT', __DIR__ . '/../');
}
require_once ROOT . 'modelos/permisos.php';
?>

<!-- Menú de navegación -->
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="paginaPrincipal.php">
            <img src="assets/img/newLogo-FormoStock.png" width="50px" height="43px">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Inicio -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="paginaPrincipal.php">Inicio</a>
                </li>

                <!-- Usuarios -->
                <li class="nav-item dropdown">
                    <?php if (in_array('Usuarios', $permisos)) { ?>
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Usuarios
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="Usuarios/dashboardUsuarios.php">Abrir sección</a></li>
                            <hr class="dropdown-divider">
                            <li><a class="dropdown-item" href="Usuarios/crearUsuario.php">Crear usuario</a></li>
                            <li><a class="dropdown-item" href="Usuarios/exportarUsuariosExc.php">Exportar lista de usuarios</a></li>
                            <hr class="dropdown-divider">
                            <li><a class="dropdown-item" href="Usuarios/visualizarPermisos.php">Configurar permisos</a></li>
                            <li><a class="dropdown-item" href="Usuarios/verPerfil.php">Gestionar tu cuenta de FormoStock</a></li>
                        </ul>
                    <?php } ?>
                </li>

                <!-- Clientes -->
                <li class="nav-item dropdown">
                    <?php if (in_array('Clientes', $permisos)) { ?>
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Clientes
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="Clientes/dashboardClientes.php">Abrir sección</a></li>
                            <hr class="dropdown-divider">
                            <li><a class="dropdown-item" href="Clientes/crearCliente.php">Crear cliente</a></li>
                            <li><a class="dropdown-item" href="Clientes/exportarClientesExc.php">Exportar lista de clientes</a></li>
                            <li><a class="dropdown-item" href="Clientes/reporteClientes.php">Reporte de cliente que más compró</a></li>
                        </ul>
                    <?php } ?>
                </li>

                <!-- Productos -->
                <li class="nav-item dropdown">
                    <?php if (in_array('Productos', $permisos)) { ?>
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Productos
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="Productos/dashboardProductos.php">Abrir sección</a></li>
                            <hr class="dropdown-divider">
                            <li><a class="dropdown-item" href="Productos/crearProducto.php">Crear producto</a></li>
                            <li><a class="dropdown-item" href="Productos/vw_actualizar_precios_x_categoria.php">Actualizar precios masivamente</a></li>
                            <li><a class="dropdown-item" href="Productos/ajusteStock.php">Ajustar stock</a></li>
                            <li><a class="dropdown-item" href="Productos/exportarListaProductos.php">Generar listado de productos</a></li>
                            <li><a class="dropdown-item" href="Productos/reporteProducto.php">Productos más vendido por período</a></li>
                            <li><a class="dropdown-item" href="Productos/Categoría/tb_categoria.php">Crear categoría</a></li>
                            <li><a class="dropdown-item" href="Productos/Marca/tb_marca.php">Agregar marca</a></li>
                            <li><a class="dropdown-item" href="Productos/TipoNota/tb_tipo_nota.php">Agregar nuevo ajuste contable</a></li>
                        </ul>
                    <?php } ?>
                </li>

                <!-- Caja -->
                <li class="nav-item dropdown">
                    <?php if (in_array('Caja', $permisos)) { ?>
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Caja
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="Caja/InicioCaja.php">Abrir sección</a></li>
                            <hr class="dropdown-divider">
                            <li><a class="dropdown-item" href="Caja/CrearCaja.php">Crear caja</a></li>
                            <li><a class="dropdown-item" href="Caja/administrarCaja.php">Administrar caja</a></li>
                            <li><a class="dropdown-item" href="Caja/#.php">Cerrar turno</a></li>
                            <li><a class="dropdown-item" href="Caja/arqueoCaja.php">Realizar arqueo de caja</a></li>
                        </ul>
                    <?php } ?>
                </li>

                <!-- Ventas -->
                <li class="nav-item dropdown">
                    <?php if (in_array('Ventas', $permisos)) { ?>
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Ventas
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="Ventas/dashboardVentas.php">Sección | Ventas</a></li>
                            <hr class="dropdown-divider">
                            <li><a class="dropdown-item" href="Ventas/nuevaTransaccion.php">Nueva venta</a></li>
                            <hr class="dropdown-divider">
                            <li><a class="dropdown-item" href="Devoluciones/dashboardDevoluciones.php">Sección | Devoluciones</a></li>
                        </ul>
                    <?php } ?>
                </li>

                <!-- Proveedores -->
                <li class="nav-item dropdown">
                    <?php if (in_array('Proveedores', $permisos)) { ?>
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Proveedores
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="Proveedores/dashboardProveedores.php">Abrir sección</a></li>
                            <hr class="dropdown-divider">
                            <li><a class="dropdown-item" href="Proveedores/crearProveedor.php">Crear proveedor</a></li>
                            <li><a class="dropdown-item" href="Proveedores/exportarProveedoresExc.php">Exportar lista de proveedores</a></li>
                            <li><a class="dropdown-item" href="Proveedores/reporteProveedores.php">Reporte de proveedores más solicitados</a></li>
                            <hr class="dropdown-divider">
                            <li><a class="dropdown-item" href="Proveedores/ordenesDeCompra.php">Sección | Órdenes de compra</a></li>
                            <li><a class="dropdown-item" href="Proveedores/reabastecimiento.php">Generar órden de reabastecimiento</a></li>
                        </ul>
                    <?php } ?>
                </li>

                <!-- Sucursales -->
                <li class="nav-item dropdown">
                    <?php if (in_array('Sucursales', $permisos)) { ?>
                        <a class="nav-link dropdown-toggle" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false"></i>
                            Sucursales
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="Sucursales/InicioSucursal.php">Abrir sección</a></li>
                            <hr class="dropdown-divider">
                            <li><a class="dropdown-item" href="Sucursales/registro_sucursal.php">Crear sucursal</a></li>
                            <li><a class="dropdown-item" href="Sucursales/aperturaSucursalOperativa.php">Administrar sucursal</a></li>
                        </ul>
                    <?php } ?>
                </li>

                <!-- Configuración -->
                <li class="nav-item dropdown">
                    <?php if (in_array('Configuración', $permisos)) { ?>
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Configuración
                        </a>

                        <ul class="dropdown-menu">

                            <li><a class="dropdown-item" href="Configuración/Dir/tb_barrios.php">Direcciones y ubicaciones</a></li>

                            <li><a class="dropdown-item" href="Configuración/Doc/tb_tipo_documento.php">Gestión de documentos</a></li>

                            <li><a class="dropdown-item" href="Configuración/Inf/tb_tipoContacto.php">Gestión de medios de contacto</a></li>

                            <li><a class="dropdown-item" href="Configuración/Est/tb_estados_logicos.php">Administración de estados</a></li>

                            <li><a class="dropdown-item" href="Configuración/Per/tb_periodo.php">Eventos de reporte</a></li>

                            <li><a class="dropdown-item" href="Configuración/Imp/tb_impuesto.php">Control de impuestos</a></li>
                        </ul>
                    <?php } ?>
                </li>

                <!-- Notificaciones -->
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" role="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell-fill notification-icon"></i>
                        <span class="badge bg-danger">0</span> <!-- Contador de notificaciones -->
                    </a>

                    <ul class="dropdown-menu custom-notification-dropdown dropdown-menu-end" aria-labelledby="notificationDropdown">
                        <li><a class="dropdown-item" href="#">No hay notificaciones</a></li>
                        <hr class="dropdown-divider">
                        <li><a class="dropdown-item" href="#">Ver todas las notificaciones</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>