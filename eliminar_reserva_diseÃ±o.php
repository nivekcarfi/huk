<?php
// Incluye el archivo de conexión a la base de datos
include 'db_conexion.php';

// Verifica si se ha pasado el ID del vehículo
if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Consulta SQL para eliminar el vehículo
    $sql = "DELETE FROM reservas WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        // Redireccionar a la lista de vehículos con un mensaje de éxito
        header("Location: reservas_diseño.php?mensaje=Reserva eliminada con éxito");
        exit();
    } else {
        // Si ocurre un error, redirige con un mensaje de error
        header("Location: reservas_diseño.php?error=No se pudo eliminar la reserva");
        exit();
    }
} else {
    // Si no se proporciona el ID, redirige con un mensaje de error
    header("Location: reservas_diseño.php?error=ID de la reserva no proporcionado");
    exit();
}
?>
