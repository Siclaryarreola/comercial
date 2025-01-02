<?php
require_once('../config/database.php');

class LeadModel
{
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

/* ------------------------------------------------------------------------------------------*/
    // Obtener leads con filtros opcionales
    public function getLeads($filters = []) {
    $query = "
        SELECT 
            l.*, 
            c.contacto AS contacto, c.correo, c.telefono, c.empresa, c.giro, c.localidad, 
            s.sucursal, p.periodo AS periodo, e.estatus AS estatus, g.gerente AS gerente, 
            mc.contacto AS medio_contacto, n.negocio AS negocio
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
    $types = ''; // Inicializar $types

    // Agregar filtros dinámicos
    if (!empty($filters['id_usuario'])) {
        $conditions[] = "l.id_usuario = ?";
        $params[] = $filters['id_usuario'];
        $types .= 'i';
    }

    if (!empty($filters['cliente'])) {
        $conditions[] = "c.empresa LIKE ?";
        $params[] = '%' . $filters['cliente'] . '%';
        $types .= 's';
    }

    if (!empty($filters['estatus'])) {
        $conditions[] = "e.id_estatus = ?";
        $params[] = (int)$filters['estatus'];
        $types .= 'i';
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

    // Bind dinámico de parámetros
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}



/* ------------------------------------------------------------------------------------------*/
    // Completar Lead
    public function completeLead($data) {
        $query = "
            UPDATE leads
            SET cotizacion = ?, archivo = ?, estatus = 'Completado'
            WHERE id = ?
        ";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            error_log("Error preparando la consulta SQL: " . $this->db->error);
            return false;
        }
        $stmt->bind_param("ssi", $data['cotizacion'], $data['archivo'], $data['id']);
        return $stmt->execute();
    }
    
/* ------------------------------------------------------------------------------------------*/
    // Obtener Etapas de Leads
    public function getEtapasLeads() {
        $query = "
            SELECT 
                e.estatus AS estatus, 
                COALESCE(COUNT(l.id), 0) AS conteo
            FROM estatusleads e
            LEFT JOIN leads l ON l.estatus = e.id_estatus
            GROUP BY e.id_estatus
            ORDER BY e.id_estatus
        ";

        $result = $this->db->query($query);
        if (!$result) {
            error_log("Error ejecutando la consulta SQL: " . $this->db->error);
            return [];
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
/* ------------------------------------------------------------------------------------------*/
    // Obtener contactos
    public function getContactos() {
        return $this->fetchAll("SELECT id_contacto, contacto FROM contactoleads");
    }
/* ------------------------------------------------------------------------------------------*/
   public function getPeriodos() {
    $result = $this->db->query("SELECT id_periodo, periodo FROM periodosleads");
    if (!$result) {
        error_log("Error SQL: " . $this->db->error);
        return [];
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}


/* ------------------------------------------------------------------------------------------*/

    // Obtener estatus
    public function getEstatus() {
        return $this->fetchAll("SELECT id_estatus, estatus FROM estatusleads");
    }
    
/* ------------------------------------------------------------------------------------------*/
    // Obtener gerentes
    public function getGerentes() {
        return $this->fetchAll("SELECT id_gerente, gerente FROM gerentesleads");
    }
    
/* ------------------------------------------------------------------------------------------*/
    // Obtener sucursales
    public function getSucursales() {
        return $this->fetchAll("SELECT id_sucursales, sucursal FROM sucursales");
    }
    
/* ------------------------------------------------------------------------------------------*/
    // Obtener negocios
    public function getNegocios() {
        return $this->fetchAll("SELECT id_negocio, negocio FROM negocioleads");
    }

   
/* ------------------------------------------------------------------------------------------*/
    // Método auxiliar para ejecutar consultas SELECT simples
    private function fetchAll($query) {
        $result = $this->db->query($query);
        if (!$result) {
            error_log("Error ejecutando la consulta SQL: " . $this->db->error);
            return [];
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    
    /* ------------------------------------------------------------------------------------------*/
public function validateForeignKey($table, $column, $value) {
    $query = "SELECT COUNT(*) as count FROM $table WHERE $column = ?";
    $stmt = $this->db->prepare($query);
    if (!$stmt) {
        error_log("Error preparando la consulta para $table: " . $this->db->error);
        return false;
    }
    $stmt->bind_param('i', $value);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['count'] > 0;
}

public function addLead($data) 
{
    // Asegurar valores predeterminados para claves foráneas
    $data['gerente_responsable'] = !empty($data['gerente_responsable']) ? $data['gerente_responsable'] : 0;
    $data['sucursal'] = !empty($data['sucursal']) ? $data['sucursal'] : 0;
    $data['medio_contacto'] = !empty($data['medio_contacto']) ? $data['medio_contacto'] : 0;
    $data['linea_negocio'] = !empty($data['linea_negocio']) ? $data['linea_negocio'] : 0;
    $data['periodo'] = !empty($data['periodo']) ? $data['periodo'] : 0;

    // Paso 1: Insertar en clientesleads
    $queryClientes = "
        INSERT INTO clientesleads (contacto, correo, telefono, giro, localidad, empresa, id_usuario, fechaCreacion)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
    ";
    $stmtClientes = $this->db->prepare($queryClientes);
    $stmtClientes->bind_param(
        'ssssssi',
        $data['contacto'],
        $data['correo'],
        $data['telefono'],
        $data['giro'],
        $data['localidad'],
        $data['empresa'],
        $data['id_usuario']
    );

    if (!$stmtClientes->execute()) {
        error_log("Error al insertar cliente: " . $stmtClientes->error);
        return false;
    }

    $idCliente = $this->db->insert_id;

    // Paso 2: Insertar en leads
    $queryLeads = "
        INSERT INTO leads (
            periodo, gerente_responsable, id_sucursal, fecha_generacion, 
            medio_contacto, estatus, linea_negocio, notas, id_usuario, id_cliente
        ) VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?)
    ";
    $stmtLeads = $this->db->prepare($queryLeads);
    $stmtLeads->bind_param(
        'iiiiiisii',
        $data['periodo'],
        $data['gerente_responsable'],
        $data['sucursal'],
        $data['medio_contacto'],
        $data['estatus'],
        $data['linea_negocio'],
        $data['notas'],
        $data['id_usuario'],
        $idCliente
    );

    if (!$stmtLeads->execute()) {
        error_log("Error al insertar lead: " . $stmtLeads->error);
        return false;
    }

    return true;
}



/* ------------------------------------------------------------------------------------------*/
    public function getLeadById($id) {
        $query = "SELECT * FROM leads WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
/* ------------------------------------------------------------------------------------------*/
//Modelo para obtener el historial de un lead
    public function getLeadHistory($id) {
        $query = "SELECT historial_cambios FROM leads WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return json_decode($result['historial_cambios'], true); // Decodifica el JSON
    }

/* ------------------------------------------------------------------------------------------*/

    public function getBranchData() {
        $sql = "
            SELECT sucursales.id_sucursales AS id_sucursales, 
                   sucursales.sucursal AS sucursal, 
                   COUNT(leads.id_cliente) AS conteo
            FROM sucursales
            LEFT JOIN leads ON sucursales.id_sucursales = leads.id_sucursal
            GROUP BY sucursales.id_sucursales, sucursales.sucursal
        ";
    
        $result = $this->db->query($sql);
    
        if ($result && $result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
    
        return [];
    }



/* ------------------------------------------------------------------------------------------*/
    public function getFilteredChartData($filters) {
        $query = "
            SELECT e.estatus AS label, COUNT(l.id) AS value
            FROM leads l
            LEFT JOIN estatusleads e ON l.estatus = e.id_estatus
        ";
    
        $conditions = [];
        $params = [];
        $types = '';
    
        // Aplicar filtros dinámicamente
        if (!empty($filters['gerente'])) {
            $conditions[] = "l.gerente_responsable = ?";
            $params[] = $filters['gerente'];
            $types .= 'i';
        }
        if (!empty($filters['sucursal'])) {
            $conditions[] = "l.id_sucursal = ?";
            $params[] = $filters['sucursal'];
            $types .= 'i';
        }
        if (!empty($filters['periodo'])) {
            $conditions[] = "l.periodo = ?";
            $params[] = $filters['periodo'];
            $types .= 'i';
        }
    
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(' AND ', $conditions);
        }
    
        $query .= " GROUP BY e.estatus ORDER BY COUNT(l.id) DESC";
    
        $stmt = $this->db->prepare($query);
        if ($types) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
    
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

/* ------------------------------------------------------------------------------------------*/
    public function getChartData($filters = []) {
        $query = "
            SELECT 
                estatus AS label, 
                COUNT(id) AS value
            FROM leads
        ";
    
        // Si hay filtros, agrégalos
        $conditions = [];
        $params = [];
        if (!empty($filters['gerente_responsable'])) {
            $conditions[] = "gerente_responsable = ?";
            $params[] = $filters['gerente_responsable'];
        }
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(' AND ', $conditions);
        }
    
        $query .= " GROUP BY estatus";
    
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            error_log("Error preparando consulta para gráfica: " . $this->db->error);
            return [];
        }
    
        if (!empty($params)) {
            $stmt->bind_param(str_repeat('i', count($params)), ...$params);
        }
    
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }






/* ------------------------------------------------------------------------------------------*/
/* ------------------------------------------------------------------------------------------*/
    //Función para actualizar eun lead con el boton de guardar de la tabla 
    public function updateLead($id, $data) {
    if (empty($id) || empty($data)) {
        error_log("Error en updateLead: ID o datos vacíos.");
        return false;
    }

    $setParts = [];
    $params = [];
    $types = '';

    foreach ($data as $field => $value) {
        $setParts[] = "$field = ?";
        $params[] = $value;
        $types .= is_int($value) ? 'i' : 's'; // Determinar tipo de dato
    }

    $params[] = $id;
    $types .= 'i';

    $query = "UPDATE leads SET " . implode(', ', $setParts) . " WHERE id = ?";
    $stmt = $this->db->prepare($query);

    if (!$stmt) {
        error_log("Error preparando la consulta: " . $this->db->error);
        return false;
    }

    $stmt->bind_param($types, ...$params);
    return $stmt->execute();
}

public function updateClienteLead($id, $data) {
    $setParts = [];
    $params = [];
    $types = '';

    foreach ($data as $field => $value) {
        $setParts[] = "$field = ?";
        $params[] = $value;
        $types .= is_int($value) ? 'i' : 's';
    }

    $query = "UPDATE clientesleads SET " . implode(', ', $setParts) . " WHERE id = (SELECT id_cliente FROM leads WHERE id = ?)";
    $params[] = $id;
    $types .= 'i';

    $stmt = $this->db->prepare($query);

    if (!$stmt) {
        error_log("Error preparando la consulta para actualizar clientesleads: " . $this->db->error);
        return false;
    }

    $stmt->bind_param($types, ...$params);
    return $stmt->execute();
}

    
  /* ------------------------------------------------------------------------------------------*/
  //Funcion para filtrar listas desplegables 
    public function getFilteredEstatus() {
    $query = "SELECT id_estatus, estatus FROM estatusleads WHERE id_estatus IN (1, 2, 3, 7,8)";
    $result = $this->db->query($query);

    if (!$result) {
        error_log("Error ejecutando la consulta de estatus: " . $this->db->error);
        return [];
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}



}
