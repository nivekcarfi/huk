<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['logget']) || $_SESSION['correo_electronico'] !== 'admin@huking.com') {
    header("Location: login_diseño.php"); // Redirigir si no está logueado o no es el usuario autorizado
    exit;
}

// Incluye el archivo de conexión a la base de datos
include 'db_conexion.php';

// Función para obtener todos los vehículos
function obtener_reserva($conn) {
    $sql = "SELECT * FROM reservas";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

// Obtener la lista de vehículos
$reserva = obtener_reserva($conn);

?>

<!DOCTYPE html>
<html lang="en">
  <head>

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Inicio - Admin</title>
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
<?php
      // Función para obtener todos los clientes
function obtenerClientes($conn) {
    $sql = "SELECT id, nombre FROM clientes";
    $result = $conn->query($sql);

    if (!$result) {
        die("Error en la consulta SQL: " . $conn->error);
    }

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

    if (!$result) {
        die("Error ejecutando la consulta SQL: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

// Obtener la lista de clientes
$clientes = obtenerClientes($conn);

// Verificar si se ha seleccionado un cliente
$client_id = isset($_GET['client_id']) ? $_GET['client_id'] : null;

// Obtener la lista de vehículos según el cliente seleccionado
if ($client_id) {
    $vehiculos = obtenerVehiculosPorCliente($conn, $client_id);
} else {
    $vehiculos = [];
}

// Depuración: puedes descomentar estas líneas para ver los valores durante la depuración
// var_dump($client_id);
// var_dump($vehiculos);
?>main_diseño.php
    
</script>

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
            <a href="main_diseño.php" class="logo">
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
                <a class="nav-link active" href="main_diseño.php">
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
                      <a href="vehiculos_diseño.php">
                        <span class="sub-item">Mis vehículos</span>
                      </a>
                    </li>
                    <li>
                      <a href="añadir_vehiculo_diseño.php">
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
                      <a href="reservas_diseño.php">
                        <span class="sub-item">Mis reservas</span>
                      </a>
                    </li>
                    <li>
                      <a href="crear_reserva_diseño.php">
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
              <a href="main_diseño.php" class="logo">
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
                          <button class="btn btn-black btn-border btn-round me-3" onclick="window.location.href='main_diseño.php';">Inicio</button>
                          <button class="btn btn-black btn-round me-3" onclick="window.location.href='vehiculos_diseño.php';">Mis vehículos</button>
                          <button class="btn btn-black btn-round" onclick="window.location.href='reservas_diseño.php';">Mis reservas</button>
                      </div>
                  </nav>

                  <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                      <li class="nav-item">
                          <span class="navbar-text me-3">
                              Buenas, <strong>Admin</strong>
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
                                              <h4>Admin</h4>
                                              <p class="text-muted">admin@huking.com</p>
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
            // Contar la cantidad de vehículos
            $query_vehiculos = "SELECT COUNT(*) AS total_vehiculos FROM vehicles";
            $result_vehiculos = $conn->query($query_vehiculos);
            $row_vehiculos = $result_vehiculos->fetch_assoc();
            $total_vehiculos = $row_vehiculos['total_vehiculos'];

            // Contar la cantidad de reservas
            $query_reservas = "SELECT COUNT(*) AS total_reservas FROM reservas";
            $result_reservas = $conn->query($query_reservas);
            $row_reservas = $result_reservas->fetch_assoc();
            $total_reservas = $row_reservas['total_reservas'];

            // Contar la cantidad de dinero
            $query_dinero = "SELECT SUM(preu) AS total_dinero FROM reservas";
            $result_dinero = $conn->query($query_dinero);
            $row_dinero = $result_dinero->fetch_assoc();
            $total_dinero = $row_dinero['total_dinero'];

            $total_vehiculos;
            $total_reservas;
            $total_dinero;

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
                          <p class="card-category">Recaudado</p>
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
                          <form action="añadir_vehiculo_diseño.php" method="get">
                            <button class="btn btn-icon btn-clean me-0" type="submit">
                              <i class="fas fa-plus"></i>
                            </button>
                          </form>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                    <form method="get" action="">
                              <label for="client_id">Cliente</label>
                              <select 
                              type="text"
                              class="form-control"
                              name="client_id" 
                              id="client_id" 
                              onchange="this.form.submit()">
                                  <option value="">Selecciona un cliente</option>
                                  <?php foreach ($clientes as $cliente): ?>
                                      <option value="<?php echo $cliente['id']; ?>" <?php echo ($client_id == $cliente['id']) ? 'selected' : ''; ?>>
                                          <?php echo $cliente['nombre']; ?>
                                      </option>
                                  <?php endforeach; ?>
                              </select>
                      </form>
                      </div>
                    <div class="card-list py-4">

                    <?php if (!empty($vehiculos)): ?>
                            <?php foreach ($vehiculos as $vehiculo): ?>

                              <div class="item-list">
                                <div class="info-user ms-3">
                                  <div class="username"><?php echo $vehiculo['marca']; ?> <?php echo $vehiculo['modelo']; ?></div>
                                  <div class="status"><?php echo $vehiculo['matricula']; ?></div>
                                </div>
                                <a href="modificar_vehiculo_diseño.php?id=<?php echo $vehiculo['id']; ?>">
                                  <button class="btn btn-icon btn-link op-8 me-1">
                                    <i class="fas fa-pen"></i>
                                  </button>
                                </a>
                                <form style="display:inline;" action="eliminar_vehiculo_diseño_main.php" method="post">
                                  <input type="hidden" name="id" value="<?php echo $vehiculo['id']; ?>">
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
                          <form action="crear_reserva_diseño.php" method="get">
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
                                    <form style="display:inline;" action="eliminar_reserva_diseño_main.php" method="post">
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
                <li class="me-3"><a href="main_diseño.php" class="text-decoration-none">Inicio</a></li>
                <li class="me-3"><a href="vehiculos_diseño.php" class="text-decoration-none">Mis Vehículos</a></li>
                <li><a href="vehiculos_diseño.php" class="text-decoration-none">Mis Reservas</a></li>
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
