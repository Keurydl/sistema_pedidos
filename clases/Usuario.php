<?php
require_once '../interfaces/CrudInterface.php';

class Usuario implements CrudInterface {
    private $conn;
    private $table_name = "usuarios";

    // Propiedades del objeto
    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $direccion;
    public $rol; // admin, cliente, etc.
    public $fecha_registro;

    // Constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    // Implementación de métodos CRUD
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET nombre=:nombre, apellido=:apellido, email=:email, 
                      password=:password, telefono=:telefono, direccion=:direccion, 
                      rol=:rol, fecha_registro=:fecha_registro";

        $stmt = $this->conn->prepare($query);

        // Sanitizar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        $this->rol = htmlspecialchars(strip_tags($this->rol));
        $this->fecha_registro = date('Y-m-d H:i:s');

        // Vincular valores
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":apellido", $this->apellido);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":rol", $this->rol);
        $stmt->bindParam(":fecha_registro", $this->fecha_registro);

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
            $this->apellido = $row['apellido'];
            $this->email = $row['email'];
            $this->telefono = $row['telefono'];
            $this->direccion = $row['direccion'];
            $this->rol = $row['rol'];
            $this->fecha_registro = $row['fecha_registro'];
            return true;
        }

        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET nombre = :nombre,
                    apellido = :apellido,
                    email = :email,
                    telefono = :telefono,
                    direccion = :direccion,
                    rol = :rol
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitizar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        $this->rol = htmlspecialchars(strip_tags($this->rol));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Vincular parámetros
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':apellido', $this->apellido);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':direccion', $this->direccion);
        $stmt->bindParam(':rol', $this->rol);
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
        
        $query = "SELECT * FROM usuarios ORDER BY id DESC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // Métodos adicionales
    public function emailExists() {
        $query = "SELECT id, nombre, apellido, password, rol
                FROM " . $this->table_name . "
                WHERE email = ?
                LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        $num = $stmt->rowCount();

        if($num > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->id = $row['id'];
            $this->nombre = $row['nombre'];
            $this->apellido = $row['apellido'];
            $this->password = $row['password'];
            $this->rol = $row['rol'];
            
            return true;
        }

        return false;
    }
}
?>