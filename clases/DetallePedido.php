<?php
require_once '../interfaces/CrudInterface.php';

class DetallePedido implements CrudInterface {
    private $conn;
    private $table_name = "detalle_pedidos";

    // Propiedades del objeto
    public $id;
    public $pedido_id;
    public $producto_id;
    public $cantidad;
    public $precio_unitario;
    public $subtotal;

    // Constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    // Implementación de métodos CRUD
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET pedido_id=:pedido_id, producto_id=:producto_id, 
                      cantidad=:cantidad, precio_unitario=:precio_unitario";

        $stmt = $this->conn->prepare($query);

        // Sanitizar datos
        $this->pedido_id = htmlspecialchars(strip_tags($this->pedido_id));
        $this->producto_id = htmlspecialchars(strip_tags($this->producto_id));
        $this->cantidad = htmlspecialchars(strip_tags($this->cantidad));
        $this->precio_unitario = htmlspecialchars(strip_tags($this->precio_unitario));

        // Vincular valores
        $stmt->bindParam(":pedido_id", $this->pedido_id);
        $stmt->bindParam(":producto_id", $this->producto_id);
        $stmt->bindParam(":cantidad", $this->cantidad);
        $stmt->bindParam(":precio_unitario", $this->precio_unitario);

        // Ejecutar consulta
        if($stmt->execute()) {
            // Actualizar el stock del producto
            $this->actualizarStockProducto($this->producto_id, $this->cantidad, 'restar');
            
            // Actualizar el total del pedido
            $pedido = new Pedido($this->conn);
            $pedido->actualizarTotal($this->pedido_id);
            
            return true;
        }

        return false;
    }

    public function read($id) {
        $query = "SELECT d.*, p.nombre as producto_nombre, p.imagen as producto_imagen 
                FROM " . $this->table_name . " d
                LEFT JOIN productos p ON d.producto_id = p.id
                WHERE d.id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->id = $row['id'];
            $this->pedido_id = $row['pedido_id'];
            $this->producto_id = $row['producto_id'];
            $this->cantidad = $row['cantidad'];
            $this->precio_unitario = $row['precio_unitario'];
            //$this->producto_nombre = $row['producto_nombre'];
            //$this->producto_imagen = $row['producto_imagen'];
            $this->subtotal = $row['cantidad'] * $row['precio_unitario'];
            
            return true;
        }

        return false;
    }

    public function update() {
        // Obtener la cantidad actual antes de actualizar
        $query = "SELECT cantidad FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $cantidad_anterior = $row['cantidad'];
        
        // Actualizar el detalle del pedido
        $query = "UPDATE " . $this->table_name . "
                SET cantidad = :cantidad
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitizar datos
        $this->cantidad = htmlspecialchars(strip_tags($this->cantidad));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Vincular parámetros
        $stmt->bindParam(':cantidad', $this->cantidad);
        $stmt->bindParam(':id', $this->id);

        // Ejecutar consulta
        if($stmt->execute()) {
            // Ajustar el stock del producto
            $diferencia = $this->cantidad - $cantidad_anterior;
            if($diferencia > 0) {
                // Si aumentó la cantidad, restar del stock
                $this->actualizarStockProducto($this->producto_id, abs($diferencia), 'restar');
            } else if($diferencia < 0) {
                // Si disminuyó la cantidad, sumar al stock
                $this->actualizarStockProducto($this->producto_id, abs($diferencia), 'sumar');
            }
            
            // Actualizar el total del pedido
            $pedido = new Pedido($this->conn);
            $pedido->actualizarTotal($this->pedido_id);
            
            return true;
        }

        return false;
    }

    public function delete($id) {
        // Obtener información del detalle antes de eliminar
        $query = "SELECT pedido_id, producto_id, cantidad FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $pedido_id = $row['pedido_id'];
            $producto_id = $row['producto_id'];
            $cantidad = $row['cantidad'];
            
            // Eliminar el detalle
            $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id);
            
            if($stmt->execute()) {
                // Devolver el stock al producto
                $this->actualizarStockProducto($producto_id, $cantidad, 'sumar');
                
                // Actualizar el total del pedido
                $pedido = new Pedido($this->conn);
                $pedido->actualizarTotal($pedido_id);
                
                return true;
            }
        }

        return false;
    }

    public static function getAll() {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "SELECT d.*, p.nombre as producto_nombre 
                FROM detalle_pedidos d
                LEFT JOIN productos p ON d.producto_id = p.id
                ORDER BY d.id DESC";
                
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // Métodos adicionales
    public function getByPedido($pedido_id) {
        $query = "SELECT d.*, p.nombre as producto_nombre, p.imagen as producto_imagen 
                FROM " . $this->table_name . " d
                LEFT JOIN productos p ON d.producto_id = p.id
                WHERE d.pedido_id = ?
                ORDER BY d.id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $pedido_id);
        $stmt->execute();
        
        return $stmt;
    }
    
    private function actualizarStockProducto($producto_id, $cantidad, $operacion) {
        // Obtener el stock actual del producto
        $query = "SELECT stock FROM productos WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $producto_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $stock_actual = $row['stock'];
            $nuevo_stock = 0;
            
            if($operacion == 'restar') {
                $nuevo_stock = $stock_actual - $cantidad;
            } else if($operacion == 'sumar') {
                $nuevo_stock = $stock_actual + $cantidad;
            }
            
            // Actualizar el stock del producto
            $query = "UPDATE productos SET stock = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $nuevo_stock);
            $stmt->bindParam(2, $producto_id);
            
            return $stmt->execute();
        }
        
        return false;
    }
    
    public function verificarStock($producto_id, $cantidad_requerida) {
        $query = "SELECT stock FROM productos WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $producto_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            return $row['stock'] >= $cantidad_requerida;
        }
        
        return false;
    }
    
    public function calcularSubtotal() {
        return $this->cantidad * $this->precio_unitario;
    }
}
?>