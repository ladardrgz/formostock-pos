function cambiarVisibilidadContraseña(inputId, toggleButton) {
    const input = document.getElementById(inputId);
    const type = input.type === "password" ? "text" : "password";
    input.type = type;
    toggleButton.querySelector('i').classList.toggle('fa-eye');
    toggleButton.querySelector('i').classList.toggle('fa-eye-slash');
}

function limpiarMensajesErrores() {
    const mensajesErrores = document.querySelectorAll('.errorValidacion');
    mensajesErrores.forEach(mensaje => mensaje.remove());
}

function validacionUsuarioAlert(elemento, mensaje) {
    const errorDivs = elemento.parentNode.querySelectorAll('.errorValidacion');
    errorDivs.forEach(div => div.remove());

    const errorDiv = document.createElement('div');
    errorDiv.className = 'errorValidacion';
    errorDiv.innerText = mensaje;
    elemento.parentNode.appendChild(errorDiv);
}

function validarEmail(email) {
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return emailPattern.test(email);
}

function validarTelefono(telefono) {
    const telefonoPattern = /^\d{10}$/; 
    return telefonoPattern.test(telefono);
}

function agregarEventosDeValidacion() {
    const inputs = [
        'nombres', 'apellidos', 'fechaNacimiento', 'sexo', 'tipo_documento_id',
        'valorDocumento', 'tipo_contacto_id', 'valorDetalleContacto', 'pais',
        'provincia', 'localidad', 'barrio', 'descripcionDomicilio', 'nombreCuentaUsuario',
        'emailUsuario', 'contraseñaUsuario', 'repetirContraseña', 'rol_id'
    ];

    inputs.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('input', () => {
                limpiarMensajesErrores(); 
                validarFormularioEnTiempoReal();
            });
        }
    });
}

async function verificarDocumento(valorDocumento, tipoDocumentoId) {
    try {
        const response = await fetch('verificarDocumento.php', {
            method: 'POST',
            body: JSON.stringify({ valorDocumento, tipoDocumentoId }),
            headers: {
                'Content-Type': 'application/json'
            }
        });
        const data = await response.json();
        if (data.existe) {
            validacionUsuarioAlert(valorDocumento, "El valor del documento ya está registrado.");
            return false; 
        }
        return true; 
    } catch (error) {
        console.error("Error al verificar el documento:", error);
        validacionUsuarioAlert(valorDocumento, "Hubo un problema al verificar el documento.");
        return false; // Error en la verificación, no es válido
    }
}

async function validarFormularioEnTiempoReal() {
    let valido = true;

    const campos = [
        'nombres', 'apellidos', 'fechaNacimiento', 'sexo', 'tipo_documento_id',
        'valorDocumento', 'tipo_contacto_id', 'valorDetalleContacto', 'pais',
        'provincia', 'localidad', 'barrio', 'descripcionDomicilio', 'nombreCuentaUsuario',
        'emailUsuario', 'contraseñaUsuario', 'repetirContraseña', 'rol_id'
    ];

    for (let id of campos) {
        const elemento = document.getElementById(id);
        if (!elemento) continue;

        switch (id) {
            case 'nombres':
                if (elemento.value.trim() === '' || elemento.value.length > 50) {
                    validacionUsuarioAlert(elemento, "El nombre no puede estar vacío y debe tener un máximo de 50 caracteres.");
                    valido = false;
                }
                break;
            case 'apellidos':
                if (elemento.value.trim() === '' || elemento.value.length > 50) {
                    validacionUsuarioAlert(elemento, "El apellido no puede estar vacío y debe tener un máximo de 50 caracteres.");
                    valido = false;
                }
                break;
            case 'fechaNacimiento':
                const hoy = new Date();
                const fechaLimiteInferior = new Date(hoy.getFullYear() - 18, hoy.getMonth(), hoy.getDate());
                const fechaLimiteSuperior = new Date(hoy.getFullYear() - 100, hoy.getMonth(), hoy.getDate());

                if (!elemento.value) {
                    validacionUsuarioAlert(elemento, "La fecha de nacimiento es requerida.");
                    valido = false;
                } else {
                    const fechaNacimientoValor = new Date(elemento.value);
                    fechaNacimientoValor.setHours(0, 0, 0, 0);

                    if (fechaNacimientoValor > fechaLimiteInferior) {
                        validacionUsuarioAlert(elemento, "Debes ser mayor de edad.");
                        valido = false;
                    } else if (fechaNacimientoValor < fechaLimiteSuperior) {
                        validacionUsuarioAlert(elemento, "La fecha de rango es inválido.");
                        valido = false;
                    }
                }
                break;
            case 'emailUsuario':
                if (!validarEmail(elemento.value)) {
                    validacionUsuarioAlert(elemento, "El correo electrónico es obligatorio.");
                    valido = false;
                }
                break;
            case 'valorDetalleContacto':
                if (!validarTelefono(elemento.value)) {
                    validacionUsuarioAlert(elemento, "Ingrese un número de teléfono válido (10 dígitos).");
                    valido = false;
                }
                break;
            case 'valorDocumento':
                if (!elemento.value) {
                    validacionUsuarioAlert(elemento, "El valor del documento no puede estar vacío.");
                    valido = false;
                } else {
                    const documentoValido = await verificarDocumento(elemento.value, document.getElementById('valorDocumento').value);
                    if (!documentoValido) {
                        valido = false;
                    }
                }
                break;
            case 'contraseñaUsuario':
                if (elemento.value.length < 8) {
                    validacionUsuarioAlert(elemento, "La contraseña debe tener al menos 8 caracteres.");
                    valido = false;
                }
                break;
            case 'repetirContraseña':
                if (elemento.value !== document.getElementById('contraseñaUsuario').value) {
                    validacionUsuarioAlert(elemento, "Las contraseñas no coinciden.");
                    valido = false;
                }
                break;
            case 'rol_id':
                if (elemento.value === '') {
                    validacionUsuarioAlert(elemento, "El rol es obligatorio.");
                    valido = false;
                }
                break;
            default:
                if (elemento.value === '') {
                    validacionUsuarioAlert(elemento, "Este campo es obligatorio.");
                    valido = false;
                }
                break;
        }
    }

    return valido;
}

agregarEventosDeValidacion();

document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('#formUsuario').addEventListener('submit', async function (event) {
        event.preventDefault();

        if (await validarFormularioEnTiempoReal()) {
            const formData = new FormData(this);

            try {
                const response = await fetch('recibirUser.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: data.message,
                        confirmButtonText: 'Aceptar',
                        confirmButtonColor: '#67f120'
                    }).then(() => {
                        window.location.href = "dashboardUsuarios.php"; 
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error!',
                        text: data.message,
                        confirmButtonText: 'Aceptar',
                        confirmButtonColor: '#67f120'
                    });
                }
            } catch (error) {
                console.error('Error en la solicitud:', error);
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    text: 'Hubo un problema con la solicitud.',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#67f120'
                });
            }
        }
    });
});
