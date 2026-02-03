const carrito = [];

document.querySelectorAll('.agregar-al-carrito').forEach(button => {
    button.addEventListener('click', function() {
        const producto = {
            id: this.getAttribute('data-id'), 
            descripcion: this.getAttribute('data-descripcion'), 
            precio: parseFloat(this.getAttribute('data-precio')), 
            cantidad: 1 
        };
        
        const index = carrito.findIndex(item => item.id === producto.id);
        if (index > -1) {
            carrito[index].cantidad++; 
        } else {
            carrito.push(producto); 
        }
        actualizarCarrito(); 
    });
});

function actualizarCarrito() {
    const tablaCarrito = document.getElementById('tabla-carrito').getElementsByTagName('tbody')[0];
    tablaCarrito.innerHTML = ''; 

    let subtotal = 0; 
    carrito.forEach(producto => {
        const row = tablaCarrito.insertRow(); 
        row.insertCell(0).textContent = producto.descripcion; 

            const cellCantidad = row.insertCell(1);
            
            const btnDecrementar = document.createElement('button');
            btnDecrementar.textContent = '-';
            btnDecrementar.className = 'button button-cantidad'; 
            btnDecrementar.addEventListener('click', () => {
                if (producto.cantidad > 1) {
                    producto.cantidad--; 
                } else {
                    const index = carrito.indexOf(producto);
                    carrito.splice(index, 1);
                }
                actualizarCarrito(); 
            });
            
            const cantidadTexto = document.createElement('span');
            cantidadTexto.textContent = producto.cantidad;
            
            const btnIncrementar = document.createElement('button');
            btnIncrementar.textContent = '+';
            btnIncrementar.className = 'button button-cantidad'; 
            
            // Asumiendo que el stock disponible para el producto es proporcionado por el servidor y se agrega al botón
            const stockDisponible = parseInt(btnIncrementar.getAttribute('data-stock'), 10); // Aquí debes asegurarte que el stock esté siendo asignado al atributo data-stock de manera correcta
            
            btnIncrementar.addEventListener('click', () => {
                // Validamos si el producto tiene stock disponible
                if (producto.cantidad >= stockDisponible) {
                    // Si la cantidad es igual o mayor que el stock, mostramos un mensaje de error
                    Swal.fire({
                        icon: 'error',
                        title: 'Stock insuficiente',
                        text: 'No puedes agregar más unidades. El stock disponible es limitado.',
                    });
                } else {
                    producto.cantidad++; // Si el stock lo permite, incrementamos la cantidad
                }
                actualizarCarrito(); 
            });
            
            cellCantidad.appendChild(btnDecrementar);
            cellCantidad.appendChild(cantidadTexto);
            cellCantidad.appendChild(btnIncrementar);
            

const precioConImpuesto = producto.precio * (1 + 0.21); 
        row.insertCell(2).textContent = `$${(precioConImpuesto * producto.cantidad).toFixed(2)}`;         
        subtotal += precioConImpuesto * producto.cantidad; 

        const btnEliminar = document.createElement('button');
        const imgEliminar = document.createElement('img');
        imgEliminar.src = '../assets/img/quitar-del-carrito.png'; 
        imgEliminar.alt = 'Eliminar';
        imgEliminar.style.width = '40px';
        imgEliminar.style.height = '40px';
        
        btnEliminar.style.backgroundColor = 'transparent'; 
        btnEliminar.style.border = 'none'; 
        btnEliminar.style.boxShadow = 'none';
        btnEliminar.style.padding = '0'; 
        btnEliminar.style.cursor = 'pointer';
        
        btnEliminar.appendChild(imgEliminar);
        
        btnEliminar.addEventListener('click', () => {
            const index = carrito.indexOf(producto);
            if (index > -1) {
                carrito.splice(index, 1);
                actualizarCarrito(); 
            }
        });

        const cellAccion = row.insertCell(3); 
        cellAccion.appendChild(btnEliminar); 
    });

    document.getElementById('subtotal-venta').textContent = `Subtotal: $${subtotal.toFixed(2)}`;
    
    const impuesto = subtotal * 0.21; 
    const total = subtotal + impuesto; 

    document.getElementById('total-venta').textContent = `Total: $${total.toFixed(2)}`;
}
