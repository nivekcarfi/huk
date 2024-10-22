<?php
// Archivo de conexión a la DB
require("db_conexion.php");
// Iniciamos una sesión
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['correo_electronico']) && isset($_POST['contraseña'])) {
        $username = $_POST['correo_electronico'];
        $password = $_POST['contraseña'];

        // Consulta para verificar el usuario
        $query = "SELECT * FROM clientes WHERE correo_electronico=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $results = $stmt->get_result();

        if ($results->num_rows === 1) {
            $row = $results->fetch_assoc();

            // Usamos password_verify para comparar la contraseña ingresada con la encriptada
            if (password_verify($password, $row['contraseña'])) {
                // Guardar los datos necesarios en la sesión
                $_SESSION['logget'] = $row['nombre']; // Guardar el nombre de usuario en la sesión
                $_SESSION['user_id'] = $row['id']; // Guardar el ID del usuario en la sesión
                $_SESSION['correo_electronico'] = $row['correo_electronico']; // Guardar el correo en la sesión
                
                // Verificar si el correo es el del usuario concreto con acceso admin
                if ($row['correo_electronico'] == 'admin@huking.com') {
                    header("Location: main_diseño.php"); // Redirigir al panel de administración
                } else {
                    header("Location: main_diseño_user.php"); // Redirigir al panel de usuario normal
                }
                exit;

            } else {
                $msgError = "Nombre de usuario/contraseña inválidos";
            }
        } else {
            $msgError = "Nombre de usuario/contraseña inválidos";
        }

        $stmt->close();
    } else {
        $msgError = "Por favor, complete todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Inicio de sesión</title>
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
              <form action="login_diseño.php" method="post"> 
              <img src="assets/img/kaiadmin/logo_light.svg" alt="Logo" class="mb-4" style="width: 180px; height: auto;" /><br/><br/><br/>
              <?php if (isset($msgError)): ?>
                  <p><?php echo $msgError; ?></p>
              <?php endif; ?>
              <h2 class="fw-bold mb-2 text-uppercase">Inicio de sesión</h2>
              <p class="text-white-50 mb-5">¡Por favor, introduzca su correo y contraseña!</p>

              <div class="form-outline form-white mb-4">
                <label class="form-label sr-only" for="correo_electronico">Correo electrónico</label>
                <input type="email" id="correo_electronico" name="correo_electronico" class="form-control form-control-lg bg-dark text-white border border-light" placeholder="Correo electrónico" required />
              </div>

              <div class="form-outline form-white mb-4">
                <label class="form-label sr-only" for="contraseña">Contraseña</label>
                <input type="password" id="contraseña" name="contraseña" class="form-control form-control-lg bg-dark text-white border border-light" placeholder="Contraseña" required />
              </div>

              <button class="btn btn-outline-light btn-lg px-5" type="submit">Iniciar Sesión</button>
              </form>
            </div>

            <div>
              <p class="mb-0">¿No tienes una cuenta? <a href="registro_diseño.php" class="text-white-50 fw-bold">Regístrese</a></p>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>




  </body>
</html>
