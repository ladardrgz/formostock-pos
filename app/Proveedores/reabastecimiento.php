<?php
include("../modelos/conexion.php");
include 'obtenerProveedor.php';
$proveedor = new Proveedor($conection);
$proveedoresActivos = $proveedor->obtenerProveedoresActivos();

// Verificar si se envió la orden de compra
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fechaOrden = $_POST['fechaOrden'];
    $estadoOrdenId = $_POST['estado_orden_id'];
    $proveedorId = $_POST['proveedor_id'];

    if (isset($_POST['productosAgregados']) && !empty($_POST['productosAgregados'])) {
        $productosAgregados = json_decode($_POST['productosAgregados'], true);

        echo "Orden de compra enviada correctamente.";
    } else {
        echo "No se han agregado productos a la orden.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar órden de compra</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">
    <style>
body {
    background-image: url(../assets/img/background-black.png);
    color: #503459;
    margin: 0;
    padding: 0;
}

h1 {
    text-align: center;
    color: #503459;
    margin-top: 20px;
}

form {
    max-width: 900px;
    margin: 20px auto;
    background: #fff;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

label {
    font-weight: bold;
    margin-bottom: 5px;
    display: block;
}

select, input[type="text"], input[type="number"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1em;
}

input[type="text"]:focus, input[type="number"]:focus, select:focus {
    border-color: #503459;
    outline: none;
}

/* Estilo general de los botones */
button {
    padding: 12px;
    font-size: 1em;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button[type="submit"] {
    background-color: #503459;
    color: #fff;
    border: none;
}

button[type="submit"]:hover {
    background-color: #765482;
}

/* Estilos de tablas */
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

th, td {
    text-align: left;
    padding: 12px;
    border: 1px solid #ddd;
}

th {
    background-color: #503459;
    color: #fff;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

/* Ancho de la columna de cantidad */
#tablaProductos th:nth-child(3),
#tablaProductos td:nth-child(3) {
    width: 80px; /* Ajustar el ancho de la columna de cantidad */
}

/* Estilos del paginador */
#paginador {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 15px;
    margin-top: 20px;
    margin-bottom: 20px;
}

#paginador button {
    background-color: #503459;
    color: #fff;
    padding: 8px 16px;
    border-radius: 4px;
    font-size: 0.9em;
    transition: background-color 0.3s ease;
    border: none;
}

#paginador button:hover {
    background-color: #564AA8;
}

#numeroPagina {
    font-size: 1em;
    font-weight: bold;
    color: #555;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    #paginador {
        flex-direction: column;
        gap: 10px;
    }
}

.input-cantidad {
    width: 30px; /* Ancho reducido del campo de cantidad */
    padding: 0; /* Sin espaciado interno */
    border: none; /* Sin bordes */
    border-radius: 0; /* Sin esquinas redondeadas */
    font-size: 1em; /* Tamaño de la fuente */
    text-align: center; /* Centrar el texto */
    background-color: transparent; /* Fondo transparente */
}

input.input-cantidad {
    width: 30px;
    padding: 0;
    border: none;
    border-radius: 0;
    font-size: 1em;
    text-align: center;
    background-color: transparent;
}

.input-cantidad:focus {
    outline: none; /* Sin contorno adicional al tener foco */
}

.proveedor-parrafo {
    margin: 0; /* Elimina el margen por defecto */
    line-height: 1.5; /* Altura de línea para mejor legibilidad */
    font-size: 1em; /* Ajusta el tamaño de la fuente */
    color: #503459; /* Color del texto */
    vertical-align: middle; /* Alineación vertical del texto */
}


    </style>
</head>
<body>
<?php include 'nav_proveedores.php'; ?>
    <h1>Reabastecimiento de productos - Generar orden de compra</h1>
    <form id="ordenCompraForm" method="POST" action="enviarOrdenCompra.php">

        <input type="hidden" name="fechaOrden" id="fechaOrdenCompra">
        <input type="hidden" name="estado_orden_id" id="estado_orden_id" value="22">
        <input type="hidden" name="productosAgregados" id="productosAgregados">
        <input type="hidden" name="totalOrdenCompra" id="totalOrdenCompraCampo">

        <div style="display: flex; align-items: center;">
    <img src="../assets/img/btn-inf.ico" alt="Información" style="width: 25px; height: auto; margin-right: 10px;" />
    <p class="proveedor-parrafo">Selecciona un proveedor para comenzar a agregar productos a su pedido</p>
