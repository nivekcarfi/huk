<?php
// Incluye el archivo de conexión a la base de datos
include 'db_conexion.php';

session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['logget'])) {
    header("Location: login.php"); // Redirigir a la página de inicio de sesión si no está logueado
    exit;
}

// Aquí puedes acceder a la información del usuario
$username = $_SESSION['logget'];
$user_id = $_SESSION['user_id'];

// Función para obtener todos los vehículos
function obtener_reserva($conn, $user_id) {
    $sql = "SELECT * FROM reservas where client_id = $user_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

// Obtener la lista de vehículos
$reserva = obtener_reserva($conn, $user_id);
?>

<!DOCTYPE html>
<head>
    <title>Gestión de Reservas</title>
</head>
<body>
    <h1>Parking HUK</h1>
    <h2>Lista de reservas</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Numero de ticket</th>
            <th>Hora de entrada</th>
            <th>Hora de salida</th>
            <th>Precio</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($reserva as $reserva): ?>
        <tr>
            <td><?php echo $reserva['id']; ?></td>
            <td><?php echo $reserva['numero_reserva']; ?></td>
            <td><?php echo $reserva['data_entrada']; ?></td>
            <td><?php echo $reserva['data_sortida']; ?></td>
            <td><?php echo $reserva['preu']; ?></td>
            <td>
                <a href="modificar_vehiculo.php?id=<?php echo $reserva['id']; ?>"><button>Modificar</button>
                <form action="eliminar_reserva.php" method="post">
                        <input type="hidden" name="id" value="<?php echo $reserva['id']; ?>">
                        <button type="submit">Eliminar</button>
                    </form>
            </td>
        </tr>        <?php endforeach; ?>
    </table>
    <br>
    <a href="crear_reserva.php"><button>Crear reserva</button></a>
</body>
</html>
