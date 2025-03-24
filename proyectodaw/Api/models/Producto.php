<?php
class Producto {
    private $conn;
    private $tabla = "productos";

    public $id;
    public $nombre;
    public $descripcion;
    public $precio;
    public $stock;
    public $categoria_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerTodos() {
        $query = "SELECT * FROM " . $this->tabla;
        return $this->conn->query($query);
    }
}

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Obtener el cuerpo de la petición
    $datos = json_decode(file_get_contents('php://input'), true);
    $id = $datos['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['mensaje' => 'ID no proporcionado']);
        exit;
    }

    try {
        $conexion = new PDO("mysql:host=localhost;dbname=tu_base_de_datos", "usuario", "contraseña");
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $conexion->prepare("DELETE FROM productos WHERE id = ?");
        $resultado = $stmt->execute([$id]);
        
        if ($resultado && $stmt->rowCount() > 0) {
            echo json_encode(['mensaje' => 'Producto eliminado correctamente']);
        } else {
            http_response_code(404);
            echo json_encode(['mensaje' => 'No se encontró el producto']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['mensaje' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['mensaje' => 'Método no permitido']);
}

?>