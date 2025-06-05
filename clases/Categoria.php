<?php
require_once '../interfaces/CrudInterface.php';

class Categoria implements CrudInterface {

    public function crear() {
        return $this->create();
    }

    public function obtener($id) {
        return $this->read($id);
    }

    public function actualizar() {
        return $this->update();
    }

    public function eliminar($id) {
        return $this->delete($id);
    }
    private $conn;
    private $table_name = "categorias";

    // Propiedades
    public $id;
    public $nombre;
    public $descripcion;
    public $fecha_creacion;

    // Constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    // Implementación de métodos CRUD
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET nombre=:nombre, descripcion=:descripcion, fecha_creacion=:fecha_creacion";

        $stmt = $this->conn->prepare($query);

        // Sanitizar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->fecha_creacion = date('Y-m-d H:i:s');

        // Vincular valores
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":fecha_creacion", $this->fecha_creacion);

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
            $this->nombre = $row['nombre'];
            $this->descripcion = $row['descripcion'];
            $this->fecha_creacion = $row['fecha_creacion'];
            return true;
        }

        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET nombre = :nombre,
                    descripcion = :descripcion
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitizar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Vincular parámetros
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':descripcion', $this->descripcion);
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

        $query = "SELECT * FROM categorias ORDER BY nombre ASC";
        $stmt = $db->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Método adicional para verificar si una categoría tiene productos asociados
    public function hasProducts() {
        $query = "SELECT COUNT(*) as total FROM productos WHERE categoria_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total'] > 0;
    }
}
?>
