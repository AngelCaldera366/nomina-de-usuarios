<?php
session_start(); // Inicia el sistema de sesiones de PHP
require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Buscar al empleado por su correo electrónico
    $stmt = $pdo->prepare("SELECT id, nombre, password FROM empleados WHERE email = ?");
    $stmt->execute([$email]);
    $empleado = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si el usuario existe y la contraseña coincide
    if ($empleado && password_verify($password, $empleado['password'])) {
        // Guardar datos clave en la sesión del navegador
        $_SESSION['usuario_id'] = $empleado['id'];
        $_SESSION['usuario_nombre'] = $empleado['nombre'];

        // Redirigir al panel donde verá su nómina
        header("Location: mi_nomina.php");
        exit();
    } else {
        echo "<h3>Correo o contraseña incorrectos.</h3>";
        echo "<a href='login.html'>Volver a intentar</a>";
    }
}
?>
