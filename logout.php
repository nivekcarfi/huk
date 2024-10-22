<?php
session_start();
session_destroy(); // Destruir la sesión actual
header("Location: login_diseño.php"); // Redirigir al inicio de sesión
exit();
?>