</div>

        <br>
        <label for="proveedor_id">Proveedores</label>
        <select name="proveedor_id" id="proveedor_id" class="form-control" required>
            <option value="" disabled selected>Selecciona un proveedor</option>
            <?php foreach ($proveedoresActivos as $proveedor): ?>
                <option value="<?php echo $proveedor['idProveedor']; ?>">
                    <?php echo $proveedor['razonSocial']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <hr>

        <h2>Productos del proveedor seleccionado</h2>
        <table id="tablaProductos">
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <div id="paginador">
            <button onclick="cambiarPagina('anterior')" type="button">Anterior</button>
            <span id="numeroPagina">Página 1</span>
            <button onclick="cambiarPagina('siguiente')" type="button">Siguiente</button>
        </div>

        <hr>

        <h2>Orden de compra</h2>
        <table id="tablaOrdenCompra">
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <div>
            <strong>Subtotal</strong> <span id="subTotalOrdenCompra">$0.00</span>
        </div>
        <div>
            <strong>IVA 21%</strong> <span id="ivaOrdenCompra">$0.00</span>
        </div>
        <div>
            <strong>Total</strong> <span id="totalOrdenCompra">$0.00</span>
        </div>

        <button type="submit">
            <i class="fas fa-paper-plane"></i> Enviar órden de compra
        </button>
    </form>

    <script>
let productosAgregados = [];
let totalOrdenCompra = 0;
let paginaActual = 1;
const productosPorPagina = 10; 
let productosDisponibles = []; 

