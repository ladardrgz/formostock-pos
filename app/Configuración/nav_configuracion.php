<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Configuración</a>
        
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
                <li class="nav-item">
                    <a class="nav-link" href="/FormoStock/app/Configuración/Dir/tb_barrios.php">Direcciones y ubicaciones</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/FormoStock/app/Configuración/Doc/tb_tipo_documento.php">Gestión de documentos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/FormoStock/app/Configuración/Inf/tb_tipoContacto.php">Gestión de medios de contacto</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/FormoStock/app/Configuración/Est/tb_estados_logicos.php">Administración de estados</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/FormoStock/app/Configuración/Per/tb_periodo.php">Eventos de reporte</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/FormoStock/app/Configuración/Imp/tb_impuesto.php">Control de impuestos</a>
                </li>
            </ul>

            <div id="nav-buttons-container" class="ms-auto d-flex align-items-center"></div>
        </div>
    </div>
</nav>

<script src="/FormoStock/app/assets/js/buttonOnTheRight.js"></script>

<script>
    document.getElementById('homeButton').addEventListener('click', function() {
        window.location.href = '/FormoStock/app/paginaPrincipal.php'; 
    });
</script>