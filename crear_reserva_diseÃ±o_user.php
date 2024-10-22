<?php
// Conexión a la base de datos
//$conn = mysqli_connect('localhost', 'huk', 'huk3', 'DB_huk');
//if (!$conn) {
//    die("Connection failed: " . mysqli_connect_error());
//}

include 'db_conexion.php';

session_start();
// Verificar si el usuario está logueado
if (!isset($_SESSION['logget'])) {
    header("Location: login_diseño.php"); // Redirigir a la página de inicio de se>    exit;
}

// Aquí puedes acceder a la información del usuario
$username = $_SESSION['logget'];
$user_id = $_SESSION['user_id'];


function generarNumeroReserva() {
    // Generar 3 letras aleatorias
    $letras = '';
    $caracteres_letras = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    for ($i = 0; $i < 3; $i++) {
        $letras .= $caracteres_letras[rand(0, strlen($caracteres_letras) - 1)];
    }

    // Generar 8 dígitos aleatorios
    $digitos = '';
    for ($i = 0; $i < 8; $i++) {
        $digitos .= rand(0, 9);
    }

    // Combinar letras y dígitos
    return $letras . $digitos;
}

// Verifica si es una petición AJAX para cargar vehículos o plazas
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'fetch_vehicles') {
        // Obtener vehículos según el cliente seleccionado
        if (isset($_GET['client_id'])) {
            $client_id = $_GET['client_id'];
            $vehicles = mysqli_query($conn, "SELECT * FROM vehicles WHERE client_id = '$client_id'");
            
            if (mysqli_num_rows($vehicles) > 0) {
                while ($vehicle = mysqli_fetch_assoc($vehicles)) {
                    echo "<option value='".$vehicle['id']."'>".$vehicle['matricula']."</option>";
                }
            } else {
                echo "<option value=''>No hay vehículos disponibles</option>";
            }
        }
        exit;
    } elseif ($_GET['action'] == 'fetch_places') {
        // Obtener plazas según el parquing seleccionado
        if (isset($_GET['parquing_id'])) {
            $parquing_id = $_GET['parquing_id'];
            $places = mysqli_query($conn, "SELECT * FROM places WHERE parquing_id = '$parquing_id'");
            
            if (mysqli_num_rows($places) > 0) {
                while ($place = mysqli_fetch_assoc($places)) {
                    echo "<option value='".$place['id']."'>".$place['numero_plaza']."</option>";
                }
            } else {
                echo "<option value=''>No hay plazas disponibles</option>";
            }
        }
        exit;
    } elseif ($_GET['action'] == 'calculate_price') {
        // Calcular precio basado en las fechas de entrada y salida
        if (isset($_GET['entry_date']) && isset($_GET['exit_date'])) {
            $entry_date = $_GET['entry_date'];
            $exit_date = $_GET['exit_date'];
            
            $duration = strtotime($exit_date) - strtotime($entry_date);
            $hours = ceil($duration / 3600);
            $price = $hours * 1.90 + ($hours > 1 ? ($hours - 1) * 0.40 : 0);

            echo number_format($price, 2);
        }
        exit;
    }
}
// Si es una solicitud POST (formulario enviado), manejar la creación de la reserva
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //print('entarando en ponst');
    $client_id = mysqli_real_escape_string($conn, $_POST["client"]);
    $vehicle_id = mysqli_real_escape_string($conn, $_POST["vehicle"]);
    $parquing_id = mysqli_real_escape_string($conn, $_POST["parquing"]);
    $place_id = mysqli_real_escape_string($conn, $_POST["place"]);
    $entry_date = mysqli_real_escape_string($conn, $_POST["entry-date"]);
    $exit_date = mysqli_real_escape_string($conn, $_POST["exit-date"]);
    $price = mysqli_real_escape_string($conn, $_POST["price"]);

    $numero_reserva = generarNumeroReserva();

    // Insertar la reserva en la base de datos
    $query = "INSERT INTO reservas (client_id, vehicle_id, parquing_id, place_id, data_entrada, data_sortida, estat, preu, numero_reserva)
              VALUES ('$client_id', '$vehicle_id', '$parquing_id', '$place_id', '$entry_date', '$exit_date', 'Ocupado', '$price', '$numero_reserva')";


    if (mysqli_query($conn, $query)) {
		$msg = "Reserva creada con el número: " . $numero_reserva;
		header("Location: reservas_diseño_user.php");// nos redirige al login cuando hayamos creado la reserva
		exit;
        //echo "Reserva creada exitosamente";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
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


?>


<!DOCTYPE html>
<html lang="en">
  <head>




  <script>
	// Cargar vehículos al cargar la página
        window.onload = function() {
            loadVehicles(<?php echo $user_id; ?>);
        };

        // AJAX para cargar vehículos según cliente seleccionado
        function loadVehicles(clientId) {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "?action=fetch_vehicles&client_id=" + clientId, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    document.getElementById("vehicle").innerHTML = this.responseText;
                }
            };
            xhr.send();
        }

        // AJAX para cargar lugares según parquing seleccionado
        function loadPlaces(parquingId) {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "?action=fetch_places&parquing_id=" + parquingId, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    document.getElementById("place").innerHTML = this.responseText;
                }
            };
            xhr.send();
        }

        // AJAX para calcular el precio basado en las fechas de entrada y salida
        function calculatePrice() {
            const entryDate = document.getElementById("entry-date").value;
            const exitDate = document.getElementById("exit-date").value;

            if (entryDate && exitDate) {
                const xhr = new XMLHttpRequest();
                xhr.open("GET", "?action=calculate_price&entry_date=" + entryDate + "&exit_date=" + exitDate, true);
                xhr.onload = function() {
                    if (this.status === 200) {
                        document.getElementById("price").value = this.responseText;
                    }
                };
                xhr.send();
            }
        }
    </script>








    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Añadir reserva</title>
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



              <li class="nav-item active submenu">
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
                    <li class="active">
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
          <!-- Navbar Header -->
          <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
              <div class="container-fluid d-flex align-items-center">
                  <!-- Botones centrados sin input -->
                  <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                      <div class="d-flex">
                          <button class="btn btn-black btn-round me-3" onclick="window.location.href='main_diseño_user.php';">Inicio</button>
                          <button class="btn btn-black btn-round me-3" onclick="window.location.href='vehiculos_diseño_user.php';">Mis vehículos</button>
                          <button class="btn btn-black btn-border btn-round" onclick="window.location.href='reservas_diseño_user.php';">Mis reservas</button>
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
                                <div class="card-title">Añadir reserva</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <form id="reservation-form" action="crear_reserva_diseño_user.php" method="post">
                                        <!-- Cliente -->
    

                                        <!-- Campo oculto para enviar el client_id al servidor -->
                                        <input type="hidden" id="client" name="client" value="<?php echo $user_id; ?>">

                                        <!-- Vehículo -->
                                        <div class="form-group">
                                            <label for="vehicle">Vehículo</label>
                                            <select class="form-select form-control" id="vehicle" name="vehicle" required>
                                                <option value="">Seleccione un vehículo</option>
                                            </select>
                                        </div>

                                        <!-- Parquing -->
                                        <div class="form-group">
                                            <label for="parquing">Parquing</label>
                                            <select class="form-select form-control" id="parquing" name="parquing" onchange="loadPlaces(this.value)" required>
                                                <option value="">Seleccione un parquing</option>
                                                <?php
                                                // fetch parquings from database
                                                $parquings = mysqli_query($conn, "SELECT * FROM parquings");
                                                while ($parquing = mysqli_fetch_assoc($parquings)) {
                                                    echo "<option value='".$parquing['id']."'>".$parquing['nom']."</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <!-- Plaza -->
                                        <div class="form-group">
                                            <label for="place">Plaza</label>
                                            <select class="form-select form-control" id="place" name="place" required>
                                                <option value="">Seleccione una plaza</option>
                                            </select>
                                        </div>

                                        <!-- Fecha entrada -->
                                        <div class="form-group">
                                            <label for="entry-date">Fecha entrada:</label>
                                            <input type="datetime-local" class="form-control" id="entry-date" name="entry-date" required onchange="calculatePrice()">
                                        </div>

                                        <!-- Fecha salida -->
                                        <div class="form-group">
                                            <label for="exit-date">Fecha salida:</label>
                                            <input type="datetime-local" class="form-control" id="exit-date" name="exit-date" required onchange="calculatePrice()">
                                        </div>

                                        <!-- Precio -->
                                        <div class="form-group">
                                            <label for="price">Precio:</label>
                                            <input type="text" class="form-control" id="price" name="price" readonly required>
                                        </div>

                                        <div class="card-action">
                                            <button type="submit" class="btn btn-success">Añadir</button>
                                            <button type="button" class="btn btn-danger" onclick="window.location.href='reservas_diseño_user.php';">Volver</button>
                                        </div>
                                    </form>
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
