<?php
require_once('../config/database.php');

class LeadModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getLeads($filters = []) {
        $query = "
            SELECT 
                l.*, 
                c.contacto AS contacto, c.correo, c.telefono, c.empresa, c.giro, c.localidad, s.sucursal,
                p.periodo AS periodo,
                e.estatus AS estatus,
                g.gerente AS gerente,
                mc.contacto AS medio_contacto,
                n.negocio AS negocio
            FROM leads l
            LEFT JOIN clientesleads c ON l.id_cliente = c.id
            LEFT JOIN periodosleads p ON l.periodo = p.id_periodo
            LEFT JOIN estatusleads e ON l.estatus = e.id_estatus
            LEFT JOIN gerentesleads g ON l.gerente_responsable = g.id_gerente
            LEFT JOIN contactoleads mc ON l.medio_contacto = mc.id_contacto
            LEFT JOIN sucursales s ON l.id_sucursal = s.id_sucursales
            LEFT JOIN negocioleads n ON l.linea_negocio = n.id_negocio
        ";

        $params = [];
        $conditions = [];

        if (!empty($filters['id_usuario'])) {
            $conditions[] = "l.id_usuario = ?";
            $params[] = $filters['id_usuario'];
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $query .= " ORDER BY l.fecha_generacion DESC";

        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            error_log("Error preparando la consulta SQL: " . $this->db->error);
            return [];
        }

        if (!empty($params)) {
            $stmt->bind_param(str_repeat('i', count($params)), ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function completeLead($data) {
        $query = "
            UPDATE leads
            SET cotizacion = ?, archivo = ?, estatus = 'Completado'
            WHERE id = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssi", $data['cotizacion'], $data['archivo'], $data['id']);
        return $stmt->execute();
    }

    public function getContactos() {
        $query = "SELECT id_contacto, contacto FROM contactoleads";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPeriodos() {
        $query = "SELECT id_periodo, periodo FROM periodosleads";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getEstatus() {
        $query = "SELECT id_estatus, estatus FROM estatusleads";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getGerentes() {
        $query = "SELECT id_gerente, gerente FROM gerentesleads";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getSucursales() {
        $query = "SELECT id_sucursales, sucursal FROM sucursales";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getNegocios() {
        $query = "SELECT id_negocio, negocio FROM negocioleads";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
