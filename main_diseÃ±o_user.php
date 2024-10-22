<?php
// Mostrar todos los errores de PHP para depurar
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


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

// Asignar client_id (asumimos que user_id y client_id son lo mismo)
$client_id = $_SESSION['user_id']; 

// Función para obtener reserva del usuario
function obtener_reserva($conn, $user_id) {
    $sql = "SELECT * FROM reservas WHERE client_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error preparando la consulta SQL: " . $conn->error);
    }

    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

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

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

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

// Obtener la lista de reservas del usuario
$reserva = obtener_reserva($conn, $user_id);

// Obtener la lista de vehículos según el cliente que ha iniciado sesión
$vehiculos = obtenerVehiculosPorCliente($conn, $client_id);
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Inicio</title>
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

              
              <li class="nav-item active submenu">
                <a class="nav-link active" href="main_diseño_user.php">
                    <i class="fas fa-home"></i>
                    <p>Inicio</p>
                </a>
              </li>



              <li class="nav-item">
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
              <a href="index.html" class="logo">
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
                          <button class="btn btn-black btn-border btn-round me-3" onclick="window.location.href='main_diseño_user.php';">Inicio</button>
                          <button class="btn btn-black btn-round me-3" onclick="window.location.href='vehiculos_diseño_user.php';">Mis vehículos</button>
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

            <div>
              <h3 class="fw-bold mb-3">Inicio</h3>
            </div>
            
              <?php
              // Obtener el client_id (asumiendo que es lo mismo que el user_id)
              $client_id = $_SESSION['user_id'];

              // Contar la cantidad de vehículos del cliente logueado
              $query_vehiculos = "SELECT COUNT(*) AS total_vehiculos FROM vehicles WHERE client_id = ?";
              $stmt_vehiculos = $conn->prepare($query_vehiculos);
              $stmt_vehiculos->bind_param("i", $client_id);
              $stmt_vehiculos->execute();
              $result_vehiculos = $stmt_vehiculos->get_result()->fetch_assoc();
              $total_vehiculos = $result_vehiculos['total_vehiculos'];

              // Contar la cantidad de reservas del cliente logueado
              $query_reservas = "SELECT COUNT(*) AS total_reservas FROM reservas WHERE client_id = ?";
              $stmt_reservas = $conn->prepare($query_reservas);
              $stmt_reservas->bind_param("i", $client_id);
              $stmt_reservas->execute();
              $result_reservas = $stmt_reservas->get_result()->fetch_assoc();
              $total_reservas = $result_reservas['total_reservas'];

              // Contar la cantidad de dinero de las reservas del cliente logueado
              $query_dinero = "SELECT SUM(preu) AS total_dinero FROM reservas WHERE client_id = ?";
              $stmt_dinero = $conn->prepare($query_dinero);
              $stmt_dinero->bind_param("i", $client_id);
              $stmt_dinero->execute();
              $result_dinero = $stmt_dinero->get_result()->fetch_assoc();
              $total_dinero = $result_dinero['total_dinero'];



              $conn->close();
              ?>

            <div class="row">
              <div class="col-sm-6 col-md-4">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-primary bubble-shadow-small"
                        >
                          <i class="fas fa-car"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-4 ms-sm-2">
                        <div class="numbers">
                          <p class="card-category">Vehículos</p>
                          <h4 class="card-title"><?php echo $total_vehiculos; ?></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>



              <div class="col-sm-6 col-md-4">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-info bubble-shadow-small"
                        >
                          <i class="fas fa-history"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-4 ms-sm-2">
                        <div class="numbers">
                          <p class="card-category">Reservas</p>
                          <h4 class="card-title"><?php echo $total_reservas; ?></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>




              <div class="col-sm-6 col-md-4">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-success bubble-shadow-small"
                        >
                          <i class="fas fa-coins"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-4 ms-sm-2">
                        <div class="numbers">
                          <p class="card-category">Invertido</p>
                          <h4 class="card-title"><?php echo $total_dinero; ?> €</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>






            
            <div class="row">
              <div class="col-md-4">
                <div class="card card-round">
                  <div class="card-body">
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

                      <!-- Mostrar la lista de vehículos del cliente seleccionado -->
                <!-- Mostrar la lista de vehículos del cliente logueado -->
                <div class="card-list py-4">
                    <?php if (!empty($vehiculos)): ?>
                        <?php foreach ($vehiculos as $vehiculo): ?>
                            <div class="item-list">
                                <div class="info-user ms-3">
                                    <div class="username"><?php echo htmlspecialchars($vehiculo['marca']); ?> <?php echo htmlspecialchars($vehiculo['modelo']); ?></div>
                                    <div class="status"><?php echo htmlspecialchars($vehiculo['matricula']); ?></div>
                                </div>
                                <a href="modificar_vehiculo_diseño_user.php?id=<?php echo htmlspecialchars($vehiculo['id']); ?>">
                                    <button class="btn btn-icon btn-link op-8 me-1">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                </a>
                                <form style="display:inline;" action="eliminar_vehiculo_diseño_user_main.php" method="post">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($vehiculo['id']); ?>">
                                    <button class="btn btn-icon btn-link btn-danger op-8">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                            <p>No hay vehículos registrados para este cliente.</p>
                        <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>






              <div class="col-md-8">
                <div class="card card-round">
                  <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                      <div class="card-title">Reservas</div>
                      <div class="card-tools">
                        <div class="dropdown">
                          <form action="crear_reserva_diseño_user.php" method="get">
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
                            <th scope="col">Número</th>
                            <th scope="col">Fecha Entrada</th>
                            <th scope="col">Fecha Salida</th>
                            <th scope="col">Precio</th>
                            <th scope="col" class="text-end">Cancelar</th>
                          </tr>
                        </thead>
                        <tbody>
   
                            <?php foreach ($reserva as $reserva): ?>
                            <tr>
                                <td><?php echo $reserva['id']; ?></td>
                                <td><?php echo $reserva['numero_reserva']; ?></td>
                                <td><?php echo $reserva['data_entrada']; ?></td>
                                <td><?php echo $reserva['data_sortida']; ?></td>
                                <td><?php echo $reserva['preu']; ?></td>
                                <td class="text-end">
                                    <form style="display:inline;" action="eliminar_reserva_diseño_user_main.php" method="post">
                                        <input type="hidden" name="id" value="<?php echo $reserva['id']; ?>">
                                        <button class="btn btn-icon btn-link btn-danger op-8">
                                          <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>        
                            <?php endforeach; ?>

                        </tbody>
                      </table>
                    </div>
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
