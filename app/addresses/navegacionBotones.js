// Crear un contenedor para los botones
const container = document.createElement('div');
container.style.position = 'fixed';
container.style.bottom = '10px'; // Margen inferior
container.style.left = '0';
container.style.width = '100%';
container.style.height = 'auto'; // Ajuste automático a la altura de los botones
container.style.display = 'flex'; // Usar Flexbox para alinear los botones
container.style.justifyContent = 'center'; // Centrar los botones horizontalmente
container.style.pointerEvents = 'none'; // Asegura que los botones no interfieran con otros elementos

// Crear el botón de retroceso
const backButton = document.createElement('img');
backButton.src = '../assets/img/flechaIzquierda.png'; // Asegúrate de que la ruta sea correcta
backButton.style.width = '40px'; // Tamaño del botón
backButton.style.height = '40px'; // Tamaño del botón
backButton.style.marginRight = '10px'; // Espacio entre los botones
backButton.style.cursor = 'pointer';
backButton.style.pointerEvents = 'auto'; // Asegura que el botón sea clicable
backButton.alt = 'Retroceder';
backButton.addEventListener('click', () => {
    window.history.back();
});

// Crear el botón de avance
const forwardButton = document.createElement('img');
forwardButton.src = '../assets/img/flechaDerecha.png'; // Asegúrate de que la ruta sea correcta
forwardButton.style.width = '40px'; // Tamaño del botón
forwardButton.style.height = '40px'; // Tamaño del botón
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

// Añadir el contenedor al documento
document.body.appendChild(container);
