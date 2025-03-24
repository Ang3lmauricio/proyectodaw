<?php
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController {
    private $db;
    private $usuario;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->usuario = new Usuario($this->db);
    }

    public function obtenerUsuarioPorEmail($email) {
        return $this->usuario->obtenerPorEmail($email);
    }

    public function registrarUsuario($nombre, $email, $contrasena, $tipo = 'cliente') {
        return $this->usuario->crear($nombre, $email, $contrasena, $tipo);
    }

    public function obtenerUsuarios() {
        return $this->usuario->obtenerTodos();
    }

    public function actualizarUsuario($id, $nombre, $email) {
        return $this->usuario->actualizar($id, $nombre, $email);
    }
}
?>