<?php
include 'app/modelos/iniciarSesion.php'; 
?>
<!doctype html>
<html lang="es" data-bs-theme="auto">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.118.2">
    <link rel="icon" type="image/x-icon" href="app/assets/img/IconoLog.ico">
    <title>Inicio de sesión | FormoStock</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <script src="app/assets/js/color-modes.js"></script>
    <link href="app/assets/css/sign-in.css" rel="stylesheet">
</head>

<body class="d-flex align-items-center py-4 bg-body-tertiary">
    <div class="dropdown position-fixed bottom-0 end-0 mb-3 me-3 bd-mode-toggle">
        <button class="btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
            <svg class="bi my-1 theme-icon-active" width="1em" height="1em">
                <use href="#circle-half"></use>
            </svg>
            <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
            <li>
                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                    <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em">
                        <use href="#sun-fill"></use>
                    </svg>
                    Claro
                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                        <use href="#check2"></use>
                    </svg>
                </button>
            </li>
            <li>
                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                    <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em">
                        <use href="#moon-stars-fill"></use>
                    </svg>
                    Oscuro
                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                        <use href="#check2"></use>
                    </svg>
                </button>
            </li>
            <li>
                <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">
                    <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em">
                        <use href="#circle-half"></use>
                    </svg>
                    Predeterminado
                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                        <use href="#check2"></use>
                    </svg>
                </button>
            </li>
        </ul>
    </div>

    <main class="form-signin w-100 m-auto">
        <form action="" method="post">
            <div style="text-align: center;">
                <img src="app/assets/img/newLogo-FormoStock.png" alt="Logo claro" class="logo logo-light">
                <img src="app/assets/img/newLogo-FormoStock-ModeClaro.png" alt="Logo oscuro" class="logo logo-dark">
                <h1 class="display-4 text-center nombre-de-la-app">FormoStock</h1>
                <h3 class="sistema">Sistema de punto de venta
                POS</h3>
                <h2 class="h3 mb-3 fw-normal">Inicio de sesión</h2>
            </div>
            <div class="form-floating">
                <input type="text" class="form-control" id="floatingInput" name="nombreCuentaUsuario" placeholder="Usuario">
                <label for="floatingInput">Usuario o correo electrónico</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="floatingPassword" name="contraseñaUsuario" placeholder="Contraseña">
                <label for="floatingPassword">Contraseña</label>
                <?php if (!empty($alert)): ?>
                    <div class="alert <?php echo $alert_class; ?>">
                        <?php echo $alert; ?>
                    </div>
                <?php endif; ?>
            </div>
            <button class="btn btn-custom w-100 py-2" type="submit">Ingresar</button>

            <div class="forgot-password">
                <a href="app/PHPMailer/src/restablecer_contraseña.php">¿Olvidaste tu contraseña?</a>
            </div>

        </form>
    </main>

    <!-- Incluir SweetAlert antes del código PHP -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</body>

</html>
