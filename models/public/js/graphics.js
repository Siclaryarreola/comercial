// Gráfica de sucursales
const ctxSucursales = document.getElementById('chartSucursales').getContext('2d');
new Chart(ctxSucursales, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_column($sucursales, 'sucursal')) ?>,
        datasets: [{
            data: [10, 20, 30], // Sustituye con datos dinámicos
            backgroundColor: ['#ff6384', '#36a2eb', '#cc65fe']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Gráfica de medios de contacto
const ctxMedios = document.getElementById('chartMedios').getContext('2d');
new Chart(ctxMedios, {
    type: 'pie',
    data: {
        labels: <?= json_encode(array_column($medios, 'contacto')) ?>,
        datasets: [{
            data: [15, 25, 60], // Sustituye con datos dinámicos
            backgroundColor: ['#ffce56', '#4bc0c0', '#ff6384']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
