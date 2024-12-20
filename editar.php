<?php
include("conexion.php");

//Admin 
if (isset($_SESSION['user_id'])){
    $admin_id = $_SESSION['user_id'];
    $queryadmin = "SELECT administrador from usuarios where id = $admin_id";
    $resultadmin = mysqli_query($con, $queryadmin);
    $admin = mysqli_fetch_assoc($resultadmin);
}else{
    $admin['administrador']=0;
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT nombre, correo, contrasena, nacimiento, tarjeta, direccion, Codigo_Postal FROM usuarios WHERE id = $user_id";
$result = mysqli_query($con, $query);

// Comprobar si se encontraron datos para el usuario
if ($row = mysqli_fetch_assoc($result)) {
    $nom = htmlspecialchars($row['nombre']);
    $correo = htmlspecialchars($row['correo']);
    $contra = htmlspecialchars($row['contrasena']);
    $naci = htmlspecialchars($row['nacimiento']);
    $tar = htmlspecialchars($row['tarjeta']);
    $dir = htmlspecialchars($row['direccion']);
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
    $direccion = $_POST['direccion'];
    $codigo_postal = $_POST['codigo_postal'];

    // Validación y sanitización de los datos se puede hacer aquí

    $updateQuery = "UPDATE usuarios SET nombre = '$nombre', contrasena = '$contrasena', nacimiento = '$nacimiento', 
    tarjeta = $tarjeta, direccion = '$direccion', codigo_postal = $codigo_postal WHERE id = $user_id;";

    if (mysqli_query($con, $updateQuery)) {
        // Establecer una variable de éxito para mostrar el mensaje emergente
        $success = true;
    } else {
        // Mostrar un error si ocurre un problema con la actualización
        echo "<div class='alert alert-danger'>Error al actualizar los datos</div>";
        $success = false;
    }
}




mysqli_close($con);

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
            <!-- Logo -->
            <a class="navbar-brand" href="index.php">
                <img src="logo.png" alt="Game Logo" style="width: 40px;" class="rounded-pill">
            </a>

            <!-- Botón Hamburguesa -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Contenido Navbar -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <!-- Menú de Navegación -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link">Catálogo</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Categorías</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="accion.php">Acción</a></li>
                            <li><a class="dropdown-item" href="deportes.php">Deportes</a></li>
                            <li><a class="dropdown-item" href="estrategia.php">Estrategia</a></li>
                            <li><a class="dropdown-item" href="role.php">Role-Play</a></li>
                            <li><a class="dropdown-item" href="carreras.php">Carreras</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="oferta.php" class="nav-link">Ofertas</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Exclusivos</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="play.php">PlayStation</a></li>
                            <li><a class="dropdown-item" href="xbox.php">Xbox</a></li>
                            <li><a class="dropdown-item" href="switch.php">Switch</a></li>
                            <li><a class="dropdown-item" href="pc.php">PC</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="about.php" class="nav-link">Acerca de</a>
                    </li>

                    <!-- Administración (solo si es administrador) -->
                    <?php if($admin['administrador'] ==1): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Administrador</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="inventario.php">Inventario</a></li>
                            <li><a class="dropdown-item" href="nuevo_producto.php">Nuevo Producto</a></li>
                            <li><a class="dropdown-item" href="modi_producto.php">Modificar Producto</a></li>
                            <li><a class="dropdown-item" href="usuarios.php">Usuarios</a></li>
                            <li><a class="dropdown-item" href="historialadmin.php">Historial de Compras</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>

                <!-- Barra de Búsqueda -->
                <form class="d-flex" style="margin-right: 5rem;" action="buscar.php" method="GET">
                    <input class="form-control me-2" type="text" name="nombre" placeholder="Buscar">
                    <button class="btn btn-primary" type="submit">Buscar</button>
                </form>

                <!-- Enlaces de Sesión -->
                <ul class="navbar-nav">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a href="registro.php" class="nav-link me-3">Crear cuenta</a>
                    </li>
                    <li class="nav-item">
                        <a href="login.php" class="nav-link">Iniciar sesión</a>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="navbar-brand me-3" href="carrito.php">
                            <img src="carrito.png" alt="Game Logo" style="width: 40px;" class="rounded-pill">
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Mi cuenta</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="cuenta.php">Detalles de Mi cuenta</a></li>
                            <li><a class="dropdown-item" href="historial.php">Historial de Pedidos</a></li>
                            <li><a class="dropdown-item" href="cerrar_sesion.php">Cerrar Sesión</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        </nav>

        <div class="mt-4 p-5 bg-primary text-white rounded text-center">
            <h1 class="display-1">D&D Games</h1>
        </div>
        <br>
        <?php if($admin['administrador'] ==1): ?>
        <h2 class="my-2">Editar Información de <?php echo $correo; ?></h2>
        <br>

        <!-- Formulario para editar usuario -->
        <form method="POST" action="editar.php" id="editarForm">
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
                <input type="number" class="form-control" id="tarjeta" name="tarjeta" value="<?php echo $tar ?>" required>
            </div>

            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo $dir ?>" required>
            </div>

            <div class="mb-3">
                <label for="codigo_postal" class="form-label">Código Postal</label>
                <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" value="<?php echo $cod ?>" required>
            </div>

            <button type="submit" class="btn btn-primary mb-4 w-100" id="editarButton">Actualizar</button>
        </form>

         <!-- Modal de confirmación -->
         <div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header bg-primary d-flex justify-content-center">
                    <h5 class="modal-title" id="editarLabel"><strong class="text-light">Cambios Guardados</strong></h5>
                </div>
                <div class="modal-body text-center">
                    <strong>Los datos se actualizaron correctamente.</strong>
                </div>
                </div>
            </div>
            </div>

            <script>
            // Escuchar el clic en el botón "Añadir al carrito"
            document.getElementById('editarButton').addEventListener('click', function() {
                event.preventDefault()
                var form = document.getElementById('editarForm');
                
                // Mostrar el modal
                var modal = new bootstrap.Modal(document.getElementById('editarModal'), {});
                modal.show();

                // Esperar 3 segundos y enviar el formulario
                setTimeout(function() {
                form.submit();
                }, 3000);
            });
            </script>
        <?php if (isset($success) && $success): ?>
            <script>
                showSuccessMessage(); // Llamar a la función para mostrar el mensaje
            </script>
        <?php endif; ?>
        <?php else: ?>
            <div class="alert alert-danger text-center">
                <strong class="display-5">No eres administrador</strong><br><br><br>
                <a href="index.php" class="alert-link text-center">Volver al catálogo</a>.
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

