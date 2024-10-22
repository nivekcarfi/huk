<?php
$host = "localhost";
$user = "huk";
$password = "huk3"; // Deja la contraseña vacía si no has configurado una
$dbname = "DB_huk"; // Nombre exacto de la base de datos

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
//echo "exito";
?>
