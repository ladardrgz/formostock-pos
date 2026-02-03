
        // Función para cargar dinámicamente las provincias basadas en el país seleccionado
        function cargarProvincias() {
            var pais = document.getElementById("pais").value;
            var provinciaSelect = document.getElementById("provincia");
            provinciaSelect.innerHTML = "<option value=''>Seleccione una provincia</option>";

            // Realizar una solicitud AJAX para obtener las provincias del país seleccionado
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var provincias = JSON.parse(xhr.responseText);
                    for (var i = 0; i < provincias.length; i++) {
                        var option = document.createElement("option");
                        option.text = provincias[i].nombreProvincia;
                        option.value = provincias[i].idProvincia;
                        provinciaSelect.add(option);
                    }
                }
            };
            xhr.open("GET", "obtener_provincias.php?pais=" + pais, true);
            xhr.send();
        }

        // Función para cargar dinámicamente las localidades basadas en la provincia seleccionada
        function cargarLocalidades() {
            var provincia = document.getElementById("provincia").value;
            var localidadSelect = document.getElementById("localidad");
            localidadSelect.innerHTML = "<option value=''>Seleccione una localidad</option>";

            // Realizar una solicitud AJAX para obtener las localidades de la provincia seleccionada
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var localidades = JSON.parse(xhr.responseText);
                    for (var i = 0; i < localidades.length; i++) {
                        var option = document.createElement("option");
                        option.text = localidades[i].nombreLocalidad;
                        option.value = localidades[i].idLocalidad;
                        localidadSelect.add(option);
                    }
                }
            };
            xhr.open("GET", "obtener_localidades.php?provincia=" + provincia, true);
            xhr.send();
        }

        // Event listener para llamar a las funciones de cargar provincias y localidades cuando se cambia la selección
        document.getElementById("pais").addEventListener("change", cargarProvincias);
        document.getElementById("provincia").addEventListener("change", cargarLocalidades);
