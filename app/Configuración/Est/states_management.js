function mostrarFormulario() {
    var form = document.getElementById('form-agregar');
    if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}

// Eliminar estado con SweetAlert
document.querySelectorAll('.btn-eliminar').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#6181e7',
            cancelButtonColor: '#cd4646',
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `delete_state.php?id=${id}`;
            }
        })
    });
});
