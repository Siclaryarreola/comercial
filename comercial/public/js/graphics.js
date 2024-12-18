// Gráfica de Sucursales
document.addEventListener("DOMContentLoaded", function () {
    const ctxSucursales = document.getElementById('branchChart');
    if (!ctxSucursales) {
        console.error("No se encontró el elemento 'branchChart'.");
        return;
    }

    const branchChart = new Chart(ctxSucursales.getContext('2d'), {
        type: 'bar',
        data: {
            labels: window.branchLabels || [], // Etiquetas dinámicas
            datasets: [{
                label: 'Número de Leads por Sucursal',
                data: window.branchData || [], // Datos dinámicos
                backgroundColor: ['#ff6384', '#36a2eb', '#cc65fe', '#ffce56', '#4bc0c0'] // Colores
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return `${context.label}: ${context.raw}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Sucursales'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Cantidad de Leads'
                    }
                }
            }
        }
    });

    // Gráfica de Medios de Contacto
    const ctxMedios = document.getElementById('contactChart');
    if (!ctxMedios) {
        console.error("No se encontró el elemento 'contactChart'.");
        return;
    }

    new Chart(ctxMedios.getContext('2d'), {
        type: 'pie',
        data: {
            labels: window.contactLabels || [], // Etiquetas dinámicas
            datasets: [{
                data: window.contactData || [], // Datos dinámicos
                backgroundColor: ['#ff6384', '#36a2eb', '#cc65fe', '#ffce56', '#4bc0c0'], // Colores
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top', // Leyenda horizontal
                    labels: {
                        boxWidth: 15,
                        padding: 10
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return `${context.label}: ${context.raw}`;
                        }
                    }
                }
            }
        }
    });
});
