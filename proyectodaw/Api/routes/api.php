<?php
include_once '../controllers/ProductoController.php';
include_once '../controllers/UsuarioController.php';

$productoController = new ProductoController();
$usuarioController = new UsuarioController();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['productos'])) {
        $productos = $productoController->obtenerProductos();
        $productos_array = [];
        while ($row = $productos->fetch(PDO::FETCH_ASSOC)) {
            $productos_array[] = $row;
        }
        echo json_encode($productos_array);
    } elseif (isset($_GET['usuarios'])) {
        $usuarios = $usuarioController->obtenerUsuarios();
        $usuarios_array = [];
        while ($row = $usuarios->fetch(PDO::FETCH_ASSOC)) {
            $usuarios_array[] = $row;
        }
        echo json_encode($usuarios_array);
    }
}


?>