const cbxPais = document.getElementById('nombrePais')
cbxPais.addEventListener('change', getProvincias)

const cbxProvincias = document.getElementById('nombreProvincia')
cbxProvincias.addEventListener('change', getLocalidades)

const cbxLocalidades = document.getElementById('nombreLocalidad')
cbxLocalidades.addEventListener('change', getBarrios)

const cbxBarrios = document.getElementById('nombreBarrio')

function fetchAndSetData(url, formData, targetElement){
    return fetch(url, {
        method: "POST",
        body: formData,
        mode: 'cors'
    })
        .then(response => response.json())
        .then(data => {
            // Utilizó <innerHTML> por qué se trata de un <select>.
            targetElement.innerHTML = data
        })
        .catch(err => console.log(err))
}

function getProvincias(){
    let nombrePais = cbxPais.value
    let url = '../controladores/getProvincias.php'
    let formData = new FormData()
    formData.append('pais_id', nombrePais)

    fetchAndSetData(url, formData, cbxProvincias)
        .then(() => {
            cbxLocalidades.innerHTML = ''
            cbxLocalidades.innerHTML = "<option value=''>Seleccionar</option>"
            cbxBarrios.innerHTML = '<option value="">Seleccionar</option>' 
        })
        .catch(err => console.log(err))
}

function getLocalidades(){
    let nombreLocalidad = cbxProvincias.value
    let url = '../controladores/getLocalidades.php'
    let formData = new FormData()
    formData.append('provincia_id', nombreLocalidad)

    fetchAndSetData(url, formData, cbxLocalidades)
        .then(() => {
            cbxBarrios.innerHTML = ''
            cbxBarrios.innerHTML = "<option value=''>Seleccionar</option>"
        })
        .catch(err => console.log(err))
}

function getBarrios(){
    let nombreBarrio = cbxLocalidades.value
    let url = '../controladores/getBarrios.php'
    let formData = new FormData()
    formData.append('localidad_id', nombreBarrio)

    fetchAndSetData(url, formData, cbxBarrios)
}