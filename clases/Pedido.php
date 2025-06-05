<?php
require_once '../interfaces/CrudInterface.php';

class Pedido implements CrudInterface {
    private $conn;
    private $table_name = "pedidos";

    // Propiedades del objeto
    public $id;
    public $usuario_id;
    public $fecha_pedido;
    public $estado; // pendiente, en proceso, completado, cancelado
    public $total;
    public $direccion_envio;
    public $metodo_pago;
    public $notas;

    // Constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    // Implementación de métodos CRUD
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET usuario_id=:usuario_id, fecha_pedido=:fecha_pedido, 
                      estado=:estado, total=:total, direccion_envio=:direccion_envio, 
                      metodo_pago=:metodo_pago, notas=:notas";

        $stmt = $this->conn->prepare($query);

        // Sanitizar datos
        $this->usuario_id = htmlspecialchars(strip_tags($this->usuario_id));
        $this->fecha_pedido = date('Y-m-d H:i:s');
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->total = htmlspecialchars(strip_tags($this->total));
        $this->direccion_envio = htmlspecialchars(strip_tags($this->direccion_envio));
        $this->metodo_pago = htmlspecialchars(strip_tags($this->metodo_pago));
        $this->notas = htmlspecialchars(strip_tags($this->notas));

        // Vincular valores
        $stmt->bindParam(":usuario_id", $this->usuario_id);
        $stmt->bindParam(":fecha_pedido", $this->fecha_pedido);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":total", $this->total);
        $stmt->bindParam(":direccion_envio", $this->direccion_envio);
        $stmt->bindParam(":metodo_pago", $this->metodo_pago);
        $stmt->bindParam(":notas", $this->notas);

        // Ejecutar consulta
        if($stmt->execute()) {
            // Obtener el ID del pedido recién creado
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    public function read($id) {
        $query = "SELECT p.*, u.nombre, u.apellido, u.email 
                FROM " . $this->table_name . " p
                LEFT JOIN usuarios u ON p.usuario_id = u.id
                WHERE p.id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->id = $row['id'];
            $this->usuario_id = $row['usuario_id'];
            $this->fecha_pedido = $row['fecha_pedido'];
            $this->estado = $row['estado'];
            $this->total = $row['total'];
            $this->direccion_envio = $row['direccion_envio'];
            $this->metodo_pago = $row['metodo_pago'];
            $this->notas = $row['notas'];
            
            // Información del usuario
            //$this->nombre_usuario = $row['nombre'] . ' ' . $row['apellido'];
            //$this->email_usuario = $row['email'];
            
            return true;
        }

        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET estado = :estado,
                    direccion_envio = :direccion_envio,
                    metodo_pago = :metodo_pago,
                    notas = :notas
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitizar datos
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->direccion_envio = htmlspecialchars(strip_tags($this->direccion_envio));
        $this->metodo_pago = htmlspecialchars(strip_tags($this->metodo_pago));
        $this->notas = htmlspecialchars(strip_tags($this->notas));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Vincular parámetros
        $stmt->bindParam(':estado', $this->estado);
        $stmt->bindParam(':direccion_envio', $this->direccion_envio);
        $stmt->bindParam(':metodo_pago', $this->metodo_pago);
        $stmt->bindParam(':notas', $this->notas);
        $stmt->bindParam(':id', $this->id);

        // Ejecutar consulta
        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function delete($id) {
        // Primero eliminar los detalles del pedido
        $query = "DELETE FROM detalle_pedidos WHERE pedido_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        // Luego eliminar el pedido
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
        
        $query = "SELECT p.*, u.nombre, u.apellido 
                FROM pedidos p
                LEFT JOIN usuarios u ON p.usuario_id = u.id
                ORDER BY p.fecha_pedido DESC";
                
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // Métodos adicionales
    public function getByUsuario($usuario_id) {
        $query = "SELECT * FROM " . $this->table_name . " 
                WHERE usuario_id = ?
                ORDER BY fecha_pedido DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $usuario_id);
        $stmt->execute();
        
        return $stmt;
    }
    
    public function getByEstado($estado) {
        $query = "SELECT p.*, u.nombre, u.apellido 
                FROM " . $this->table_name . " p
                LEFT JOIN usuarios u ON p.usuario_id = u.id
                WHERE p.estado = ?
                ORDER BY p.fecha_pedido DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $estado);
        $stmt->execute();
        
        return $stmt;
    }
    
    public function actualizarTotal($pedido_id) {
        $query = "SELECT SUM(cantidad * precio_unitario) as total 
                FROM detalle_pedidos 
                WHERE pedido_id = ?";
                
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $pedido_id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $total = $row['total'];
            
            $query = "UPDATE " . $this->table_name . " SET total = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $total);
            $stmt->bindParam(2, $pedido_id);
            
            if($stmt->execute()) {
                return true;
            }
        }
        
        return false;
    }
}
?>