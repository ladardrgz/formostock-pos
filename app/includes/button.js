
const container = document.getElementById('nav-buttons-container');
container.style.display = 'flex'; 
container.style.pointerEvents = 'none'; 

// Botón de retroceso
const backButton = document.createElement('img');
backButton.src = '/FormoStock/app/assets/img/flechaIzquierda.png';
backButton.style.width = '35px';
backButton.style.height = '35px';
backButton.style.cursor = 'pointer';
backButton.style.pointerEvents = 'auto'; 
backButton.alt = 'Retroceder';
backButton.addEventListener('click', () => {
    window.history.back();
});

// Botón de avance
const forwardButton = document.createElement('img');
forwardButton.src = '/FormoStock/app/assets/img/flechaDerecha.png'; 
forwardButton.style.width = '35px';
forwardButton.style.height = '35px'; 
forwardButton.style.marginLeft = '10px'; 
forwardButton.style.cursor = 'pointer';
forwardButton.style.pointerEvents = 'auto'; 
forwardButton.alt = 'Avanzar';
forwardButton.addEventListener('click', () => {
    window.history.forward();
});

// Añado los botones al contenedor
container.appendChild(backButton);
container.appendChild(forwardButton);