document.getElementById('proveedor_id').addEventListener('change', function () {
    const proveedorId = this.value;

    if (proveedorId) {
        fetch(`obtenerProductos.php?proveedor_id=${proveedorId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(productos => {
                console.log('Productos recibidos:', productos);
                productosDisponibles = productos; 
                cargarProductosEnTabla(productos);
            })
            .catch(error => console.error('Error al obtener los productos:', error));
    }
});

function cargarProductosEnTabla(productos) {
    const tbody = document.querySelector('#tablaProductos tbody');
    tbody.innerHTML = ''; 

    const inicio = (paginaActual - 1) * productosPorPagina;
    const fin = inicio + productosPorPagina;
    const productosPaginados = productos.slice(inicio, fin); 

    productosPaginados.forEach(producto => {
        const fila = document.createElement('tr');

        // Celda de descripción
        const descripcionTd = document.createElement('td');
        descripcionTd.textContent = producto.descripcionProducto;
        fila.appendChild(descripcionTd);

        // Celda de precio
        const precioTd = document.createElement('td');
        const precio = parseFloat(producto.precioProducto);
        precioTd.textContent = !isNaN(precio) ? precio.toFixed(2) : 'N/A';
        fila.appendChild(precioTd);

        // Celda de cantidad
        const cantidadTd = document.createElement('td');
        const inputCantidad = document.createElement('input');
        inputCantidad.type = 'number';
        inputCantidad.min = 1;
        inputCantidad.value = 1;
        inputCantidad.classList.add('input-cantidad'); // Agregando una clase personalizada
        cantidadTd.appendChild(inputCantidad);
        fila.appendChild(cantidadTd);

        // Celda de acciones
        const accionesTd = document.createElement('td');
        const botonAgregar = document.createElement('button');

        // Crear la imagen
        const imagenAgregar = document.createElement('img');
        imagenAgregar.src = '../assets/img/agregar-tarea.png'; // Reemplaza con la ruta de tu imagen
        imagenAgregar.alt = 'Agregar'; // Texto alternativo para la imagen
        imagenAgregar.style.width = '30px'; // Ajustar el ancho de la imagen
        imagenAgregar.style.height = '30px'; // Ajustar la altura de la imagen
        imagenAgregar.style.marginRight = '5px'; // Espacio entre la imagen y el texto

        // Agregar la imagen al botón
        botonAgregar.appendChild(imagenAgregar);
        
        // Agregar el texto al botón
        botonAgregar.appendChild(document.createTextNode('Agregar'));

        // Manejar el evento del botón
        botonAgregar.onclick = function (event) {
            event.preventDefault(); 
            const cantidad = parseInt(inputCantidad.value);
            if (cantidad > 0) {
                agregarProductoAOrden(producto, cantidad);
                calcularTotales();
            }
        };

        // Estilos opcionales para el botón
        botonAgregar.style.display = 'flex'; // Para alinear imagen y texto
        botonAgregar.style.alignItems = 'center'; // Centrar verticalmente
        botonAgregar.style.border = 'none'; // Sin borde
        botonAgregar.style.backgroundColor = '#4CAF50'; // Color de fondo
        botonAgregar.style.color = '#fff'; // Color del texto
        botonAgregar.style.padding = '8px 12px'; // Espaciado interno
        botonAgregar.style.borderRadius = '4px'; // Esquinas redondeadas
        botonAgregar.style.cursor = 'pointer'; // Cambiar cursor al pasar

        accionesTd.appendChild(botonAgregar);
        fila.appendChild(accionesTd);

        tbody.appendChild(fila);
    });

    document.getElementById('numeroPagina').textContent = `Página ${paginaActual}`;
}


function agregarProductoAOrden(producto, cantidad) {
    const existe = productosAgregados.find(item => item.idProducto === producto.idProducto);
    if (existe) {
        existe.cantidad += cantidad;
    } else {
        productosAgregados.push({
            idProducto: producto.idProducto,
            descripcionProducto: producto.descripcionProducto,
            precioProducto: parseFloat(producto.precioProducto), // Asegúrate de que sea un número
            cantidad: cantidad
        });
    }

    mostrarOrdenCompra();
}

function mostrarOrdenCompra() {
    const tbody = document.querySelector('#tablaOrdenCompra tbody');
    tbody.innerHTML = ''; 

    productosAgregados.forEach(producto => {
        const fila = document.createElement('tr');

        const descripcionTd = document.createElement('td');
        descripcionTd.textContent = producto.descripcionProducto;
        fila.appendChild(descripcionTd);

        const precioTd = document.createElement('td');
        precioTd.textContent = producto.precioProducto.toFixed(2);
        fila.appendChild(precioTd);

        const cantidadTd = document.createElement('td');
        cantidadTd.textContent = producto.cantidad;
        fila.appendChild(cantidadTd);

        const totalTd = document.createElement('td');
        const subTotalProducto = producto.precioProducto * producto.cantidad;
        totalTd.textContent = subTotalProducto.toFixed(2);
        fila.appendChild(totalTd);

        const accionesTd = document.createElement('td');
        const botonEliminar = document.createElement('button');
        botonEliminar.textContent = 'Eliminar';
        botonEliminar.onclick = function () {
            productosAgregados = productosAgregados.filter(item => item.idProducto !== producto.idProducto);
            mostrarOrdenCompra();
            calcularTotales(); // Recalcular totales al eliminar
        };
        accionesTd.appendChild(botonEliminar);
        fila.appendChild(accionesTd);

        tbody.appendChild(fila);
    });

    // Actualizar el campo oculto con los productos agregados
    document.getElementById('productosAgregados').value = JSON.stringify(productosAgregados);
}

function calcularTotales() {
    totalOrdenCompra = productosAgregados.reduce((total, producto) => {
        const subTotal = producto.precioProducto * producto.cantidad;
        return total + subTotal;
    }, 0);

    const iva = totalOrdenCompra * 0.21; 
    const totalConIva = totalOrdenCompra + iva;

    document.getElementById('subTotalOrdenCompra').textContent = `$${totalOrdenCompra.toFixed(2)}`;
    document.getElementById('ivaOrdenCompra').textContent = `$${iva.toFixed(2)}`;
    document.getElementById('totalOrdenCompra').textContent = `$${totalConIva.toFixed(2)}`;

// Actualizar el campo oculto con el total
document.getElementById('totalOrdenCompraCampo').value = totalConIva.toFixed(2); // Aquí asignamos el total al campo oculto

}

function cambiarPagina(direccion) {
    const totalPaginas = Math.ceil(productosDisponibles.length / productosPorPagina);
    if (direccion === 'siguiente' && paginaActual < totalPaginas) {
        paginaActual++;
    } else if (direccion === 'anterior' && paginaActual > 1) {
        paginaActual--;
    }
    cargarProductosEnTabla(productosDisponibles);
}

function filtrarProductos() {
    const buscador = document.getElementById('buscadorProducto').value.toLowerCase();
    const productosFiltrados = productosDisponibles.filter(producto => 
        producto.descripcionProducto.toLowerCase().includes(buscador)
    );
    cargarProductosEnTabla(productosFiltrados);
}

// Función para establecer la fecha de hoy en el campo oculto
function establecerFechaOrden() {
    const fechaHoy = new Date(); // Obtener la fecha actual
    const fechaFormateada = fechaHoy.toISOString().split('T')[0]; // Obtener solo la parte de la fecha en formato YYYY-MM-DD
    document.getElementById('fechaOrdenCompra').value = fechaFormateada; // Asignar la fecha al campo oculto
}

// Llamar a la función al cargar la página
document.addEventListener('DOMContentLoaded', establecerFechaOrden);

document.getElementById('ordenCompraForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Evitar el envío del formulario tradicional

    const formData = new FormData(this);

    fetch('enviarOrdenCompra.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: data.message,
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message,
            });
        }
    })
    .catch(error => {
        console.error('Error al enviar la orden:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al enviar la orden.',
        });
    });
});

</script>
<script src="ButtonOnTheRight.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
