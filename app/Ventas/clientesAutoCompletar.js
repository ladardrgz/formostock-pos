// Arreglo para almacenar los clientes seleccionados
let clientesSeleccionados = []; 
// Variable para almacenar el índice del cliente seleccionado
let clienteSeleccionadoIndex = -1; 

// Función asíncrona para filtrar clientes
async function filtrarClientes() {
    // Obtener el valor del input de búsqueda
    const input = document.getElementById('buscador').value;
    // Obtener el contenedor donde se mostrarán los resultados
    const resultado = document.getElementById('resultado-clientes');
    resultado.innerHTML = ''; // Limpiar resultados previos
    clienteSeleccionadoIndex = -1; // Reiniciar índice de cliente seleccionado

    // Validar que la longitud del input sea al menos 3 caracteres
    if (input.length < 3) {
        resultado.innerHTML = '<li>Escribe al menos 3 caracteres para buscar.</li>';
        return;
    }

    try {
        // Realizar la búsqueda de clientes a través de una solicitud fetch
        const response = await fetch(`buscador_clientes.php?query=${encodeURIComponent(input)}`);
        const clientes = await response.json(); // Convertir la respuesta a JSON
        clientesSeleccionados = clientes; // Almacenar clientes seleccionados

        // Iterar sobre los clientes obtenidos y crear elementos de lista
        clientes.forEach((cliente, index) => {
            const li = document.createElement('li');
            li.textContent = `Cliente: ${cliente.nombreCompleto} - ${cliente.tipoDocumento} ${cliente.documento}`;
            // Asignar la función de selección al hacer clic
            li.onclick = () => seleccionarCliente(cliente.idCliente, cliente.nombreCompleto, cliente.tipoDocumento, cliente.documento);
            li.tabIndex = 0; // Hacer que el elemento sea accesible mediante el teclado

            // Resaltar el elemento al pasar el mouse
            li.addEventListener('mouseenter', () => {
                clienteSeleccionadoIndex = index; // Actualizar el índice del cliente seleccionado
                resaltarElemento(resultado, index); // Resaltar el elemento
            });
            li.addEventListener('mouseleave', () => {
                clienteSeleccionadoIndex = -1; // Reiniciar el índice al salir
                resaltarElemento(resultado, -1); // Quitar el resaltado
            });
            resultado.appendChild(li); // Añadir el elemento a la lista de resultados
        });
    } catch (error) {
        // Manejar errores en la búsqueda
        console.error('Error al buscar clientes:', error);
    }
}

// Función para resaltar el elemento seleccionado
function resaltarElemento(resultado, index) {
    const items = resultado.getElementsByTagName('li'); // Obtener todos los elementos de lista
    for (let i = 0; i < items.length; i++) {
        // Cambiar el color de fondo del elemento resaltado
        items[i].style.backgroundColor = i === index ? '#e0e0e0' : '';
    }
}

// Evento para manejar la navegación por teclado en el buscador
document.getElementById('buscador').addEventListener('keydown', (event) => {
    const items = document.getElementById('resultado-clientes').getElementsByTagName('li');

    // Mover hacia abajo en la lista con la flecha hacia abajo
    if (event.key === 'ArrowDown') {
        clienteSeleccionadoIndex = Math.min(clienteSeleccionadoIndex + 1, items.length - 1);
        resaltarElemento(document.getElementById('resultado-clientes'), clienteSeleccionadoIndex); // Resaltar el nuevo elemento
        event.preventDefault(); // Evitar el comportamiento por defecto
    } 
    // Mover hacia arriba en la lista con la flecha hacia arriba
    else if (event.key === 'ArrowUp') {
        clienteSeleccionadoIndex = Math.max(clienteSeleccionadoIndex - 1, 0);
        resaltarElemento(document.getElementById('resultado-clientes'), clienteSeleccionadoIndex); // Resaltar el nuevo elemento
        event.preventDefault(); // Evitar el comportamiento por defecto
    } 
    // Seleccionar el cliente con la tecla Enter
    else if (event.key === 'Enter') {
        if (clienteSeleccionadoIndex >= 0) {
            const clienteSeleccionado = clientesSeleccionados[clienteSeleccionadoIndex];
            // Llamar a la función para seleccionar al cliente
            seleccionarCliente(clienteSeleccionado.idCliente, clienteSeleccionado.nombreCompleto, clienteSeleccionado.tipoDocumento, clienteSeleccionado.documento);
            console.log(`Cliente seleccionado: ${clienteSeleccionado.nombreCompleto}`); // Mostrar en la consola
        }
    }
});

// Función para seleccionar un cliente y actualizar los campos del formulario
function seleccionarCliente(clienteId, nombreCompleto, tipoDocumento, documento) {
    document.getElementById('cliente_id').value = clienteId; // Guardar el ID del cliente
    document.getElementById('cliente_seleccionado').value = `${nombreCompleto} - ${tipoDocumento} ${documento}`; // Mostrar información del cliente
    document.getElementById('buscador').value = ''; // Limpiar el campo de búsqueda
    document.getElementById('resultado-clientes').innerHTML = ''; // Limpiar los resultados
}
