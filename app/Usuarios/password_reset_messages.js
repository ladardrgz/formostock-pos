document.addEventListener("DOMContentLoaded", function () {
    // Obtener el formulario
    const form = document.querySelector("form");

    form.addEventListener("submit", function (e) {
        e.preventDefault(); // Evitar el envío tradicional del formulario

        const formData = new FormData(form);

        // Enviar la solicitud usando Fetch API
        fetch(form.action, {
            method: "POST",
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                // Manejar la respuesta según el estado
                if (data.status === "success") {
                    Swal.fire({
                        title: "¡Éxito!",
                        text: data.message,
                        icon: "success"
                    }).then(() => {
                        // Redireccionar si se especifica
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        }
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: data.message,
                        icon: "error"
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: "Error",
                    text: "Hubo un problema con la solicitud. Inténtalo de nuevo.",
                    icon: "error"
                });
                console.error("Error:", error);
            });
    });
});
