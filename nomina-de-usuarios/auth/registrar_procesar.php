<?php
require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $cedula = $_POST['cedula'];
    $email = $_POST['email'];
    $salario_base = $_POST['salario_base'];
    $password = $_POST['password'];

    // Encriptar la contraseña de forma segura
    $password_encriptada = password_hash($password, PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO empleados (nombre, cedula, email, salario_base, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $cedula, $email, $salario_base, $password_encriptada]);

        echo "<h3>Usuario registrado con éxito.</h3>";
        echo "<a href='login.html'>Ir al Inicio de Sesión</a>";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "Error: El correo o la cédula ya se encuentran registrados.";
        } else {
            echo "Hubo un error al registrar: " . $e->getMessage();
        }
        echo "<br><a href='registro.html'>Volver a intentar</a>";
    }
}
?>
