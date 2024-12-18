<?php 
$activePage = 'dashboard';
include('components/header.php');

?>
<main class="container mt-3 large-container"> <!-- Clase ajustada para un contenedor más grande -->
    <div class="content-wrapper">
        <!-- Imagen -->
        <div class="image-container">
            <img src="../public/images/banner_portal.jpg" alt="Imagen de administración" class="img-fluid rounded shadow-sm">
        </div>

        <!-- Texto -->
        <div class="text-container">
            <h2>Bienvenido al Portal de Generación de Demanda</h2>
            <p class="lead">
                El sistema está dirigido a facilitar el proceso desde la creación
                de cotizaciones hasta su cierre, proporcionando funcionalidades como la creación 
                de nuevas cotizaciones, actualización de información de clientes, seguimiento de 
                cotizaciones en diferentes etapas, y generación de reportes para análisis de desempeño.
            </p>
        </div>
    </div>
</main>

<?php include('components/footer.php'); ?>
