<?php
session_start();

// Validar que el usuario haya iniciado sesión correctamente
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}

require 'conexion.php';
$empleado_id = $_SESSION['usuario_id'];

// Consultar todas las nóminas registradas para este empleado en específico
$stmt = $pdo->prepare("SELECT * FROM nominas WHERE empleado_id = ? ORDER BY periodo DESC");
$stmt->execute([$empleado_id]);
$mis_nominas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Nóminas</title>
</head>
<body>
    <h2>Bienvenido(a), <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></h2>
    <p><a href="logout.php">Cerrar Sesión</a></p>

    <h3>Tu Historial de Pagos</h3>

    <?php if (count($mis_nominas) > 0): ?>
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>Periodo</th>
                    <th>Horas Extras</th>
                    <th>Total Devengado</th>
                    <th>Deducción Salud</th>
                    <th>Deducción Pensión</th>
                    <th>Neto a Pagar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mis_nominas as $nomina): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($nomina['periodo']); ?></td>
                        <td><?php echo $nomina['horas_extras']; ?></td>
                        <td>$<?php echo number_format($nomina['total_devengado'], 2); ?></td>
                        <td>$<?php echo number_format($nomina['deduccion_salud'], 2); ?></td>
                        <td>$<?php echo number_format($nomina['deduccion_pension'], 2); ?></td>
                        <td><b>$<?php echo number_format($nomina['salario_neto'], 2); ?></b></td>
                    </tr>
                <?php endindex; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aún no tienes nóminas liquidadas en el sistema.</p>
    <?php endif; ?>
</body>
</html>
