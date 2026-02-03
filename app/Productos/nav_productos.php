<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="/FormoStock/app/Productos/dashboardProductos.php">Productos</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="me-3">
            <button id="homeButton" class="btn" aria-label="Ir a la página principal">
                <img src="/FormoStock/app/assets/img/irApaginaPrincipal.png" alt="Ir al inicio" style="width: 44px; height: 44px;">
            </button>
        </div>
        <div class="collapse navbar-collapse" id="navbarScroll">
            <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                <!-- Página principal -->
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/FormoStock/app/Productos/dashboardProductos.php">Página principal</a>
                </li>
                <!-- Nuevo producto -->
                <li class="nav-item">
                    <a class="nav-link" href="/FormoStock/app/Productos/crearProducto.php">Crear producto</a>
                </li>
                <!-- Actualizar precios masivamente -->
                <li class="nav-item">
                    <a class="nav-link" href="/FormoStock/app/Productos/vw_actualizar_precios_x_categoria.php">Actualizar precios masivamente</a>
                </li>
                <!-- Exportar listado de productos -->
                <li class="nav-item">
                    <a class="nav-link" href="/FormoStock/app/Productos/exportarListaProductos.php">Exportar listado de productos</a>
                </li>
                <!-- Ajustes de inventario -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Ajustes de inventario
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="/FormoStock/app/Productos/Categoría/tb_categoria.php">Categoría</a></li>
                        <li><a class="dropdown-item" href="/FormoStock/app/Productos/Marca/tb_marca.php">Marca</a></li>
                        <li><a class="dropdown-item" href="/FormoStock/app/Productos/TipoNota/tb_tipo_nota.php">Ajuste contable</a></li>
                    </ul>
                </li>
            </ul>
            <div id="nav-buttons-container" class="ms-auto d-flex align-items-center"></div>
        </div>
    </div>
    <script src="/FormoStock/app/assets/js/buttonOnTheRight.js"></script>
</nav>
<script>
    document.getElementById('homeButton').addEventListener('click', function() {
        window.location.href = '/FormoStock/app/paginaPrincipal.php'; 
    });
</script>
