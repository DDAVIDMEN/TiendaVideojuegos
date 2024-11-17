<?php
include("conexion.php");


// Obtener el ID del usuario de la sesión
$user_id = $_SESSION['user_id'];

// Consultar los datos del usuario
$query = "SELECT nombre, correo, contrasena, nacimiento, tarjeta, Codigo_Postal FROM usuarios WHERE id = $user_id";
$result = mysqli_query($con, $query);

// Comprobar si se encontraron datos para el usuario
if ($row = mysqli_fetch_assoc($result)) {
    $nombre = htmlspecialchars($row['nombre']);
    $correo = htmlspecialchars($row['correo']);
    $contra = htmlspecialchars($row['contrasena']);
    $nacimiento = htmlspecialchars($row['nacimiento']);
    $tarjeta = htmlspecialchars($row['tarjeta']);
    $codigo_postal = htmlspecialchars($row['Codigo_Postal']);
} else {
    echo "<div class='alert alert-danger'>Error: Usuario no encontrado.</div>";
    echo $user_id;
    exit();
}

// Cerrar la conexión
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <div class="container my-2">
            <h1 class="display-4 my-3 text-center">Bienvenido, <?php echo $nombre; ?></h1> 
            <h3><small><strong>Datos Personales:</strong></small>
                <a  href="editar.php">
                    <img src="editar.jpg" alt="Game Logo" style="height: 30px;" class="rounded-pill">
                </a>
            </h3>
            <p><strong>Correo:</strong> <?php echo $correo; ?></p>
            <p><strong>Contraseña:</strong> <?php echo $contra; ?></p>
            <p><strong>Fecha de Nacimiento:</strong> <?php echo $nacimiento; ?></p>
            <p><strong>Tarjeta:</strong> <?php echo $tarjeta; ?></p>
            <p><strong>Código Postal:</strong> <?php echo $codigo_postal; ?></p>
            <div class="mt-4">
                <a href="borrar_cuenta.php" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar tu cuenta?');">Eliminar Cuenta</a>
            </div>
        </div>
    </div>
</body>
</html>
