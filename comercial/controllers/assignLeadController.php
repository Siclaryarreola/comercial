<?php
require_once('../controllers/buzonLeadsController.php');

try {
    $controller = new BuzonLeadsController();
    $leadId = $_POST['leadId'];
    $gerenteId = $_POST['gerenteId'];
    $controller->assignToGerente($leadId, $gerenteId);
    header("Location: buzonLeads.php?success=1");
} 
catch (Exception $e) 
{
    echo "Error: " . htmlspecialchars($e->getMessage());
}
