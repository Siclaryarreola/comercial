<?php
require_once('../config/database.php');

class GraphicsModel 
{
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getGeneradoresDemanda() {
        $query = "SELECT id_usuarios, nombre FROM usuarios WHERE puesto = 3"; // Suponiendo que "3" es el ID para Generadores de Demanda
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getSucursales() {
        $query = "SELECT id_sucursales, sucursal FROM sucursales";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getPeriodos() {
        $query = "SELECT id_periodo, periodo FROM periodosleads";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getMediosDeContacto() {
        $query = "SELECT id_contacto, contacto FROM contactoleads";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getEtapasLeads() {
        $query = "
            SELECT 
                estatusleads.estatus, 
                COUNT(leads.id) AS conteo
            FROM leads
            INNER JOIN estatusleads ON leads.estatus = estatusleads.id_estatus
            GROUP BY estatusleads.estatus
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getConversionLeads() {
    $query = "
        SELECT 
            COUNT(*) AS totalLeads, 
            SUM(leads.cotizacion) AS totalCotizado,
            SUM(CASE WHEN estatusleads.estatus = 'Cerrado-Ganado' THEN leads.cotizacion ELSE 0 END) AS ventasTotales,
            SUM(CASE WHEN estatusleads.estatus = 'Cerrado-Perdido' THEN leads.cotizacion ELSE 0 END) AS totalPerdido
        FROM leads
        INNER JOIN estatusleads ON leads.estatus = estatusleads.id_estatus
    ";
    $stmt = $this->db->prepare($query);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}


    
    public function getLeadsDetalle() 
    {
        $query = "
        SELECT 
            leads.fecha_generacion AS fecha,
            sucursales.sucursal AS sucursal,
            clientesleads.empresa AS empresa,
            estatusleads.estatus AS estatus,
            negocioleads.negocio AS linea_negocio,
            leads.notas AS comentarios
        FROM leads
        INNER JOIN sucursales ON leads.id_sucursal = sucursales.id_sucursales
        INNER JOIN clientesleads ON leads.id_cliente = clientesleads.id
        INNER JOIN estatusleads ON leads.estatus = estatusleads.id_estatus
        INNER JOIN negocioleads ON leads.linea_negocio = negocioleads.id_negocio
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
      public function getBranchData() 
    {
        $query = "
            SELECT 
                sucursales.sucursal, 
                COUNT(leads.id) AS conteo
            FROM leads
            INNER JOIN sucursales ON leads.id_sucursal = sucursales.id_sucursales
            GROUP BY sucursales.sucursal
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getContactData() 
{
    $query = "
        SELECT 
            contactoleads.contacto, 
            COUNT(leads.id) AS conteo
        FROM leads
        INNER JOIN contactoleads ON leads.medio_contacto = contactoleads.id_contacto
        GROUP BY contactoleads.contacto
    ";
    $stmt = $this->db->prepare($query);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}


public function getFilteredBranchData($generador, $periodo, $sucursal, $lineaNegocio) 
{
    $query = "
        SELECT sucursales.sucursal, COUNT(leads.id_lead) AS conteo
        FROM leads
        INNER JOIN sucursales ON leads.id_sucursal = sucursales.id_sucursales
        WHERE 1=1
    ";

    $params = [];
    if ($generador) {
        $query .= " AND leads.id_generador = ?";
        $params[] = $generador;
    }
    if ($periodo) {
        $query .= " AND leads.id_periodo = ?";
        $params[] = $periodo;
    }
    if ($sucursal) {
        $query .= " AND leads.id_sucursal = ?";
        $params[] = $sucursal;
    }
    if ($lineaNegocio) {
        $query .= " AND leads.linea_negocio = ?";
        $params[] = $lineaNegocio;
    }

    $query .= " GROUP BY sucursales.sucursal";
    $stmt = $this->db->prepare($query);
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

public function getFilteredContactData($generador, $periodo, $sucursal, $lineaNegocio) 
{
    $query = "
        SELECT medios_contacto.contacto, COUNT(leads.id_lead) AS conteo
        FROM leads
        INNER JOIN medios_contacto ON leads.id_contacto = medios_contacto.id_contacto
        WHERE 1=1
    ";

    $params = [];
    if ($generador) {
        $query .= " AND leads.id_generador = ?";
        $params[] = $generador;
    }
    if ($periodo) {
        $query .= " AND leads.id_periodo = ?";
        $params[] = $periodo;
    }
    if ($sucursal) {
        $query .= " AND leads.id_sucursal = ?";
        $params[] = $sucursal;
    }
    if ($lineaNegocio) {
        $query .= " AND leads.linea_negocio = ?";
        $params[] = $lineaNegocio;
    }

    $query .= " GROUP BY medios_contacto.contacto";
    $stmt = $this->db->prepare($query);
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}



}
?>
