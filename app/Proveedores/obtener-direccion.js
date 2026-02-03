document.getElementById('pais').addEventListener('change', function() {
    var paisId = this.value;
    fetch('get_provincias.php?pais_id=' + paisId)
        .then(response => response.json())
        .then(data => {
            var provinciaSelect = document.getElementById('provincia');
            provinciaSelect.innerHTML = '<option value="">Seleccione una provincia</option>'; // Reset
            data.forEach(function(provincia) {
                provinciaSelect.innerHTML += '<option value="' + provincia.idProvincia + '">' + provincia.nombreProvincia + '</option>';
            });
        });
});

document.getElementById('provincia').addEventListener('change', function() {
    var provinciaId = this.value;
    fetch('get_localidades.php?provincia_id=' + provinciaId)
        .then(response => response.json())
        .then(data => {
            var localidadSelect = document.getElementById('localidad');
            localidadSelect.innerHTML = '<option value="">Seleccione una localidad</option>'; // Reset
            data.forEach(function(localidad) {
                localidadSelect.innerHTML += '<option value="' + localidad.idLocalidad + '">' + localidad.nombreLocalidad + '</option>';
            });
        });
});

document.getElementById('localidad').addEventListener('change', function() {
    var localidadId = this.value;
    fetch('get_barrios.php?localidad_id=' + localidadId)
        .then(response => response.json())
        .then(data => {
            var barrioSelect = document.getElementById('barrio');
            barrioSelect.innerHTML = '<option value="">Seleccione un barrio</option>'; // Reset
            data.forEach(function(barrio) {
                barrioSelect.innerHTML += '<option value="' + barrio.idBarrio + '">' + barrio.nombreBarrio + '</option>';
            });
        });
});