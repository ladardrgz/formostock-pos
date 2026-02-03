<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="/FormoStock/app/Proveedores/dashboardProveedores.php">Proveedor</a>
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
                    <a class="nav-link active" aria-current="page" href="/FormoStock/app/Proveedores/dashboardProveedores.php">Página principal</a>
                </li>
                <!-- Nuevo proveedor -->
                <li class="nav-item">
                    <a class="nav-link" href="/FormoStock/app/Proveedores/crearProveedor.php">Nuevo proveedor</a>
                </li>
                <!-- Exportar listado de proveedores -->
                <li class="nav-item">
                    <a class="nav-link" href="/FormoStock/app/Proveedores/exportarProveedoresExc.php">Exportar lista de proveedores</a>
                </li>
                <!-- Órdenes de compra -->
                <li class="nav-item">
                    <a class="nav-link" href="/FormoStock/app/Proveedores/ordenesDeCompra.php">Órdenes de compra</a>
                </li>
                <!-- Reporte de proveedor más solicitado -->
                <li class="nav-item">
                    <a class="nav-link" href="/FormoStock/app/Proveedores/reporteProveedores.php">Reporte de proveedores más solicitados</a>
                </li>
            </ul>
            <div id="nav-buttons-container" class="ms-auto d-flex align-items-center"></div>
        </div>
    </div>
    <script src="/FormoStock/app/assets/js/buttonOnTheRight.js"></script>
</nav>
<script>
    document.getElementById('homeButton').addEventListener('click', function() {
        window.location.href = '/FormoStock/app/paginaPrincipal.php'; // Redirige a la página principal
    });
</script>