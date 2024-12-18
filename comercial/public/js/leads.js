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
