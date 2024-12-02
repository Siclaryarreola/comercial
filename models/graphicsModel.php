<?php
require_once('../config/database.php');

class GraphicsModel {
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
        $query = "SELECT id, sucursal FROM sucursales";
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
                SUM(cotizacion) AS totalCotizado,
                SUM(CASE WHEN estatus = 'Cerrado-Ganado' THEN cotizacion ELSE 0 END) AS ventasTotales,
                SUM(CASE WHEN estatus = 'Cerrado-Perdido' THEN cotizacion ELSE 0 END) AS totalPerdido
            FROM leads
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function getLeadsDetalle() {
    $query = "
        SELECT 
            leads.fecha_generacion AS fecha,
            sucursales.sucursal AS sucursal,
            clientesleads.empresa AS empresa,
            estatusleads.estatus AS estatus,
            negocioleads.negocio AS linea_negocio,
            leads.notas AS comentarios
        FROM leads
        INNER JOIN sucursales ON leads.id_sucursal = sucursales.id
        INNER JOIN clientesleads ON leads.id_cliente = clientesleads.id
        INNER JOIN estatusleads ON leads.estatus = estatusleads.id_estatus
        INNER JOIN negocioleads ON leads.linea_negocio = negocioleads.id_negocio
    ";
    $stmt = $this->db->prepare($query);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

}
?>
