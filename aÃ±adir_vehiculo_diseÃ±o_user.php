<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['logget'])) {
    header("Location: login_diseño.php"); // Redirigir a la página de inicio de sesión si no está logueado
    exit;
}

// Obtener el ID del cliente desde la sesión
$id_cliente = $_SESSION['user_id']; // Aquí usamos 'user_id' de la sesión que contiene el ID del cliente

// Incluir el archivo de conexión a la base de datos
include 'db_conexion.php';

// Obtener el nombre y correo del cliente
$username = $_SESSION['logget'];
$correo_cliente = obtenerCorreoCliente($conn, $id_cliente);

// Inicializa variables para almacenar mensajes de error o éxito
$error = '';
$success = '';

// Verifica si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Escapa las entradas del usuario para prevenir inyecciones SQL
    $matricula = $conn->real_escape_string($_POST['matricula']);
    $marca = $conn->real_escape_string($_POST['marca']);
    $modelo = $conn->real_escape_string($_POST['modelo']);

    // Verifica que los campos no estén vacíos
    if (!empty($matricula) && !empty($marca) && !empty($modelo)) {
        // Inserta el nuevo vehículo en la base de datos, vinculándolo con el cliente logueado
        $sql = "INSERT INTO vehicles (matricula, modelo, marca, client_id) VALUES ('$matricula', '$modelo', '$marca', '$id_cliente')";

        if ($conn->query($sql) === TRUE) {
            $success = "Vehículo añadido con éxito.";
            header("Location: vehiculos_diseño_user.php"); // nos redirige al login cuando hayamos creado la reserva
        } else {
            $error = "Error al añadir el vehículo: " . $conn->error;
        }
    } else {
        $error = "Todos los campos son obligatorios.";
    }
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
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Añadir vehículo</title>
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

                    <li>
                      <a href="vehiculos_diseño_user.php">
                        <span class="sub-item">Mis vehículos</span>
                      </a>
                    </li>
                    <li class="active">
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
                    <div class="card-title">Añadir vehículo</div>
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

                      <?php if ($error): ?>
                          <p style="color: red;"><?php echo $error; ?></p>
                      <?php endif; ?>
                      <?php if ($success): ?>
                          <p style="color: green;"><?php echo $success; ?></p>
                      <?php endif; ?>

                      <div class="form-group">
                          <label for="marca">Marca</label>
                          <select class="form-select form-control" id="marca" name="marca" required>
                              <option>Selecciona una marca</option>
                              <option>Audi</option>
                              <option>BMW</option>
                              <option>Chevrolet</option>
                              <option>Chrysler</option>
                              <option>Dodge</option>
                              <option>Ford</option>
                              <option>Honda</option>
                              <option>Hyundai</option>
                              <option>Kia</option>
                              <option>Land Rover</option>
                              <option>Mazda</option>
                              <option>Mercedes-Benz</option>
                              <option>Nissan</option>
                              <option>Porsche</option>
                              <option>Renault</option>
                              <option>Subaru</option>
                              <option>Peugeot</option>
                              <option>Fiat</option>
                              <option>Volkswagen</option>
                              <option>Volvo</option>
                              <option>Toyota</option>
                              <option>Citröen</option>
                              <option>Jaguar</option>
                              <option>Tesla</option>
                              <option>Buick</option>
                              <option>Infiniti</option>
                              <option>Lexus</option>
                              <option>Mini</option>
                              <option>Saab</option>
                              <option>Smart</option>
                              <option>Seat</option>
                              <option>Skoda</option>
                              <option>Changan</option>
                              <option>Geely</option>
                              <option>Great Wall</option>
                              <option>Rover</option>
                              <option>Scion</option>
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
                            />
                        </div>
                        
                        <div class="card-action">
                            <button type="submit" class="btn btn-success">Añadir</button>
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
