<?php
require_once('../config/database.php');

class BuzonModel {
    private $db;

    public function __construct() 
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtener leads pendientes para un usuario (sin filtros adicionales).
     */
     
     
   public function getPendingLeadsFromDB($clienteFiltro = '', $estatusFiltro = '') {
    $query = "
        SELECT 
            l.id,                                   -- ID del lead
            cl.empresa AS nombre_cliente,          -- Nombre del cliente (empresa) desde clientesleads
            cl.contacto AS nombre_contacto,        -- Nombre del contacto desde clientesleads
            cl.correo,                             -- Correo del contacto
            cl.telefono,                           -- Teléfono del contacto
            n.negocio AS linea_negocio,            -- Línea de negocio desde negocioleads
            e.estatus AS estatus,                  -- Estatus desde estatusleads
            g.gerente AS gerente_responsable,      -- Gerente responsable desde gerentesleads
            s.sucursal AS sucursal,                -- Sucursal desde sucursales
            p.periodo AS periodo,                  -- Periodo desde periodosleads
            mc.contacto AS medio_contacto,         -- Medio de contacto desde contactoleads
            l.cotizacion,                   -- Monto de cotización
            l.notas,                              -- Notas del lead
            l.archivo                 -- Archivo de cotización
        FROM leads l
        LEFT JOIN clientesleads cl ON l.id_cliente = cl.id          -- Relación con clientesleads
        LEFT JOIN negocioleads n ON l.linea_negocio = n.id_negocio  -- Relación con negocioleads
        LEFT JOIN estatusleads e ON l.estatus = e.id_estatus        -- Relación con estatusleads
        LEFT JOIN gerentesleads g ON l.gerente_responsable = g.id_gerente -- Relación con gerentesleads
        LEFT JOIN sucursales s ON l.id_sucursal = s.id_sucursales   -- Relación con sucursales
        LEFT JOIN periodosleads p ON l.periodo = p.id_periodo       -- Relación con periodosleads
        LEFT JOIN contactoleads mc ON l.medio_contacto = mc.id_contacto -- Relación con medios de contacto
        WHERE l.estatus NOT IN (6, 7)
    ";

    $params = [];
    $types = '';

    if (!empty($clienteFiltro)) {
        $query .= " AND cl.empresa LIKE ?";
        $params[] = '%' . $clienteFiltro . '%';
        $types .= 's';
    }

    if (!empty($estatusFiltro)) {
        $query .= " AND e.id_estatus = ?";
        $params[] = (int)$estatusFiltro;
        $types .= 'i';
    }

    $query .= " ORDER BY l.fecha_generacion DESC";

    $stmt = $this->db->prepare($query);
    if (!$stmt) {
        error_log("Error preparando getPendingLeadsFromDB: " . $this->db->error);
        return [];
    }

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}


    /**
     * Obtener leads asignados a un gerente específico (sin filtros adicionales).
     */
    public function getLeadsByGerente($gerenteId) {
        $query = "
            SELECT l.*, c.contacto, c.correo, c.telefono
            FROM leads l
            LEFT JOIN clientesleads c ON l.id_cliente = c.id
            WHERE l.gerente_responsable = ? AND l.estatus NOT IN (6, 7)
            ORDER BY l.fecha_generacion DESC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $gerenteId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Asignar un lead a un gerente.
     */
    public function assignLeadToGerente($leadId, $gerenteId) {
        $query = "UPDATE leads SET gerente_responsable = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $gerenteId, $leadId);
        return $stmt->execute();
    }
    
    /**
     * Obtener leads pendientes asignados a un gerente (sin filtros).
     */
    public function getPendingLeadsByGerente($gerenteId) {
        $query = "SELECT * FROM leads WHERE gerente_responsable = ? AND estatus != 'Completado'";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $gerenteId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    /**
     * Obtener leads para un gerente específico con filtros opcionales.
     */
    public function getLeadsForGerenteFromDB($gerenteId, $clienteFiltro = '', $estatusFiltro = '') {
        $query = "
            SELECT l.*, c.contacto, c.correo, c.telefono
            FROM leads l
            LEFT JOIN clientesleads c ON l.id_cliente = c.id
            WHERE l.gerente_responsable = ? AND l.estatus NOT IN (6, 7)
        ";

        $params = [$gerenteId];
        $types = 'i';

        if (!empty($clienteFiltro)) {
            $query .= " AND c.contacto LIKE ?";
            $params[] = '%' . $clienteFiltro . '%';
            $types .= 's';
        }

        if (!empty($estatusFiltro)) {
            $query .= " AND l.estatus = ?";
            $params[] = (int)$estatusFiltro;
            $types .= 'i';
        }

        $query .= " ORDER BY l.fecha_generacion DESC";

        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            error_log("Error preparando getLeadsForGerenteFromDB: " . $this->db->error);
            return [];
        }

        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /*-----------------------------------------------------
     Métodos para obtener datos para listas desplegables
     -----------------------------------------------------*/

    // Obtener contactos (Medios de contacto)
    public function getContactos() 
    {
        return $this->fetchAll("SELECT id_contacto, contacto FROM contactoleads");
    }

    // Obtener periodos
    public function getPeriodos() {
        return $this->fetchAll("SELECT id_periodo, periodo FROM periodosleads");
    }

    // Obtener estatus
    public function getEstatus() {
        return $this->fetchAll("SELECT id_estatus, estatus FROM estatusleads");
    }

    // Obtener gerentes
    public function getGerentes() {
        return $this->fetchAll("SELECT id_gerente, gerente FROM gerentesleads");
    }

    // Obtener sucursales
    public function getSucursales() {
        return $this->fetchAll("SELECT id_sucursales, sucursal FROM sucursales");
    }

    // Obtener negocios (líneas de negocio)
    public function getNegocios() {
        return $this->fetchAll("SELECT id_negocio, negocio FROM negocioleads");
    }

    /**
     * Método auxiliar para ejecutar consultas SELECT simples.
     */
    private function fetchAll($query) {
        $result = $this->db->query($query);
        if (!$result) {
            error_log("Error ejecutando la consulta SQL: " . $this->db->error);
            return [];
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    
    

}


