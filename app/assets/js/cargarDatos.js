document.getElementById('pais').addEventListener('change', function() {
    fetchProvincias(this.value);
});

document.getElementById('provincia').addEventListener('change', function() {
    fetchLocalidades(this.value);
});

document.getElementById('localidad').addEventListener('change', function() {
    fetchBarrios(this.value);
});

function fetchProvincias(paisId) {
    fetch('/FormoStock/app/Usuarios/get_provincias.php?pais_id=' + paisId)
        .then(response => response.json())
        .then(data => {
            let provinciaSelect = document.getElementById('provincia');
            provinciaSelect.innerHTML = '<option value="">Selecciona la provincia</option>';
            data.forEach(provincia => {
                provinciaSelect.innerHTML += `<option value="${provincia.idProvincia}">${provincia.nombreProvincia}</option>`;
            });
        })
        .catch(error => console.error('Error al cargar las provincias:', error));
}

function fetchLocalidades(provinciaId) {
    fetch('/FormoStock/app/Usuarios/get_localidades.php?provincia_id=' + provinciaId)
        .then(response => response.json())
        .then(data => {
            let localidadSelect = document.getElementById('localidad');
            localidadSelect.innerHTML = '<option value="">Selecciona la localidad</option>';
            data.forEach(localidad => {
                localidadSelect.innerHTML += `<option value="${localidad.idLocalidad}">${localidad.nombreLocalidad}</option>`;
            });
        })
        .catch(error => console.error('Error al cargar las localidades:', error));
}

function fetchBarrios(localidadId) {
    fetch('/FormoStock/app/Usuarios/get_barrios.php?localidad_id=' + localidadId)
        .then(response => response.json())
        .then(data => {
            let barrioSelect = document.getElementById('barrio');
            barrioSelect.innerHTML = '<option value="">Selecciona el barrio</option>';
            data.forEach(barrio => {
                barrioSelect.innerHTML += `<option value="${barrio.idBarrio}">${barrio.nombreBarrio}</option>`;
            });
        })
        .catch(error => console.error('Error al cargar los barrios:', error));
}
