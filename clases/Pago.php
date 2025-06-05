<?php
require_once '../interfaces/CrudInterface.php';

class Pago implements CrudInterface {
    private $conn;
    private $table_name = "pagos";

    // Propiedades del objeto
    public $id;
    public $pedido_id;
    public $monto;
    public $metodo; // efectivo, tarjeta, transferencia, etc.
    public $estado; // pendiente, completado, rechazado
    public $referencia;
    public $fecha_pago;
    public $notas;

    // Constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    // Implementación de métodos CRUD
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET pedido_id=:pedido_id, monto=:monto, metodo=:metodo, 
                      estado=:estado, referencia=:referencia, fecha_pago=:fecha_pago, 
                      notas=:notas";

        $stmt = $this->conn->prepare($query);

        // Sanitizar datos
        $this->pedido_id = htmlspecialchars(strip_tags($this->pedido_id));
        $this->monto = htmlspecialchars(strip_tags($this->monto));
        $this->metodo = htmlspecialchars(strip_tags($this->metodo));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->referencia = htmlspecialchars(strip_tags($this->referencia));
        $this->fecha_pago = date('Y-m-d H:i:s');
        $this->notas = htmlspecialchars(strip_tags($this->notas));

        // Vincular valores
        $stmt->bindParam(":pedido_id", $this->pedido_id);
        $stmt->bindParam(":monto", $this->monto);
        $stmt->bindParam(":metodo", $this->metodo);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":referencia", $this->referencia);
        $stmt->bindParam(":fecha_pago", $this->fecha_pago);
        $stmt->bindParam(":notas", $this->notas);

        // Ejecutar consulta
        if($stmt->execute()) {
            // Si el pago es completado, actualizar el estado del pedido
            if($this->estado == 'completado') {
                $this->actualizarEstadoPedido($this->pedido_id, 'en proceso');
            }
            
            return true;
        }

        return false;
    }

    public function read($id) {
        $query = "SELECT p.*, o.total as pedido_total 
                FROM " . $this->table_name . " p
                LEFT JOIN pedidos o ON p.pedido_id = o.id
                WHERE p.id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->id = $row['id'];
            $this->pedido_id = $row['pedido_id'];
            $this->monto = $row['monto'];
            $this->metodo = $row['metodo'];
            $this->estado = $row['estado'];
            $this->referencia = $row['referencia'];
            $this->fecha_pago = $row['fecha_pago'];
            $this->notas = $row['notas'];
            //$this->pedido_total = $row['pedido_total'];
            
            return true;
        }

        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET monto = :monto,
                    metodo = :metodo,
                    estado = :estado,
                    referencia = :referencia,
                    notas = :notas
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitizar datos
        $this->monto = htmlspecialchars(strip_tags($this->monto));
        $this->metodo = htmlspecialchars(strip_tags($this->metodo));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->referencia = htmlspecialchars(strip_tags($this->referencia));
        $this->notas = htmlspecialchars(strip_tags($this->notas));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Vincular parámetros
        $stmt->bindParam(':monto', $this->monto);
        $stmt->bindParam(':metodo', $this->metodo);
        $stmt->bindParam(':estado', $this->estado);
        $stmt->bindParam(':referencia', $this->referencia);
        $stmt->bindParam(':notas', $this->notas);
        $stmt->bindParam(':id', $this->id);

        // Ejecutar consulta
        if($stmt->execute()) {
            // Si el pago es completado, actualizar el estado del pedido
            if($this->estado == 'completado') {
                $this->actualizarEstadoPedido($this->pedido_id, 'en proceso');
            } else if($this->estado == 'rechazado') {
                $this->actualizarEstadoPedido($this->pedido_id, 'pendiente');
            }
            
            return true;
        }

        return false;
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    public static function getAll() {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "SELECT p.*, o.total as pedido_total 
                FROM pagos p
                LEFT JOIN pedidos o ON p.pedido_id = o.id
                ORDER BY p.fecha_pago DESC";
                
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // Métodos adicionales
    public function getByPedido($pedido_id) {
        $query = "SELECT * FROM " . $this->table_name . " 
                WHERE pedido_id = ?
                ORDER BY fecha_pago DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $pedido_id);
        $stmt->execute();
        
        return $stmt;
    }
    
    public function getByEstado($estado) {
        $query = "SELECT p.*, o.total as pedido_total 
                FROM " . $this->table_name . " p
                LEFT JOIN pedidos o ON p.pedido_id = o.id
                WHERE p.estado = ?
                ORDER BY p.fecha_pago DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $estado);
        $stmt->execute();
        
        return $stmt;
    }
    
    private function actualizarEstadoPedido($pedido_id, $nuevo_estado) {
        $query = "UPDATE pedidos SET estado = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $nuevo_estado);
        $stmt->bindParam(2, $pedido_id);
        
        return $stmt->execute();
    }
}
?>