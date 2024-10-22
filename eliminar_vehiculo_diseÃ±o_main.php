<?php
include 'db_conexion.php';

if (isset($_POST['id'])) {
    $vehicle_id = $_POST['id'];

    // Obtener el client_id asociado con el vehicle_id
    $sql = "SELECT client_id FROM vehicles WHERE id = $vehicle_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Obtener el client_id de la fila resultante
        $row = $result->fetch_assoc();
        $client_id = $row['client_id'];

        // Actualizar las reservas para que vehicle_id sea NULL
        $sql = "UPDATE reservas SET vehicle_id = NULL WHERE vehicle_id = $vehicle_id";
        if ($conn->query($sql) === TRUE) {
            // Ahora eliminar el vehículo
            $sql = "DELETE FROM vehicles WHERE id = $vehicle_id";
            if ($conn->query($sql) === TRUE) {
                echo "Vehículo eliminado con éxito";
                header("Location: main_diseño.php?client_id=$client_id");
                exit();
            } else {
                echo "Error al eliminar el vehículo: " . $conn->error;
            }
        } else {
            echo "Error al actualizar las reservas: " . $conn->error;
        }
    } else {
        echo "No se encontró un cliente asociado con este vehículo.";
    }
}

$conn->close();
?>
