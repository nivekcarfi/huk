<?php
include 'db_conexion.php';

if (isset($_POST['id'])) {
    $vehicle_id = $_POST['id'];

    // Actualizar las reservas para que vehicle_id sea NULL
    $sql = "UPDATE reservas SET vehicle_id = NULL WHERE vehicle_id = $vehicle_id";
    if ($conn->query($sql) === TRUE) {
        // Ahora eliminar el vehículo
        $sql = "DELETE FROM vehicles WHERE id = $vehicle_id";
        if ($conn->query($sql) === TRUE) {
            echo "Vehículo eliminado con éxito";
                header("Location: vehiculos_diseño_user.php?mensaje=Vehiculo eliminado con éxito");
                exit();
        } else {
            echo "Error al eliminar el vehículo: " . $conn->error;
        }
    } else {
        echo "Error al actualizar las reservas: " . $conn->error;
    }
}

$conn->close();
?>
