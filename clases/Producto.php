<?php
require_once '../interfaces/CrudInterface.php';

class Producto implements CrudInterface {
    private $conn;
    private $table_name = "productos";

    // Propiedades del objeto
    public $id;
    public $nombre;
    public $descripcion;
    public $precio;
    public $stock;
    public $imagen;
    public $categoria_id;
    public $fecha_creacion;

    // Constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    // Implementación de métodos CRUD
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET nombre=:nombre, descripcion=:descripcion, precio=:precio, 
                      stock=:stock, imagen=:imagen, categoria_id=:categoria_id, 
                      fecha_creacion=:fecha_creacion";

        $stmt = $this->conn->prepare($query);

        // Sanitizar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->precio = htmlspecialchars(strip_tags($this->precio));
        $this->stock = htmlspecialchars(strip_tags($this->stock));
        $this->imagen = htmlspecialchars(strip_tags($this->imagen));
        $this->categoria_id = htmlspecialchars(strip_tags($this->categoria_id));
        $this->fecha_creacion = date('Y-m-d H:i:s');

        // Vincular valores
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":precio", $this->precio);
        $stmt->bindParam(":stock", $this->stock);
        $stmt->bindParam(":imagen", $this->imagen);
        $stmt->bindParam(":categoria_id", $this->categoria_id);
        $stmt->bindParam(":fecha_creacion", $this->fecha_creacion);

        // Ejecutar consulta
        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function read($id) {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                FROM " . $this->table_name . " p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                WHERE p.id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->id = $row['id'];
            $this->nombre = $row['nombre'];
            $this->descripcion = $row['descripcion'];
            $this->precio = $row['precio'];
            $this->stock = $row['stock'];
            $this->imagen = $row['imagen'];
            $this->categoria_id = $row['categoria_id'];
            //$this->categoria_nombre = $row['categoria_nombre'];
            $this->fecha_creacion = $row['fecha_creacion'];
            return true;
        }

        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET nombre = :nombre,
                    descripcion = :descripcion,
                    precio = :precio,
                    stock = :stock,
                    imagen = :imagen,
                    categoria_id = :categoria_id
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitizar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->precio = htmlspecialchars(strip_tags($this->precio));
        $this->stock = htmlspecialchars(strip_tags($this->stock));
        $this->imagen = htmlspecialchars(strip_tags($this->imagen));
        $this->categoria_id = htmlspecialchars(strip_tags($this->categoria_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Vincular parámetros
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':precio', $this->precio);
        $stmt->bindParam(':stock', $this->stock);
        $stmt->bindParam(':imagen', $this->imagen);
        $stmt->bindParam(':categoria_id', $this->categoria_id);
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
        
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                FROM productos p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                ORDER BY p.id DESC";
                
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // Métodos adicionales
    public function searchByName($keywords) {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                FROM " . $this->table_name . " p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                WHERE p.nombre LIKE ? OR p.descripcion LIKE ?
                ORDER BY p.id DESC";

        $stmt = $this->conn->prepare($query);
        
        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";
        
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        
        $stmt->execute();
        
        return $stmt;
    }

    public function getByCategory($categoria_id) {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                FROM " . $this->table_name . " p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                WHERE p.categoria_id = ?
                ORDER BY p.id DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $categoria_id);
        $stmt->execute();
        
        return $stmt;
    }
}
?>