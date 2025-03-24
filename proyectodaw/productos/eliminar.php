<!-- filepath: c:\xampp2\htdocs\proyectodaw\productos\eliminarproducto.php -->
<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "licoreria";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID del producto desde el formulario
$product_id = $_POST['product_id'];

// Preparar y ejecutar la consulta SQL para eliminar el producto
$sql = "DELETE FROM productos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);

if ($stmt->execute()) {
    echo "Producto eliminado exitosamente.";
} else {
    echo "Error al eliminar el producto: " . $conn->error;
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>