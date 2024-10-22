<?php
// Mostrar errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir la conexión a la base de datos
include 'db_conexion.php';

// Verificar que se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $edad = $_POST['edad'];
    $direccion = $_POST['direccion'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encriptar contraseña

    // Verificar que no exista un usuario con el mismo email
    $sql = "SELECT * FROM clientes WHERE correo_electronico = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        echo "El email ya está registrado.";
    } else {
        // Insertar el nuevo cliente
        $sql = "INSERT INTO clientes (nombre, apellido, correo_electronico, telefono, edad, direccion, contraseña) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssiss", $nombre, $apellido, $email, $telefono, $edad, $direccion, $password);

        if ($stmt->execute()) {
            header("Location: login_diseño.php");
            exit; // Asegúrate de salir después de redirigir
        } else {
            echo "Error al registrar el usuario: " . $stmt->error; // Mostrar error específico
        }
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
   
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Registro</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="assets/img/kaiadmin/favicon.ico"
      type="image/x-icon"
    />

    <!-- Fonts and icons -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="assets/css/demo.css" />


    <style>
      .gradient-custom {
        /* fallback for old browsers */
        background: #6A0CF8;

        /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
        background: linear-gradient(to right, #6A0CF8, #330099)
        }
    
        .bg-dark{
        background: #1a2035 !important
        }
    </style>

  </head>
  <body>




  <section class="vh-100 gradient-custom">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card bg-dark text-white" style="border-radius: 1rem;">
          <div class="card-body p-5 text-center">

            <div class="mb-md-5 mt-md-4 pb-5">
            <form action="registro_diseño.php" method="POST">
              <h2 class="fw-bold mb-2 text-uppercase">Registro</h2>
              <br>
              <!-- Fila para Nombre y Apellido en la misma línea -->
              <div class="row">
                <!-- Nombre -->
                <div class="col-md-6">
                  <div class="form-outline form-white mb-4">
                    <label class="form-label sr-only" for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="form-control form-control-lg bg-dark text-white border border-light" placeholder="Nombre" required />
                  </div>
                </div>

                <!-- Apellido -->
                <div class="col-md-6">
                  <div class="form-outline form-white mb-4">
                    <label class="form-label sr-only" for="apellido">Apellido</label>
                    <input type="text" id="apellido" name="apellido" class="form-control form-control-lg bg-dark text-white border border-light" placeholder="Apellido" required />
                  </div>
                </div>
              </div>

              <!-- Teléfono -->
              <div class="form-outline form-white mb-4">
                <label class="form-label sr-only" for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" class="form-control form-control-lg bg-dark text-white border border-light" placeholder="Teléfono" pattern="[0-9]{9}" required />
              </div>

              <!-- Dirección -->
              <div class="form-outline form-white mb-4">
                <label class="form-label sr-only" for="direccion">Dirección</label>
                <input type="text" id="direccion" name="direccion" class="form-control form-control-lg bg-dark text-white border border-light" placeholder="Dirección" required />
              </div>

              <!-- Edad -->
              <div class="form-outline form-white mb-4">
                <label class="form-label sr-only" for="edad">Edad</label>
                <select id="edad" name="edad" class="form-control form-control-lg bg-dark text-white border border-light" required>
                  <option value="" disabled selected>Selecciona tu edad</option>
                  <script>
                    for (let i = 16; i <= 100; i++) {
                      document.write('<option value="' + i + '">' + i + '</option>');
                    }
                  </script>
                </select>
              </div>

              <!-- Correo electrónico -->
              <div class="form-outline form-white mb-4">
                <label class="form-label sr-only" for="email">Correo electrónico</label>
                <input type="email" name="email" class="form-control form-control-lg bg-dark text-white border border-light" placeholder="Correo electrónico" required />
              </div>

              <!-- Contraseña -->
              <div class="form-outline form-white mb-4">
                <label class="form-label sr-only" for="password">Contraseña</label>
                <input type="password" name="password" class="form-control form-control-lg bg-dark text-white border border-light" placeholder="Contraseña" required />
              </div>

              <!-- Botón de registro -->
              <button class="btn btn-outline-light btn-lg px-5" type="submit">Registrarse</button>
              </form>
            </div>

            <div>
              <p class="mb-0">¿Ya tienes una cuenta? <a href="login_diseño.php" class="text-white-50 fw-bold">Inicia Sesión</a></p>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>





  </body>
</html>
