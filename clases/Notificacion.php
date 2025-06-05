<?php
require_once '../interfaces/CrudInterface.php';

class Notificacion implements CrudInterface {
    private $conn;
    private $table_name = "notificaciones";

    // Propiedades del objeto
    public $id;
    public $usuario_id;
    public $titulo;
    public $mensaje;
    public $tipo; // info, success, warning, error
    public $leida; // 0 o 1
    public $fecha_creacion;
    public $enlace; // URL opcional para redirigir

    // Constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    // Implementación de métodos CRUD
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET usuario_id=:usuario_id, titulo=:titulo, mensaje=:mensaje, 
                      tipo=:tipo, leida=:leida, fecha_creacion=:fecha_creacion, 
                      enlace=:enlace";

        $stmt = $this->conn->prepare($query);

        // Sanitizar datos
        $this->usuario_id = htmlspecialchars(strip_tags($this->usuario_id));
        $this->titulo = htmlspecialchars(strip_tags($this->titulo));
        $this->mensaje = htmlspecialchars(strip_tags($this->mensaje));
        $this->tipo = htmlspecialchars(strip_tags($this->tipo));
        $this->leida = 0; // Por defecto, no leída
        $this->fecha_creacion = date('Y-m-d H:i:s');
        $this->enlace = htmlspecialchars(strip_tags($this->enlace));

        // Vincular valores
        $stmt->bindParam(":usuario_id", $this->usuario_id);
        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":mensaje", $this->mensaje);
        $stmt->bindParam(":tipo", $this->tipo);
        $stmt->bindParam(":leida", $this->leida);
        $stmt->bindParam(":fecha_creacion", $this->fecha_creacion);
        $stmt->bindParam(":enlace", $this->enlace);

        // Ejecutar consulta
        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function read($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->id = $row['id'];
            $this->usuario_id = $row['usuario_id'];
            $this->titulo = $row['titulo'];
            $this->mensaje = $row['mensaje'];
            $this->tipo = $row['tipo'];
            $this->leida = $row['leida'];
            $this->fecha_creacion = $row['fecha_creacion'];
            $this->enlace = $row['enlace'];
            
            return true;
        }

        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET titulo = :titulo,
                    mensaje = :mensaje,
                    tipo = :tipo,
                    leida = :leida,
                    enlace = :enlace
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitizar datos
        $this->titulo = htmlspecialchars(strip_tags($this->titulo));
        $this->mensaje = htmlspecialchars(strip_tags($this->mensaje));
        $this->tipo = htmlspecialchars(strip_tags($this->tipo));
        $this->leida = htmlspecialchars(strip_tags($this->leida));
        $this->enlace = htmlspecialchars(strip_tags($this->enlace));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Vincular parámetros
        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':mensaje', $this->mensaje);
        $stmt->bindParam(':tipo', $this->tipo);
        $stmt->bindParam(':leida', $this->leida);
        $stmt->bindParam(':enlace', $this->enlace);
        $stmt->bindParam(':id', $this->id);

        // Ejecutar consulta
        if($stmt->execute()) {
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
        
        $query = "SELECT * FROM notificaciones ORDER BY fecha_creacion DESC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // Métodos adicionales
    public function getByUsuario($usuario_id, $solo_no_leidas = false) {
        $query = "SELECT * FROM " . $this->table_name . " 
                WHERE usuario_id = ?";
                
        if($solo_no_leidas) {
            $query .= " AND leida = 0";
        }
        
        $query .= " ORDER BY fecha_creacion DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $usuario_id);
        $stmt->execute();
        
        return $stmt;
    }
    
    public function marcarComoLeida($id) {
        $query = "UPDATE " . $this->table_name . " SET leida = 1 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        
        return $stmt->execute();
    }
    
    public function marcarTodasComoLeidas($usuario_id) {
        $query = "UPDATE " . $this->table_name . " SET leida = 1 WHERE usuario_id = ? AND leida = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $usuario_id);
        
        return $stmt->execute();
    }
    
    public function contarNoLeidas($usuario_id) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " 
                WHERE usuario_id = ? AND leida = 0";
                
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $usuario_id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'];
    }
    
    // Método para crear notificaciones para eventos del sistema
    public static function crearNotificacionPedido($db, $usuario_id, $pedido_id, $estado) {
        $notificacion = new Notificacion($db);
        $notificacion->usuario_id = $usuario_id;
        
        switch($estado) {
            case 'pendiente':
                $notificacion->titulo = "Nuevo pedido creado";
                $notificacion->mensaje = "Tu pedido #" . $pedido_id . " ha sido creado y está pendiente de pago.";
                $notificacion->tipo = "info";
                break;
            case 'en proceso':
                $notificacion->titulo = "Pedido en proceso";
                $notificacion->mensaje = "Tu pedido #" . $pedido_id . " está siendo procesado.";
                $notificacion->tipo = "info";
                break;
            case 'completado':
                $notificacion->titulo = "Pedido completado";
                $notificacion->mensaje = "Tu pedido #" . $pedido_id . " ha sido completado con éxito.";
                $notificacion->tipo = "success";
                break;
            case 'cancelado':
                $notificacion->titulo = "Pedido cancelado";
                $notificacion->mensaje = "Tu pedido #" . $pedido_id . " ha sido cancelado.";
                $notificacion->tipo = "warning";
                break;
        }
        
        $notificacion->enlace = "pedido.php?id=" . $pedido_id;
        
        return $notificacion->create();
    }
    
    public static function crearNotificacionPago($db, $usuario_id, $pedido_id, $estado) {
        $notificacion = new Notificacion($db);
        $notificacion->usuario_id = $usuario_id;
        
        switch($estado) {
            case 'pendiente':
                $notificacion->titulo = "Pago pendiente";
                $notificacion->mensaje = "Tu pago para el pedido #" . $pedido_id . " está pendiente de confirmación.";
                $notificacion->tipo = "info";
                break;
            case 'completado':
                $notificacion->titulo = "Pago confirmado";
                $notificacion->mensaje = "El pago para tu pedido #" . $pedido_id . " ha sido confirmado.";
                $notificacion->tipo = "success";
                break;
            case 'rechazado':
                $notificacion->titulo = "Pago rechazado";
                $notificacion->mensaje = "El pago para tu pedido #" . $pedido_id . " ha sido rechazado. Por favor, intenta con otro método de pago.";
                $notificacion->tipo = "error";
                break;
        }
        
        $notificacion->enlace = "pedido.php?id=" . $pedido_id;
        
        return $notificacion->create();
    }
}
?>