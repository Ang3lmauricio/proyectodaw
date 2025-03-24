<?php
class Usuario {
    private $conn;
    private $tabla = "usuarios";

    public $id;
    public $nombre;
    public $email;
    public $contrasena;
    public $tipo;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerPorEmail($email) {
        $query = "SELECT * FROM " . $this->tabla . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($nombre, $email, $contrasena, $tipo = 'cliente') {
        try {
            // Verificar si el email ya existe
            if ($this->obtenerPorEmail($email)) {
                return false;
            }

            $query = "INSERT INTO " . $this->tabla . " 
                     (nombre, email, contrasena, tipo) 
                     VALUES (:nombre, :email, :contrasena, :tipo)";

            $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":contrasena", $contrasena_hash);
            $stmt->bindParam(":tipo", $tipo);

            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }

    public function actualizar($id, $nombre, $email, $contrasena = null) {
        try {
            if ($contrasena) {
                $query = "UPDATE " . $this->tabla . " 
                         SET nombre = :nombre, 
                             email = :email, 
                             contrasena = :contrasena 
                         WHERE id = :id";
                
                $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
                
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":contrasena", $contrasena_hash);
            } else {
                $query = "UPDATE " . $this->tabla . " 
                         SET nombre = :nombre, 
                             email = :email 
                         WHERE id = :id";
                
                $stmt = $this->conn->prepare($query);
            }

            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":email", $email);

            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }

    public function eliminar($id) {
        try {
            $query = "DELETE FROM " . $this->tabla . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }

    public function obtenerTodos() {
        try {
            $query = "SELECT id, nombre, email, tipo FROM " . $this->tabla;
            return $this->conn->query($query);
        } catch(PDOException $e) {
            return false;
        }
    }

    public function autenticar($email, $contrasena) {
        $usuario = $this->obtenerPorEmail($email);
        
        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            return $usuario;
        }
        
        return false;
    }

    public function cambiarContrasena($id, $nueva_contrasena) {
        try {
            $query = "UPDATE " . $this->tabla . " 
                     SET contrasena = :contrasena 
                     WHERE id = :id";
            
            $contrasena_hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":contrasena", $contrasena_hash);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }
}
?>