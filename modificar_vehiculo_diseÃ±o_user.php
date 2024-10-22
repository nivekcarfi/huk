<?php
// Incluye el archivo de conexión a la base de datos
include 'db_conexion.php';

session_start();
// Verificar si el usuario está logueado
if (!isset($_SESSION['logget'])) {
    header("Location: login_diseño.php"); // Redirigir a la página de inicio de sesión si no está logueado
    exit;
}

// Aquí puedes acceder a la información del usuario
$username = $_SESSION['logget'];
$user_id = $_SESSION['user_id'];

// Inicializa variables para almacenar mensajes de error o éxito
$error = '';
$success = '';

// Verifica si se ha pasado el ID del vehículo
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Verifica si el formulario ha sido enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Escapa las entradas del usuario para prevenir inyecciones SQL
        $matricula = $conn->real_escape_string($_POST['matricula']);
        $marca = $conn->real_escape_string($_POST['marca']);
        $modelo = $conn->real_escape_string($_POST['modelo']);

        // Verifica que los campos no estén vacíos
        if (!empty($matricula) && !empty($marca) && !empty($modelo)) {
            // Actualiza el vehículo en la base de datos
            $sql = "UPDATE vehicles SET matricula='$matricula', modelo='$modelo', marca='$marca' WHERE id=$id";

            if ($conn->query($sql) === TRUE) {
                $success = "Vehículo modificado con éxito.";
                // Redireccionar a la lista de vehículos con el client_id en la URL
                header("Location: vehiculos_diseño_user.php");
                exit();
            } else {
                $error = "Error al modificar el vehículo: " . $conn->error;
            }
        } else {
            $error = "Todos los campos son obligatorios.";
        }
    }

    // Obtiene los datos actuales del vehículo
    $sql = "SELECT * FROM vehicles WHERE id=$id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $vehiculo = $result->fetch_assoc();
    } else {
        die("Vehículo no encontrado.");
    }
} else {
    die("ID de vehículo no proporcionado.");
}

// Función para obtener el correo del cliente
function obtenerCorreoCliente($conn, $user_id) {
    $sql = "SELECT correo_electronico FROM clientes WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error preparando la consulta SQL: " . $conn->error);
    }

    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['correo_electronico']; // Devuelve el correo electrónico
    } else {
        return null; // Si no se encuentra el correo
    }
}

