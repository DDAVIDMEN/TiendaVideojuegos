<?php
include("conexion.php");

$user_id = $_SESSION['user_id'];

$query = "SELECT nombre, correo, contrasena, nacimiento, tarjeta, Codigo_Postal FROM usuarios WHERE id = $user_id";
$result = mysqli_query($con, $query);

// Comprobar si se encontraron datos para el usuario
if ($row = mysqli_fetch_assoc($result)) {
    $nom = htmlspecialchars($row['nombre']);
    $correo = htmlspecialchars($row['correo']);
    $contra = htmlspecialchars($row['contrasena']);
    $naci = htmlspecialchars($row['nacimiento']);
    $tar = htmlspecialchars($row['tarjeta']);
    $cod = htmlspecialchars($row['Codigo_Postal']);
} else {
    echo "<div class='alert alert-danger'>Error: Usuario no encontrado.</div>";
    exit();
}

// Manejar la actualización de datos si el formulario es enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $contrasena = $_POST['contrasena'];
    $nacimiento = $_POST['nacimiento'];
    $tarjeta = $_POST['tarjeta'];
    $codigo_postal = $_POST['codigo_postal'];

    // Validación y sanitización de los datos se puede hacer aquí

    $updateQuery = "UPDATE usuarios SET nombre = '$nombre', contrasena = '$contrasena', nacimiento = '$nacimiento', 
    tarjeta = $tarjeta, codigo_postal = $codigo_postal WHERE id = $user_id;";

    if (mysqli_query($con, $updateQuery)) {
        // Establecer una variable de éxito para mostrar el mensaje emergente
        $success = true;
    } else {
        // Mostrar un error si ocurre un problema con la actualización
        echo "<div class='alert alert-danger'>Error al actualizar los datos</div>";
        $success = false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función para mostrar el mensaje de éxito y redirigir
        function showSuccessMessage() {
            alert("Los datos se actualizaron correctamente.");
            setTimeout(function() {
                window.location.href = "cuenta.php"; // Redirigir a cuenta.php después del mensaje
            }); 
        }
    </script>
</head>
<body>
    <div class="container mt-5">
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
            <div class="container text-center">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="navbar-brand" href="index.php">
                            <img src="logo.png" alt="Game Logo" style="width: 40px;" class="rounded-pill">
                        </a>
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
                </ul>
                <form class="d-flex" action="buscar.php" method="GET">
                    <input class="form-control me-2" type="text" name="nombre" placeholder="Buscar">
                    <button class="btn btn-primary" type="submit">Buscar</button>
                </form>

                <!-- Mostrar enlaces dependiendo del estado de sesión -->
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="registro.php" class="nav-link">Crear cuenta</a>
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
                                <li><a class="dropdown-item" href="cuenta.php">Configuración</a></li>
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
        <br>
        <h2 class="my-2">Editar Información de <?php echo $correo; ?></h2>
        <br>

        <!-- Formulario para editar usuario -->
        <form method="POST" action="editar.php">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nom; ?>" required>
            </div>

            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" class="form-control text-secondary" id="correo" name="correo" value="<?php echo $correo; ?>" disabled>
            </div>

            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input type="text" class="form-control" id="contrasena" name="contrasena" value="<?php echo $contra ?>" required>
            </div>

            <div class="mb-3">
                <label for="nacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="nacimiento" name="nacimiento" value="<?php echo $naci ?>" required>
            </div>

            <div class="mb-3">
                <label for="tarjeta" class="form-label">Número de Tarjeta</label>
                <input type="text" class="form-control" id="tarjeta" name="tarjeta" value="<?php echo $tar ?>" required>
            </div>

            <div class="mb-3">
                <label for="codigo_postal" class="form-label">Código Postal</label>
                <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" value="<?php echo $cod ?>" required>
            </div>

            <button type="submit" class="btn btn-primary mb-4 w-100">Actualizar</button>
        </form>

        <!-- Si la actualización fue exitosa, mostramos el mensaje emergente -->
        <?php if (isset($success) && $success): ?>
            <script>
                showSuccessMessage(); // Llamar a la función para mostrar el mensaje
            </script>
        <?php endif; ?>
    </div>
</body>
</html>

