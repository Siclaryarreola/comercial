<?php
require_once('../config/database.php');

class LeadsManagementModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }


    /* -------------------------------------------------*/

    public function getAll($tableName) {
        $query = "SELECT * FROM {$tableName}";
        $result = $this->db->query($query);

        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC) ?: [];
        }

        error_log("Error en getAll ({$tableName}): " . $this->db->error);
        return [];
    }
    
    
    /* -------------------------------------------------*/

    public function add($tableName, $data) {
        if (empty($data) || !is_array($data)) {
            error_log("Datos inválidos para agregar en {$tableName}");
            return false;
        }

        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), '?'));
        $query = "INSERT INTO {$tableName} ({$columns}) VALUES ({$placeholders})";

        $stmt = $this->db->prepare($query);
        if ($stmt) {
            $types = '';
            $values = [];
            foreach ($data as $value) {
                $types .= $this->getParamType($value);
                $values[] = $value;
            }

            $stmt->bind_param($types, ...$values);
            return $stmt->execute();
        }

        error_log("Error al preparar add ({$tableName}): " . $this->db->error);
        return false;
    }
    
    /* -------------------------------------------------*/

    public function update($tableName, $data, $id) {
        if (empty($data) || !$id) {
            error_log("Datos inválidos para actualizar en {$tableName}");
            return false;
        }

        $setClause = implode(", ", array_map(function ($key) {
            return "{$key} = ?";
        }, array_keys($data)));

        $query = "UPDATE {$tableName} SET {$setClause} WHERE id = ?";

        $stmt = $this->db->prepare($query);
        if ($stmt) {
            $types = '';
            $values = [];
            foreach ($data as $value) {
                $types .= $this->getParamType($value);
                $values[] = $value;
            }

            $types .= 'i';
            $values[] = (int)$id;

            $stmt->bind_param($types, ...$values);
            return $stmt->execute();
        }

        error_log("Error al preparar update ({$tableName}): " . $this->db->error);
        return false;
    }


    /* -------------------------------------------------*/

    public function delete($tableName, $id) {
        if (!$id) {
            error_log("ID inválido para eliminar en {$tableName}");
            return false;
        }

        $query = "DELETE FROM {$tableName} WHERE id = ?";
        $stmt = $this->db->prepare($query);
        if ($stmt) {
            $stmt->bind_param('i', $id);
            return $stmt->execute();
        }

        error_log("Error al preparar delete ({$tableName}): " . $this->db->error);
        return false;
    }


    /* -------------------------------------------------*/

    private function getParamType($var) {
        if (is_int($var)) return 'i';
        if (is_float($var)) return 'd';
        return 's';
    }
    
    
        /* -------------------------------------------------*/
public function getLeadsWithRelations() {
    $query = "
        SELECT 
            leads.id,
            clientesleads.contacto AS nombre_cliente, -- Nombre del cliente desde clientesleads
            negocioleads.negocio AS linea_negocio,    -- Traer el nombre del negocio desde negocioleads
            leads.id_sucursal,
            leads.gerente_responsable,
            sucursales.sucursal AS sucursal
        FROM leads
        LEFT JOIN clientesleads ON leads.id_cliente = clientesleads.id -- Relación con clientesleads
        LEFT JOIN sucursales ON leads.id_sucursal = sucursales.id_sucursales -- Relación con sucursales
        LEFT JOIN negocioleads ON leads.linea_negocio = negocioleads.id_negocio -- Relación con negocioleads
    ";

    $result = $this->db->query($query);

    if ($result) {
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    return [];
}


    /* -------------------------------------------------*/



}
