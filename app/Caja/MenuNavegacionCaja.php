<!-- MenuNavegacionCaja.php -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="">Caja</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
                <!-- Contenedor para el botón de la casita (ubicado a la izquierda) -->
                <div class="me-3">
            <button id="homeButton" class="btn" aria-label="Ir a la página principal">
                <img src="../assets/img/irApaginaPrincipal.png" alt="Ir al inicio" style="width: 44px; height: 44px;">
            </button>
        </div>
        <div class="collapse navbar-collapse" id="navbarScroll">
            <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="">Página principal</a>
                </li>
                <!-- Registrar caja -->
                <li class="nav-item">
                    <a class="nav-link" href="CrearCaja.php">Registrar caja</a>
                </li>
                <!-- Abrir caja -->
                <li class="nav-item">
                    <a class="nav-link" href="AbrirCaja.php">Abrir caja</a>
                </li>
                <!-- Arqueo de caja -->
                <li class="nav-item">
                    <a class="nav-link" href="arqueoCaja.php">Realizar arqueo de caja</a>
                </li>
            </ul>
            <!-- Contenedor para los botones de navegación (ubicado a la derecha) -->
            <div id="nav-buttons-container" class="ms-auto d-flex align-items-center"></div>
        </div>
    </div>
    </div>
</nav>
<!-- Incluir el script justo antes del cierre del body -->
<script src="ButtonOnTheRight.js"></script>
<script>
    // Funcionalidad del botón de la casita
    document.getElementById('homeButton').addEventListener('click', function() {
        window.location.href = '../paginaPrincipal.php'; // Redirige a la página principal
    });
</script>