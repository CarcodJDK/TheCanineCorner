<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    echo "<script>alert('Debe iniciar sesión para ver sus productos.'); window.location.href='login.html';</script>";
    exit;
}

$host_name = 'db5017003380.hosting-data.io';
$database = 'dbs13697465';
$user_name = 'dbu1433845';
$password = 'diegoelperpetrador_asir2025';

$conn = new mysqli($host_name, $user_name, $password, $database);
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

$correo = $_SESSION['usuario'];

$query = "SELECT v.nombre_producto, v.cantidad, v.precio_unitario, v.fecha
          FROM ventas v
          JOIN clientes c ON v.id_cliente = c.id_cliente 
          WHERE c.correo = ?
          ORDER BY v.fecha DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param('s', $correo);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>My Products - Dog Products Store</title>
    <link rel="stylesheet" href="home.css">
    <style>
        .product-card {
            border: 1px solid #ccc;
            padding: 16px;
            margin-bottom: 16px;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .product-card h3 {
            margin-top: 0;
        }
    </style>
</head>
<body>
<header>
    <h1>My Products</h1>
</header>

<nav>
    <div class="left-nav">
        <a href="index.html">Home</a>
        <a href="tienda.html">Products</a>
        <a href="aboutus.html">About Us</a>
    </div>
    <div class="right-nav">
        <a href="mis_productos.php">My Products</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="content">
    <h2>Historial de Compras</h2>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $total = $row['precio_unitario'] * $row['cantidad'];
            echo "<div class='product-card'>";
            echo "<h3>" . $row['nombre_producto'] . "</h3>";
            echo "<p>Cantidad: " . $row['cantidad'] . "</p>";
            echo "<p>Precio unitario: " . number_format($row['precio_unitario'], 2) . " €</p>";
            echo "<p>Total: " . number_format($total, 2) . " €</p>";
            echo "<p><strong>Fecha de compra:</strong> " . $row['fecha'] . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>No has comprado ningún producto todavía.</p>";
    }
    ?>
</div>

</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
