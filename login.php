<?php
session_start();

$host = 'db5017003380.hosting-data.io';
$database = 'dbs13697465';
$user = 'dbu1433845';
$password = 'diegoelperpetrador_asir2025';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    $stmt = $conn->prepare("SELECT id_cliente, contrasena FROM clientes WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        if (password_verify($contrasena, $row['contrasena'])) {
            $_SESSION['usuario'] = $correo;
            $_SESSION['user_id'] = $row['id_cliente']; // 🔥 ESTA LÍNEA ES CLAVE
            header("Location: index.html");
            exit;
        } else {
            echo "<script>alert('Contraseña incorrecta.'); window.location.href='login.html';</script>";
        }
    } else {
        echo "<script>alert('Usuario no encontrado.'); window.location.href='login.html';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
