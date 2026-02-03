// Archivo: js/data-loaders.js

// Cargar los países en el select correspondiente
function cargarPaises() {
    fetch('cargar_paises.php')
        .then(response => response.json())
        .then(data => {
            const paisSelect = document.getElementById('pais');
            data.forEach(pais => {
                const option = document.createElement('option');
                option.value = pais.idPais;
                option.textContent = pais.nombrePais;
                paisSelect.appendChild(option);
            });
        });
}

// Cargar las provincias basadas en el país seleccionado
function cargarProvincias() {
    const paisId = document.getElementById('pais').value;
    if (!paisId) return;

    fetch(`cargar_provincias.php?pais_id=${paisId}`)
        .then(response => response.json())
        .then(data => {
            const provinciaSelect = document.getElementById('provincia');
            provinciaSelect.innerHTML = '<option value="">Seleccionar provincia</option>';
            data.forEach(provincia => {
                const option = document.createElement('option');
                option.value = provincia.idProvincia;
                option.textContent = provincia.nombreProvincia;
                provinciaSelect.appendChild(option);
            });
        });
}

// Cargar las localidades basadas en la provincia seleccionada
function cargarLocalidades() {
    const provinciaId = document.getElementById('provincia').value;
    if (!provinciaId) return;

    fetch(`cargar_localidades.php?provincia_id=${provinciaId}`)
        .then(response => response.json())
        .then(data => {
            const localidadSelect = document.getElementById('localidad');
            localidadSelect.innerHTML = '<option value="">Seleccionar localidad</option>';
            data.forEach(localidad => {
                const option = document.createElement('option');
                option.value = localidad.idLocalidad;
                option.textContent = localidad.nombreLocalidad;
                localidadSelect.appendChild(option);
            });
        });
}

// Llamar la función de cargar países al cargar la página
window.onload = cargarPaises;