// Obtener el correo del cliente
$correo_cliente = obtenerCorreoCliente($conn, $user_id);

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Modificar vehículo</title>
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
  </head>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="main_diseño_user.php" class="logo">
              <img
                src="assets/img/kaiadmin/logo_light.svg"
                alt="navbar brand"
                class="navbar-brand"
                height="30"
              />
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
          <!-- End Logo Header -->
          </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">

              
              <li class="nav-item">
                <a class="nav-link active" href="main_diseño_user.php">
                    <i class="fas fa-home"></i>
                    <p>Inicio</p>
                </a>
              </li>


              <li class="nav-item  active submenu">
                <a data-bs-toggle="collapse" href="#vehiculos">
                  <i class="fas fa-car"></i>
                  <p>Vehículos</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="vehiculos">
                  <ul class="nav nav-collapse">

                    <li class="active">
                      <a href="vehiculos_diseño_user.php">
                        <span class="sub-item">Mis vehículos</span>
                      </a>
                    </li>
                    <li>
                      <a href="añadir_vehiculo_diseño_user.php">
                        <span class="sub-item">Añadir vehículo</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>



              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#reservas">
                  <i class="fas fa-history"></i>
                  <p>Reservas</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="reservas">
                  <ul class="nav nav-collapse">           
                    <li>
                      <a href="reservas_diseño_user.php">
                        <span class="sub-item">Mis reservas</span>
                      </a>
                    </li>
                    <li>
                      <a href="crear_reserva_diseño_user.php">
                        <span class="sub-item">Añadir reserva</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>






            </ul>
          </div>
        </div>
      </div>
      <!-- End Sidebar -->

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
              <a href="main_diseño_user.php" class="logo">
                <img
                  src="assets/img/kaiadmin/logo_light.svg"
                  alt="navbar brand"
                  class="navbar-brand"
                  height="30"
                />
              </a>
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                  <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                  <i class="gg-menu-left"></i>
                </button>
              </div>
              <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
              </button>
            </div>
            <!-- End Logo Header -->
          </div>
          <!-- Navbar Header -->
          <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
              <div class="container-fluid d-flex align-items-center">
                  <!-- Botones centrados sin input -->
                  <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                      <div class="d-flex">
                          <button class="btn btn-black btn-round me-3" onclick="window.location.href='main_diseño_user.php';">Inicio</button>
                          <button class="btn btn-black btn-border btn-round me-3" onclick="window.location.href='vehiculos_diseño_user.php';">Mis vehículos</button>
                          <button class="btn btn-black btn-round" onclick="window.location.href='reservas_diseño_user.php';">Mis reservas</button>
                      </div>
                  </nav>

                  <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                      <li class="nav-item">
                          <span class="navbar-text me-3">
                              Buenas, <strong><?php echo htmlspecialchars($username); ?></strong>
                          </span>
                      </li>

                      <li class="nav-item topbar-icon dropdown hidden-caret">
                          <a class="nav-link" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                              <i class="fas fa-user"></i>
                          </a>
                          <ul class="dropdown-menu dropdown-user animated fadeIn">
                              <div class="dropdown-user-scroll scrollbar-outer">
                                  <li>
                                      <div class="user-box">
                                          <div class="u-text">
                                              <h4><?php echo htmlspecialchars($username); ?></h4>
                                              <p class="text-muted"><?php echo htmlspecialchars($correo_cliente); ?></p>
                                          </div>
                                      </div>
                                  </li>
                                  <li>
                                      <div class="dropdown-divider"></div>
                                      <a class="dropdown-item" href="logout.php">Cerrar sesión</a>
                                  </li>
                              </div>
                          </ul>
                      </li>
                  </ul>
              </div>
          </nav>
          <!-- End Navbar -->
        </div>

        <div class="container">
          <div class="page-inner">

            <div class="row">
              <div class="col-md-6 ms-auto me-auto">
                <div class="card">
                  <div class="card-header">
                    <div class="card-title">Modificar vehículo</div>
                    <?php if ($error): ?>
                        <p style="color: red;"><?php echo $error; ?></p>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <p style="color: green;"><?php echo $success; ?></p>
                    <?php endif; ?>
                  </div>



                  <!-- FORMULARIO -->
                   <!-- FORMULARIO -->
                    <!-- FORMULARIO -->
                     <!-- FORMULARIO -->





                  <div class="card-body">
                    <div class="row">
                      <form method="POST" action="">

                      <div class="form-group">
                          <label for="marca">Marca</label>
                          <select class="form-select form-control" id="marca" name="marca" required>
                              <option value="">Selecciona una marca</option>
                              <option value="Audi" <?php echo ($vehiculo['marca'] == 'Audi') ? 'selected' : ''; ?>>Audi</option>
                              <option value="BMW" <?php echo ($vehiculo['marca'] == 'BMW') ? 'selected' : ''; ?>>BMW</option>
                              <option value="Chevrolet" <?php echo ($vehiculo['marca'] == 'Chevrolet') ? 'selected' : ''; ?>>Chevrolet</option>
                              <option value="Chrysler" <?php echo ($vehiculo['marca'] == 'Chrysler') ? 'selected' : ''; ?>>Chrysler</option>
                              <option value="Dodge" <?php echo ($vehiculo['marca'] == 'Dodge') ? 'selected' : ''; ?>>Dodge</option>
                              <option value="Ford" <?php echo ($vehiculo['marca'] == 'Ford') ? 'selected' : ''; ?>>Ford</option>
                              <option value="Honda" <?php echo ($vehiculo['marca'] == 'Honda') ? 'selected' : ''; ?>>Honda</option>
                              <option value="Hyundai" <?php echo ($vehiculo['marca'] == 'Hyundai') ? 'selected' : ''; ?>>Hyundai</option>
                              <option value="Kia" <?php echo ($vehiculo['marca'] == 'Kia') ? 'selected' : ''; ?>>Kia</option>
                              <option value="Land Rover" <?php echo ($vehiculo['marca'] == 'Land Rover') ? 'selected' : ''; ?>>Land Rover</option>
                              <option value="Mazda" <?php echo ($vehiculo['marca'] == 'Mazda') ? 'selected' : ''; ?>>Mazda</option>
                              <option value="Mercedes-Benz" <?php echo ($vehiculo['marca'] == 'Mercedes-Benz') ? 'selected' : ''; ?>>Mercedes-Benz</option>
                              <option value="Nissan" <?php echo ($vehiculo['marca'] == 'Nissan') ? 'selected' : ''; ?>>Nissan</option>
                              <option value="Porsche" <?php echo ($vehiculo['marca'] == 'Porsche') ? 'selected' : ''; ?>>Porsche</option>
                              <option value="Renault" <?php echo ($vehiculo['marca'] == 'Renault') ? 'selected' : ''; ?>>Renault</option>
                              <option value="Subaru" <?php echo ($vehiculo['marca'] == 'Subaru') ? 'selected' : ''; ?>>Subaru</option>
                              <option value="Peugeot" <?php echo ($vehiculo['marca'] == 'Peugeot') ? 'selected' : ''; ?>>Peugeot</option>
                              <option value="Fiat" <?php echo ($vehiculo['marca'] == 'Fiat') ? 'selected' : ''; ?>>Fiat</option>
                              <option value="Volkswagen" <?php echo ($vehiculo['marca'] == 'Volkswagen') ? 'selected' : ''; ?>>Volkswagen</option>
                              <option value="Volvo" <?php echo ($vehiculo['marca'] == 'Volvo') ? 'selected' : ''; ?>>Volvo</option>
                              <option value="Toyota" <?php echo ($vehiculo['marca'] == 'Toyota') ? 'selected' : ''; ?>>Toyota</option>
                              <option value="Citröen" <?php echo ($vehiculo['marca'] == 'Citröen') ? 'selected' : ''; ?>>Citröen</option>
                              <option value="Jaguar" <?php echo ($vehiculo['marca'] == 'Jaguar') ? 'selected' : ''; ?>>Jaguar</option>
                              <option value="Tesla" <?php echo ($vehiculo['marca'] == 'Tesla') ? 'selected' : ''; ?>>Tesla</option>
                              <option value="Buick" <?php echo ($vehiculo['marca'] == 'Buick') ? 'selected' : ''; ?>>Buick</option>
                              <option value="Infiniti" <?php echo ($vehiculo['marca'] == 'Infiniti') ? 'selected' : ''; ?>>Infiniti</option>
                              <option value="Lexus" <?php echo ($vehiculo['marca'] == 'Lexus') ? 'selected' : ''; ?>>Lexus</option>
                              <option value="Mini" <?php echo ($vehiculo['marca'] == 'Mini') ? 'selected' : ''; ?>>Mini</option>
                              <option value="Saab" <?php echo ($vehiculo['marca'] == 'Saab') ? 'selected' : ''; ?>>Saab</option>
                              <option value="Smart" <?php echo ($vehiculo['marca'] == 'Smart') ? 'selected' : ''; ?>>Smart</option>
                              <option value="Seat" <?php echo ($vehiculo['marca'] == 'Seat') ? 'selected' : ''; ?>>Seat</option>
                              <option value="Skoda" <?php echo ($vehiculo['marca'] == 'Skoda') ? 'selected' : ''; ?>>Skoda</option>
                              <option value="Changan" <?php echo ($vehiculo['marca'] == 'Changan') ? 'selected' : ''; ?>>Changan</option>
                              <option value="Geely" <?php echo ($vehiculo['marca'] == 'Geely') ? 'selected' : ''; ?>>Geely</option>
                              <option value="Great Wall" <?php echo ($vehiculo['marca'] == 'Great Wall') ? 'selected' : ''; ?>>Great Wall</option>
                              <option value="Rover" <?php echo ($vehiculo['marca'] == 'Rover') ? 'selected' : ''; ?>>Rover</option>
                              <option value="Scion" <?php echo ($vehiculo['marca'] == 'Scion') ? 'selected' : ''; ?>>Scion</option>
                          </select>
                      </div>

                        <div class="form-group">
                            <label for="modelo">Modelo</label>
                            <input
                                type="text"
                                class="form-control"
                                id="modelo"
                                name="modelo"
                                placeholder="Introduzca el modelo del vehículo"
                                required
                                value="<?php echo htmlspecialchars($vehiculo['modelo']); ?>"
                            />
                        </div>

                        <div class="form-group">
                            <label for="matricula">Matrícula</label>
                            <input
                                type="text"
                                class="form-control"
                                id="matricula"
                                name="matricula"
                                placeholder="Introduzca la matrícula del vehículo"
                                required
                                value="<?php echo htmlspecialchars($vehiculo['matricula']); ?>"
                            />
                        </div>
                        
                        <div class="card-action">
                            <button type="submit" value="Modificar Vehículo" class="btn btn-success">Editar</button>
                            <button type="button" class="btn btn-danger" onclick="window.location.href='vehiculos_diseño_user.php'">Volver</button>
                        </div>

                      </form>
                    </div>
                

                <!-- FORMULARIO -->
                <!-- FORMULARIO -->
                  <!-- FORMULARIO -->
                  <!-- FORMULARIO -->






                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


        <footer class="footer">
          <div class="container-fluid d-flex justify-content-between">
            <div class="d-flex align-items-center">
              <ul class="list-unstyled d-flex m-0">
                <li class="me-3"><a href="main_diseño_user.php" class="text-decoration-none">Inicio</a></li>
                <li class="me-3"><a href="vehiculos_diseño_user.php" class="text-decoration-none">Mis Vehículos</a></li>
                <li><a href="vehiculos_diseño_user.php" class="text-decoration-none">Mis Reservas</a></li>
              </ul>
            </div>
            <div class="copyright">
              © 2024 Huking. All rights reserved.
            </div>
            <div>
              Proyecto creado por Hector, Unai, Kevin.
            </div>
          </div>
        </footer>









      <!-- End Custom template -->
    </div>
    <!--   Core JS Files   -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Chart JS -->
    <script src="assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Datatables -->
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- Bootstrap Notify -->
    <script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <!-- jQuery Vector Maps -->
    <script src="assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="assets/js/plugin/jsvectormap/world.js"></script>

    <!-- Sweet Alert -->
    <script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="assets/js/kaiadmin.min.js"></script>

  </body>
</html>
