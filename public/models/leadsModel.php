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
            LEFT JOIN sucursales s ON l.id_sucursal = s.id
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
        $query = "SELECT id, sucursal FROM sucursales";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getNegocios() {
        $query = "SELECT id_negocio, negocio FROM negocioleads";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addLead($data) {
        $this->db->begin_transaction();
        try {
            $queryCliente = "
                INSERT INTO clientesleads (contacto, correo, telefono, empresa, giro, localidad, sucursal, fechaCreacion, id_usuario)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?)
            ";

            $stmtCliente = $this->db->prepare($queryCliente);
            if (!$stmtCliente) {
                throw new Exception("Error preparando la consulta para insertar en clientesleads: " . $this->db->error);
            }

            $stmtCliente->bind_param(
                "sssssssi",
                $data['contacto'],
                $data['correo'],
                $data['telefono'],
                $data['empresa'],
                $data['giro'],
                $data['localidad'],
                $data['sucursal'],
                $data['id_usuario']
            );

            if (!$stmtCliente->execute()) {
                throw new Exception("Error al insertar en clientesleads: " . $stmtCliente->error);
            }

            $idCliente = $this->db->insert_id;

            $queryLead = "
                INSERT INTO leads (id_cliente, id_usuario, medio_contacto, estatus, cotizacion, linea_negocio, notas, archivo, fecha_generacion, periodo, gerente_responsable, id_sucursal)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?)
            ";

            $stmtLead = $this->db->prepare($queryLead);
            if (!$stmtLead) {
                throw new Exception("Error preparando la consulta para insertar en leads: " . $this->db->error);
            }

            $stmtLead->bind_param(
                "iissssssiii",
                $idCliente,
                $data['id_usuario'],
                $data['medio_contacto'],
                $data['estatus'],
                $data['cotizacion'],
                $data['linea_negocio'],
                $data['notas'],
                $data['archivo'],
                $data['periodo'],
                $data['gerente_responsable'],
                $data['sucursal']
            );

            if (!$stmtLead->execute()) {
                throw new Exception("Error al insertar en leads: " . $stmtLead->error);
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollback();
            error_log($e->getMessage());
            return false;
        }
    }
}
?>
