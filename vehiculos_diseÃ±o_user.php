<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['logget'])) {
    header("Location: login_diseño.php"); // Redirigir a la página de inicio de sesión si no está logueado
    exit;
}


// Aquí puedes acceder a la información del usuario
$username = $_SESSION['logget'];
$user_id = $_SESSION['user_id'];

// Obtener el ID del cliente de la sesión
$id_cliente = $_SESSION['user_id']; // Usar 'user_id' si es así como guardas el ID del cliente

// Función para obtener vehículos por cliente
function obtenerVehiculosPorCliente($conn, $client_id) {
    $sql = "SELECT * FROM vehicles WHERE client_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error preparando la consulta SQL: " . $conn->error);
    }

    $stmt->bind_param('i', $client_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        die("Error ejecutando la consulta SQL: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

// Conexión a la base de datos
require("db_conexion.php");

// Obtener la lista de vehículos según el cliente que ha iniciado sesión
$vehiculos = obtenerVehiculosPorCliente($conn, $id_cliente);


// Función para obtener el correo del cliente
function obtenerCorreoCliente($conn, $user_id) {
  $sql = "SELECT correo_electronico FROM clientes WHERE id = ?"; // Adaptado a la estructura de tu tabla
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
    <title>Mis vehículos</title>
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
            <div
              class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
            >
          </div>

            <div class="row">
              <div class="col-md-0">
                <div class="card card-round">
                  <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                      <div class="card-title">Vehículos</div>
                      <div class="card-tools">
                        <div class="dropdown">
                        <form action="añadir_vehiculo_diseño_user.php" method="get">
                            <button class="btn btn-icon btn-clean me-0" type="submit">
                              <i class="fas fa-plus"></i>
                            </button>
                        </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card-body p-0">



                    <div class="table-responsive">
                      <!-- Projects table -->
                      <table class="table align-items-center mb-0">
                        <thead class="thead-light">
                          <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Matrícula</th>
                            <th scope="col">Modelo</th>
                            <th scope="col">Marca</th>
                            <th scope="col" class="text-end">Acciones</th>
                          </tr>
                        </thead>
                        <tbody>

                        <?php if (!empty($vehiculos)): ?>
                            <?php foreach ($vehiculos as $vehiculo): ?>
                            <tr>
                                <td><?php echo $vehiculo['id']; ?></td>
                                <td><?php echo $vehiculo['matricula']; ?></td>
                                <td><?php echo $vehiculo['modelo']; ?></td>
                                <td><?php echo $vehiculo['marca']; ?></td>
                                <td class="text-end">
                                    <a href="modificar_vehiculo_diseño_user.php?id=<?php echo $vehiculo['id']; ?>">
                                    <button class="btn btn-icon btn-link op-8 me-1">
                                      <i class="fas fa-pen"></i>
                                    </button>
                                    </a>
                                    <form style="display:inline;" action="eliminar_vehiculo_diseño_user.php" method="post">
                                        <input type="hidden" name="id" value="<?php echo $vehiculo['id']; ?>">
                                        <button class="btn btn-icon btn-link btn-danger op-8">
                                          <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">No hay vehículos para el cliente seleccionado.</td>
                                </tr>
                            <?php endif; ?>


                        </tbody>
                      </table>
                    </div>
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
