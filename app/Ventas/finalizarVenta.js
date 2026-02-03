function finalizarVenta() {
    const formaPago = document.getElementById('forma_pago').value;
    const clienteId = document.getElementById('cliente_id').value;
    const cajaId = document.getElementById('caja_id').value;
    const sucursalId = document.getElementById('sucursal_id').value;

    if (!formaPago) {
        Swal.fire({
            icon: 'warning',
            title: 'Forma de pago requerida',
            text: 'Por favor, seleccione una forma de pago.',
        });
        return;
    }

    if (!clienteId) {
        Swal.fire({
            icon: 'warning',
            title: 'Cliente no seleccionado',
            text: 'Por favor, seleccione un cliente.',
        });
        return;
    }

    if (!cajaId || !sucursalId) {
        Swal.fire({
            icon: 'error',
            title: 'Error en la sesión',
            text: 'La información de la caja o sucursal no está disponible. Por favor, contacte al administrador.',
        });
        return;
    }

    const datosVenta = {
        productos: carrito,
        subtotal: parseFloat(document.getElementById('subtotal-venta').textContent.split('$')[1]),
        total: parseFloat(document.getElementById('total-venta').textContent.split('$')[1]),
        forma_pago: formaPago,
        cliente_id: clienteId,
        caja_id: cajaId,
        sucursal_id: sucursalId
    };

    fetch('procesarFac.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datosVenta)
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor: ' + response.status + ' ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Venta realizada correctamente',
                    text: 'Número de identificación de factura: ' + data.idFactura,
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#67f120'
                }).then(() => {
                    Swal.fire({
                        icon: 'question',
                        title: '¿Desea exportar la factura?',
                        showCancelButton: true,
                        confirmButtonText: 'Sí',
                        cancelButtonText: 'No',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            console.log("Código de la factura:", data.idFactura); // Verifica que se reciba el ID aquí
                            window.location.href = 'PDF_exportarFactura.php?idFactura=' + data.idFactura;
                        }
                    });
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error en la venta',
                    text: 'Error al realizar la venta: ' + data.message,
                });
            }
        })
    };        
