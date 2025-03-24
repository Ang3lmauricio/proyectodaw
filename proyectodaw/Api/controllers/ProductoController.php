<?php
require_once __DIR__ . '/../config/db.php';

class ProductoController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    // Métodos para Productos
    public function obtenerProductos() {
        try {
            $query = "SELECT p.*, c.nombre as categoria_nombre 
                     FROM productos p 
                     LEFT JOIN categorias c ON p.categoria_id = c.id";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function obtenerProductoPorId($id) {
        try {
            $query = "SELECT p.*, c.nombre as categoria_nombre 
                     FROM productos p 
                     LEFT JOIN categorias c ON p.categoria_id = c.id 
                     WHERE p.id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function agregarProducto($nombre, $descripcion, $precio, $stock, $categoria_id) {
        try {
            $query = "INSERT INTO productos (nombre, descripcion, precio, stock, categoria_id) 
                     VALUES (:nombre, :descripcion, :precio, :stock, :categoria_id)";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':precio', $precio);
            $stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':categoria_id', $categoria_id);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function actualizarProducto($id, $nombre, $descripcion, $precio, $stock, $categoria_id) {
        try {
            $query = "UPDATE productos 
                     SET nombre = :nombre, 
                         descripcion = :descripcion, 
                         precio = :precio, 
                         stock = :stock, 
                         categoria_id = :categoria_id 
                     WHERE id = :id";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':precio', $precio);
            $stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':categoria_id', $categoria_id);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function eliminarProducto($id) {
        try {
            $query = "DELETE FROM productos WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function buscarProductos($filtros = []) {
        try {
            $query = "SELECT id, nombre, descripcion FROM productos WHERE 1=1";
            $params = [];
            
            if (!empty($filtros['id'])) {
                $query .= " AND id = :id";
                $params[':id'] = $filtros['id'];
            }
    
            $stmt = $this->db->prepare($query);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function obtenerCategorias() {
        try {
            $query = "SELECT * FROM categorias";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }
}

// Endpoints para las operaciones CRUD
if (isset($_GET['action'])) {
    // Limpiar cualquier salida previa
    ob_clean();
    
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');

    $controller = new ProductoController();
    
    try {
        switch ($_GET['action']) {
            case 'obtener':
                $result = $controller->obtenerProductos();
                echo json_encode([
                    'success' => true,
                    'data' => $result
                ]);
                break;
                
            case 'obtener_uno':
                if (!isset($_GET['id'])) {
                    throw new Exception('ID no proporcionado');
                }
                $result = $controller->obtenerProductoPorId($_GET['id']);
                if (!$result) {
                    throw new Exception('Producto no encontrado');
                }
                echo json_encode([
                    'success' => true,
                    'data' => $result
                ]);
                break;
                
            case 'crear':
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    throw new Exception('Método no permitido');
                }
                $resultado = $controller->agregarProducto(
                    $_POST['nombre'] ?? '',
                    $_POST['descripcion'] ?? '',
                    $_POST['precio'] ?? 0,
                    $_POST['stock'] ?? 0,
                    $_POST['categoria_id'] ?? null
                );
                echo json_encode([
                    'success' => $resultado,
                    'message' => $resultado ? 'Producto creado con éxito' : 'Error al crear el producto'
                ]);
                break;
                
            case 'actualizar':
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    throw new Exception('Método no permitido');
                }
                if (!isset($_POST['id'])) {
                    throw new Exception('ID no proporcionado');
                }
                $resultado = $controller->actualizarProducto(
                    $_POST['id'],
                    $_POST['nombre'] ?? '',
                    $_POST['descripcion'] ?? '',
                    $_POST['precio'] ?? 0,
                    $_POST['stock'] ?? 0,
                    $_POST['categoria_id'] ?? null
                );
                echo json_encode([
                    'success' => $resultado,
                    'message' => $resultado ? 'Producto actualizado con éxito' : 'Error al actualizar el producto'
                ]);
                break;
                
            // En la sección del switch de acciones
case 'eliminar':
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode([
            'success' => false,
            'message' => 'Método no permitido'
        ]);
        exit;
    }

    if (!isset($_POST['id'])) {
        echo json_encode([
            'success' => false,
            'message' => 'ID no proporcionado'
        ]);
        exit;
    }

    $resultado = $controller->eliminarProducto($_POST['id']);
    
    echo json_encode([
        'success' => $resultado,
        'message' => $resultado ? 'Producto eliminado correctamente' : 'Error al eliminar el producto'
    ]);
    break;
                
            case 'buscar':
                $filtros = array_filter($_GET, function($key) {
                    return $key !== 'action';
                }, ARRAY_FILTER_USE_KEY);
                
                $resultados = $controller->buscarProductos($filtros);
                echo json_encode([
                    'success' => true,
                    'data' => $resultados
                ]);
                break;
                
            case 'categorias':
                $categorias = $controller->obtenerCategorias();
                echo json_encode([
                    'success' => true,
                    'data' => $categorias
                ]);
                break;
                
            default:
                throw new Exception('Acción no válida');
        }
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
    exit;
}
?>