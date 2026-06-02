<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $empleado_id = $_POST['empleado_id'];
    $periodo = $_POST['periodo'];
    $horas_extras = intval($_POST['horas_extras']);

    // 1. Obtener el salario base del empleado
    $stmt = $pdo->prepare("SELECT salario_base FROM empleados WHERE id = ?");
    $stmt->execute([$empleado_id]);
    $empleado = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$empleado) {
        die("Empleado no encontrado.");
    }

    $salario_base = $empleado['salario_base'];

    // 2. Definir constantes de cálculo (Variables del país, ej: Colombia 4%)
    define('VALOR_HORA_EXTRA', 15000);
    define('PORCENTAJE_SALUD', 0.04);
    define('PORCENTAJE_PENSION', 0.04);

    // 3. Realizar los cálculos matemáticos
    $pago_horas_extras = $horas_extras * VALOR_HORA_EXTRA;
    $total_devengado = $salario_base + $pago_horas_extras;

    $deduccion_salud = $total_devengado * PORCENTAJE_SALUD;
    $deduccion_pension = $total_devengado * PORCENTAJE_PENSION;
    
    $salario_neto = $total_devengado - ($deduccion_salud + $deduccion_pension);

    // 4. Guardar el registro de la nómina en la base de datos
    $sql = "INSERT INTO nominas (empleado_id, periodo, horas_extras, total_devengado, deduccion_salud, deduccion_pension, salario_neto) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt_insert = $pdo->prepare($sql);
    $stmt_insert->execute([
        $empleado_id, 
        $periodo, 
        $horas_extras, 
        $total_devengado, 
        $deduccion_salud, 
        $deduccion_pension, 
        $salario_neto
    ]);

    // 5. Mostrar el resultado al usuario
    echo "<h3>¡Nómina Procesada Exitosamente!</h3>";
    echo "Sueldo Base: $" . number_format($salario_base, 2) . "<br>";
    echo "Horas Extras Pagadas: $" . number_format($pago_horas_extras, 2) . "<br>";
    echo "<b>Total Devengado: $" . number_format($total_devengado, 2) . "</b><br><br>";
    echo "Descuento Salud (4%): $" . number_format($deduccion_salud, 2) . "<br>";
    echo "Descuento Pensión (4%): $" . number_format($deduccion_pension, 2) . "<br>";
    echo "<h2>Neto a Pagar: $" . number_format($salario_neto, 2) . "</h2>";
    echo "<a href='index.php'>Volver</a>";
}
?>
