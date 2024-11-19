<?php
include("conexion.php");

$error = "";
$nombre = $correo = $contra = $naci = $tarjeta = $direccion = $postal = ""; // Inicializar las variables

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escapar y almacenar los datos del formulario
    $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
    $correo = mysqli_real_escape_string($con, $_POST['correo']);
    $contra = mysqli_real_escape_string($con, $_POST['contra']);
    $naci = mysqli_real_escape_string($con, $_POST['naci']);
    $tarjeta = mysqli_real_escape_string($con, $_POST['tarjeta']);
    $direccion = mysqli_real_escape_string($con, $_POST['direccion']);
    $postal = mysqli_real_escape_string($con, $_POST['postal']);

    // Verificar si el correo ya existe en la base de datos
    $query = "SELECT id FROM usuarios WHERE correo = '$correo'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        // Si el correo ya existe, establecer el mensaje de error
        $error = "Ya existe un usuario registrado con este correo. Intente con otro";
    } else {
        // Si el correo no existe, insertar los datos del usuario
        $insert_query = "INSERT INTO usuarios (nombre, correo, contrasena, nacimiento, tarjeta, direccion, Codigo_Postal) 
                         VALUES ('$nombre', '$correo', '$contra', '$naci', '$tarjeta','$direccion', '$postal')";

        if (mysqli_query($con, $insert_query)) {
            // Obtén el ID del usuario recién insertado
            $user_id = mysqli_insert_id($con);

            // Guarda el ID en la variable de sesión
            $_SESSION['user_id'] = $user_id;

            // Redirigir a cuenta.php si la inserción fue exitosa
            header("Location: cuenta.php");
            exit();
        } else {
            // Mostrar un error si ocurre un problema con la inserción
            $error = "Error al registrar el usuario. Inténtalo nuevamente.";
        }
    }

}

// Cerrar la conexión
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <!--Contenedor principal de BS5-->
    <div class="container">
        <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
            <div class="container text-center">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="navbar-brand" href="index.php">
                            <img src="logo.png" alt="Game Logo" style="width: 40px;" class="rounded-pill">
                        </a>
                    </li>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="collapsibleNavbar">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link">Catálogo</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown">Categorías</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="accion.php">Acción</a></li>
                                <li><a class="dropdown-item" href="deportes.php">Deportes</a></li>
                                <li><a class="dropdown-item" href="estrategia.php">Estrategia</a></li>
                                <li><a class="dropdown-item" href="role.php">Role-Play</a></li>
                                <li><a class="dropdown-item" href="carreras.php">Carreras</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="oferta.php">Ofertas</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown">Exclusivos</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="play.php">PlayStation</a></li>
                                <li><a class="dropdown-item" href="xbox.php">Xbox</a></li>
                                <li><a class="dropdown-item" href="switch.php">Switch</a></li>
                                <li><a class="dropdown-item" href="pc.php">PC</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="about.php">Acerca de</a>
                        </li>
                    </div>
                </ul>
                <form class="d-flex" action="buscar.php" method="GET">
                    <input class="form-control me-2" type="text" name="nombre" placeholder="Buscar">
                    <button class="btn btn-primary" type="submit">Buscar</button>
                </form>

                <!-- Mostrar enlaces dependiendo del estado de sesión -->
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="registro.php" class="nav-link active">Crear cuenta</a>
                        </li>
                        <li class="nav-item">
                            <a href="login.php" class="nav-link">Iniciar sesión</a>
                        </li>
                    </ul>
                <?php else: ?>

                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="navbar-brand" href="carrito.php">
                                <img src="carrito.png" alt="Game Logo" style="width: 40px;" class="rounded-pill">
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown">Mi cuenta</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="cuenta.php">Detalles de Mi cuenta</a></li>
                                <li><a class="dropdown-item" href="historial.php">Historial de Pedidos</a></li>
                                <li><a class="dropdown-item" href="cerrar_sesion.php">Cerrar Sesión</a></li>
                            </ul>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
        </nav>

        <div class="mt-4 p-5 bg-primary text-white rounded text-center">
            <h1 class="display-1">D&D Games</h1>
        </div>
        <div class="text-center">
            <p class="mb-0">¿Ya tienes una cuenta?</p>
            <a href="login.php" class="text-secondary">Inicia sesión</a>
        </div>
        <br>
        <h2 class="my-2">Nuevo Usuario</h2>

        <!-- Mostrar el mensaje de error si existe -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-center"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="registro.php" method="post">
            <div class="mb-3 mt-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" id="nombre" placeholder="Ingresa tu nombre" name="nombre" required value="<?php echo htmlspecialchars($nombre); ?>">
            </div>
            <div class="mb-3 mt-3">
                <label for="correo" class="form-label">Correo:</label>
                <input type="email" class="form-control" id="correo" placeholder="Ingresa tu correo" name="correo" required value="<?php echo htmlspecialchars($correo); ?>">
            </div>
            <div class="mb-3 mt-3">
                <label for="contra" class="form-label">Contraseña:</label>
                <input type="password" class="form-control" id="contra" placeholder="Crea tu contraseña" name="contra" required value="<?php echo htmlspecialchars($contra); ?>">
            </div>
            <div class="mb-3 mt-3">
                <label for="naci" class="form-label">Nacimiento:</label>
                <input type="date" class="form-control w-25" id="naci" name="naci" required value="<?php echo htmlspecialchars($naci); ?>">
            </div>
            <div class="mb-3 mt-3">
                <label for="tarjeta" class="form-label">Tarjeta:</label>
                <input type="number" class="form-control" id="tarjeta" placeholder="16 dígitos" name="tarjeta" required value="<?php echo htmlspecialchars($tarjeta); ?>">
            </div>
            <div class="mb-3 mt-3">
                <label for="direccion" class="form-label">Dirección:</label>
                <input type="text" class="form-control" id="direccion" placeholder="Ingresa tu dirección" name="direccion" required value="<?php echo htmlspecialchars($direccion); ?>">
            </div>
            <div class="mb-3 mt-3">
                <label for="postal" class="form-label">Código Postal:</label>
                <input type="number" class="form-control w-25" id="postal" placeholder="Zona Postal" name="postal" required value="<?php echo htmlspecialchars($postal); ?>">
            </div>
            <div class="my-3">
                <button type="submit" class="btn btn-primary w-100">Crear</button>
            </div>
        </form>
    </div>
</body>
</html>

