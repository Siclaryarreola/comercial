document.addEventListener('DOMContentLoaded', function() {
    // Configuración inicial de la gráfica de pastel
    const ctx = document.getElementById('branchChart').getContext('2d');
    let branchChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: Object.keys(etapasMap),
            datasets: [{
                label: 'Cantidad de Leads',
                data: Object.values(etapasMap),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',   // Rojo
                    'rgba(255, 99, 71, 0.6)',    // Nuevo color (Rojo claro)
                    'rgba(255, 206, 86, 0.6)',   // Amarillo
                    'rgba(75, 192, 192, 0.6)',   // Verde agua
                    'rgba(153, 102, 255, 0.6)',  // Morado
                    'rgba(255, 159, 64, 0.6)'    // Naranja
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 99, 71, 1)',       // Frontera con el nuevo color
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });
});


//Acción de doble click en tabla de leads

$(document).on('dblclick', '.editable', function () {
    let $cell = $(this);
    if (!$cell.find('input').length) {
        let originalValue = $cell.text().trim();
        let field = $cell.data('field');
        $cell.html(`<input type="text" class="form-control" value="${originalValue}">`);
        $cell.find('input').focus().blur(function () {
            let newValue = $(this).val().trim();
            if (newValue !== originalValue) {
                let leadId = $cell.closest('tr').data('id');
                updateLeadField(leadId, field, newValue, $cell);
            } else {
                $cell.text(originalValue);
            }
        });
    }
});

//Actualizar la tabla
function updateLeadField(id, field, value, $cell) {
    console.log("Datos enviados al servidor:", { id, field, value }); // Depurar datos

    $.ajax({
        url: `../controllers/leadsController.php?action=editLead&id=${id}`,
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ [field]: value }),
        success: function (response) {
            console.log("Respuesta del servidor:", response); // Ver respuesta
            let res = JSON.parse(response);
            if (res.success) {
                $cell.text(value);
                Swal.fire('Actualizado', 'El campo fue actualizado correctamente', 'success');
            } else {
                Swal.fire('Error', res.message || 'No se pudo actualizar el campo', 'error');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("Error al conectar con el servidor:", textStatus, errorThrown);
            console.error("Respuesta del servidor:", jqXHR.responseText);
            Swal.fire('Error', 'No se pudo conectar al servidor', 'error');
        }
    });
}

//Ación de listas desplegables en la tabla
$(document).on('dblclick', '.editable-select', function () 
{
    let $cell = $(this);
    if (!$cell.find('select').length) 
    {
        let originalValue = $cell.text().trim();
        let field = $cell.data('field');
        
         // Generar el desplegable con las opciones
        let options = getDropdownOptions(field);
        $cell.html(`<select class="form-control">${options}</select>`);
        
        // Seleccionar el valor actual
        $cell.find('select').val(originalValue).focus().blur(function () {
            let newValue = $(this).val();
            if (newValue !== originalValue) {
                let leadId = $cell.closest('tr').data('id');
                updateLeadField(leadId, field, newValue, $cell);
            } 
            else 
            {
                $cell.text(originalValue);
            }
        });
    }
});

    // Asume que `dropdownData` está disponible como una variable global
function getDropdownOptions(field) {
    let options = '<option value="">Seleccione</option>';
    if (dropdownData[field]) {
        dropdownData[field].forEach(item => {
            if (field === 'estatus' && ![1, 2, 3, 7, 8].includes(item.id)) {
                return; // Omite opciones no deseadas
            }
            options += `<option value="${item.id}">${item.label}</option>`;
        });
    }
    return options;
}



//Acción de filtrado automatico
$('#cliente').on('keyup', function () {
    let value = $(this).val().toLowerCase();
    $('#leadsTable tbody tr').filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
});


//Función actualización en tiepo real
function refreshTable() {
    $.get('../controllers/leadsController.php?action=index', function (data) {
        let leads = JSON.parse(data);
        // Reconstruye la tabla con los nuevos datos
    });
}


//Acción de botón de guardar
$(document).on('click', '.save-lead-btn', function () {
    // Identificar la fila del lead a guardar
    let $row = $(this).closest('tr');
    let leadId = $row.data('id'); // Obtener el ID del lead

    // Crear un objeto con los datos modificados
    let updatedData = {};
    $row.find('.editable, .editable-select').each(function () {
        let field = $(this).data('field');
        let newValue = $(this).find('input, select').length 
            ? $(this).find('input, select').val() // Si hay input/select, toma su valor
            : $(this).text().trim(); // Si no, toma el texto de la celda
        updatedData[field] = newValue;
    });


    // Enviar los datos al servidor mediante AJAX
    $.ajax({
       url: `../controllers/leadsController.php?action=editLead&id=${leadId}`,


        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(updatedData),
        success: function (response) {
            let res = JSON.parse(response);
            if (res.success) {
                Swal.fire('Guardado', 'Los cambios se han guardado correctamente', 'success');
            } else {
                Swal.fire('Error', res.message || 'No se pudieron guardar los cambios', 'error');
            }
        },
        error: function () {
            Swal.fire('Error', 'No se pudo conectar al servidor', 'error');
        }
    });
});


//Feedback al usuario
$.ajax({
    success: function (response) {
        let res = JSON.parse(response);
        if (res.success) {
            $row.find('.editable, .editable-select').each(function () {
                let field = $(this).data('field');
                $(this).text(updatedData[field]); // Actualizar el texto de la celda
            });
            Swal.fire('Guardado', 'Los cambios se han guardado correctamente', 'success');
        } else {
            Swal.fire('Error', res.message || 'No se pudieron guardar los cambios', 'error');
        }
    }
});
