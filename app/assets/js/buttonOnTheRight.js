document.addEventListener('DOMContentLoaded', () => {
    // Obtener el contenedor existente para los botones
    const container = document.getElementById('nav-buttons-container');
    
    // Asegurar que el contenedor esté configurado para flexbox
    container.style.display = 'flex';
    container.style.pointerEvents = 'none'; // Evitar interferencias en el contenedor

    // Crear el botón de retroceso
    const backButton = document.createElement('img');
    backButton.src = `${window.location.origin}/FormoStock/app/assets/img/flechaIzquierda.png`; // Ruta absoluta
    backButton.style.width = '35px'; // Tamaño del botón
    backButton.style.height = '35px'; // Tamaño del botón
    backButton.style.cursor = 'pointer';
    backButton.style.pointerEvents = 'auto'; // Asegura que el botón sea clicable
    backButton.alt = 'Retroceder';
    backButton.addEventListener('click', () => {
        window.history.back();
    });

    // Crear el botón de avance
    const forwardButton = document.createElement('img');
    forwardButton.src = `${window.location.origin}/FormoStock/app/assets/img/flechaDerecha.png`; // Ruta absoluta
    forwardButton.style.width = '35px'; // Tamaño del botón
    forwardButton.style.height = '35px'; // Tamaño del botón
    forwardButton.style.marginLeft = '10px'; // Espacio entre los botones
    forwardButton.style.cursor = 'pointer';
    forwardButton.style.pointerEvents = 'auto'; // Asegura que el botón sea clicable
    forwardButton.alt = 'Avanzar';
    forwardButton.addEventListener('click', () => {
        window.history.forward();
    });

    // Añadir botones al contenedor
    container.appendChild(backButton);
    container.appendChild(forwardButton);
});
