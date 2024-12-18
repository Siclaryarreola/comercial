const ctxSucursales = document.getElementById('chartSucursales').getContext('2d');
const branchChart = new Chart(ctxSucursales, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($sucursales, 'sucursal')) ?>, // Etiquetas dinámicas desde PHP
        datasets: [{
            label: 'Número de Leads por Sucursal', // Texto inicial para la leyenda
            data: <?= json_encode(array_column($sucursales, 'conteo')) ?>, // Datos dinámicos desde PHP
            backgroundColor: [
                '#ff6384', '#36a2eb', '#ffcc00', '#ff5733', '#bfff00', // Verde limón aquí
                '#27ae60', '#f39c12', '#d35400', '#3498db', '#9b59b6',
                '#16a085', '#c0392b', '#7f8c8d', '#2ecc71', '#e74c3c'
            ], // Colores diversos y mejorados
            borderColor: [
                '#e74c3c', '#2980b9', '#f1c40f', '#c0392b', '#8dc700', // Borde acorde al verde limón
                '#27ae60', '#e67e22', '#d35400', '#2980b9', '#9b59b6',
                '#16a085', '#c0392b', '#95a5a6', '#2ecc71', '#e74c3c'
            ], // Bordes con mayor contraste
            borderWidth: 1 // Grosor de los bordes
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: {
                    generateLabels: function(chart) {
                        const labels = chart.data.labels; // Etiquetas dinámicas
                        const data = chart.data.datasets[0].data; // Datos del dataset
                        const backgroundColor = chart.data.datasets[0].backgroundColor; // Colores

                        return labels.map((label, index) => ({
                            text: `${label}: ${data[index]}`, // Etiqueta y valor
                            fillStyle: backgroundColor[index], // Color correspondiente
                            hidden: false,
                            index: index,
                        }));
                    }
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return `${context.label}: ${context.raw}`; // Formato del tooltip
                    }
                }
            }
        },
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Sucursales' // Título del eje X
                }
            },
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Cantidad de Leads' // Título del eje Y
                }
            }
        }
    }
});
