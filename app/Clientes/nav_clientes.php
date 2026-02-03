<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="client_vw.php">Clientes</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="me-3">
            <button id="homeButton" class="btn" aria-label="Ir a la página principal">
                <img src="../assets/img/irApaginaPrincipal.png" alt="Ir al inicio" style="width: 44px; height: 44px;">
            </button>
        </div>
        <div class="collapse navbar-collapse" id="navbarScroll">
            <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="dashboardCLientes.php">Página principal</a>
                </li>
                <!-- Nuevo cliente -->
                <li class="nav-item">
                    <a class="nav-link" href="crearCliente.php">Nuevo cliente</a>
                </li>
                <!-- Exportar listado -->
                <li class="nav-item">
                    <a class="nav-link" href="exportarClientesExc.php">Exportar lista de clientes</a>
                </li>
                <!-- Reporte de clientes que más compraron -->
                <li class="nav-item">
                    <a class="nav-link" href="reporteClientes.php">Reporte de clientes que más compró</a>
                </li>
            </ul>
            <div id="nav-buttons-container" class="ms-auto d-flex align-items-center"></div>
        </div>
    </div>
    </div>
</nav>
<script src="ButtonOnTheRight.js"></script>
<script>
    document.getElementById('homeButton').addEventListener('click', function() {
        window.location.href = '../paginaPrincipal.php'; 
    });
</script>