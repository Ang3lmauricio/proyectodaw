<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");

include_once __DIR__ . '/config/db.php';
include_once __DIR__ . '/models/Producto.php';
include_once __DIR__ . '/controllers/ProductoController.php';

include_once __DIR__ . '/controllers/UsuarioController.php';


$productoController = new ProductoController();
$usuarioController = new UsuarioController();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['productos'])) {
        $productos = $productoController->obtenerProductos();
        if (is_array($productos)) {
            echo json_encode($productos);
        } else {
            $productos_array = [];
            while ($row = $productos->fetch(PDO::FETCH_ASSOC)) {
                $productos_array[] = $row;
            }
            echo json_encode($productos_array);
        }
    } elseif (isset($_GET['usuarios'])) {
        $usuarios = $usuarioController->obtenerUsuarios();
        $usuarios_array = [];
        while ($row = $usuarios->fetch(PDO::FETCH_ASSOC)) {
            $usuarios_array[] = $row;
        }
        echo json_encode($usuarios_array);
    }

   
   
    
    } elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        // Obtener datos JSON del cuerpo de la petición
        $data = json_decode(file_get_contents('php://input'), true);
        
        if ($data && isset($data['id'])) {
            $resultado = $productoController->actualizarProducto(
                $data['id'],
                $data['nombre'] ?? '',
                $data['descripcion'] ?? '',
                $data['precio'] ?? 0,
                $data['stock'] ?? 0,
                $data['categoria_id'] ?? 1
            );
            if ($resultado) {
                echo json_encode(["success" => true, "message" => "Producto actualizado"]);
            } else {
                echo json_encode(["success" => false, "message" => "Error al actualizar"]);
            }
        } else {
            echo json_encode(["error" => "Datos inválidos"]);
        }
    
    } elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            $resultado = $productoController->eliminarProducto($id);
            if ($resultado) {
                echo json_encode(["success" => true, "message" => "Producto eliminado"]);
            } else {
                echo json_encode(["success" => false, "message" => "Error al eliminar"]);
            }
        } else {
            echo json_encode(["error" => "ID no proporcionado"]);
        }
    }
    ?>
?>