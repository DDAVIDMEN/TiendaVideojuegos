<?php
include("conexion.php");

$error = "";
$usuarios = [];
$usuario_seleccionado = null;

// Obtener todos los correos electrónicos de la tabla `usuarios`
$query_usuarios = "SELECT ID, Correo FROM usuarios";
$result_usuarios = mysqli_query($con, $query_usuarios);

if ($result_usuarios) {
    while ($row = mysqli_fetch_assoc($result_usuarios)) {
        $usuarios[] = $row;
    }
}

// Manejar la selección del correo
if (isset($_POST['correo_seleccionado'])) {
    $usuario_id = mysqli_real_escape_string($con, $_POST['correo_seleccionado']);
    
    // Obtener información del usuario seleccionado
    $query_usuario = "SELECT * FROM usuarios WHERE ID = '$usuario_id'";
    $result_usuario = mysqli_query($con, $query_usuario);
    if ($result_usuario && mysqli_num_rows($result_usuario) > 0) {
        $usuario_seleccionado = mysqli_fetch_assoc($result_usuario);
    }
}

// Manejar la actualización del usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar_usuario'])) {
    $usuario_id = mysqli_real_escape_string($con, $_POST['usuario']);
    $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
    $correo = mysqli_real_escape_string($con, $_POST['correo']);
    $nacimiento = mysqli_real_escape_string($con, $_POST['nacimiento']);
    $tarjeta = mysqli_real_escape_string($con, $_POST['tarjeta']);
    $direccion = mysqli_real_escape_string($con, $_POST['direccion']);
    $codigo_postal = mysqli_real_escape_string($con, $_POST['codigo_postal']);
    $administrador = isset($_POST['administrador']) ? 1 : 0;

    // Actualizar información del usuario
    $query_update_usuario = "UPDATE usuarios SET 
                                Nombre = '$nombre', 
                                Correo = '$correo', 
                                Nacimiento = '$nacimiento', 
                                Tarjeta = '$tarjeta', 
                                Direccion = '$direccion', 
                                Codigo_Postal = '$codigo_postal', 
                                Administrador = '$administrador'
                              WHERE ID = '$usuario_id'";
    if (!mysqli_query($con, $query_update_usuario)) {
        $error = "Error al actualizar el usuario.";
    }

    if (empty($error)) {
        echo "<script>
                alert('Usuario actualizado correctamente');
                window.location.href = 'usuarios.php';
            </script>";
    }
}

//Admin 
if (isset($_SESSION['user_id'])){
    $admin_id = $_SESSION['user_id'];
    $queryadmin = "SELECT administrador from usuarios where id = $admin_id";
    $resultadmin = mysqli_query($con, $queryadmin);
    $admin = mysqli_fetch_assoc($resultadmin);
}else{
    $admin['administrador']=0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Usuarios</title>
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
                        <!--Administracion -->
                        <?php if($admin['administrador'] ==1): ?>
                            <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle active" href="#" role="button"
                                data-bs-toggle="dropdown">Administrador</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="inventario.php">Inventario</a></li>
                                <li><a class="dropdown-item" href="nuevo_producto.php">Nuevo Producto</a></li>
                                <li><a class="dropdown-item" href="modi_producto.php">Modificar Producto</a></li>
                                <li><a class="dropdown-item active" href="usuarios.php">Usuarios</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
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
            <h1 class="display-1 ">D&D Games</h1>
        </div>
        <br>
    <h2 class="my-3">Modificar Usuario</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Formulario para seleccionar el correo -->
    <form method="post">
        <div class="mb-3">
            <label for="correo_seleccionado" class="form-label">Seleccionar Correo:</label>
            <select class="form-control" id="correo_seleccionado" name="correo_seleccionado" onchange="this.form.submit()">
                <option value="" disabled selected>Seleccione un correo</option>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?php echo $usuario['ID']; ?>" <?php echo (isset($_POST['correo_seleccionado']) && $_POST['correo_seleccionado'] == $usuario['ID']) ? 'selected' : ''; ?>>
                        <?php echo $usuario['Correo']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <?php if ($usuario_seleccionado): ?>
        <!-- Formulario para modificar el usuario -->
        <form method="post">
            <input type="hidden" name="usuario" value="<?php echo $usuario_seleccionado['ID']; ?>">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $usuario_seleccionado['Nombre']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo:</label>
                <input type="email" class="form-control" id="correo" name="correo" value="<?php echo $usuario_seleccionado['Correo']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="nacimiento" class="form-label">Fecha de Nacimiento:</label>
                <input type="date" class="form-control w-50" id="nacimiento" name="nacimiento" value="<?php echo $usuario_seleccionado['Nacimiento']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="tarjeta" class="form-label">Tarjeta:</label>
                <input type="text" class="form-control w-50" id="tarjeta" name="tarjeta" value="<?php echo $usuario_seleccionado['Tarjeta']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección:</label>
                <textarea class="form-control" id="direccion" name="direccion" rows="3" required><?php echo $usuario_seleccionado['Direccion']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="codigo_postal" class="form-label">Código Postal:</label>
                <input type="number" class="form-control w-25" id="codigo_postal" name="codigo_postal" value="<?php echo $usuario_seleccionado['Codigo_Postal']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="administrador" class="form-label">Administrador:</label>
                <input type="checkbox" id="administrador" name="administrador" value="1" <?php echo ($usuario_seleccionado['Administrador'] == 1) ? 'checked' : ''; ?>>
            </div>
            <div class="my-5">
                <button type="submit" class="btn btn-primary w-100" name="actualizar_usuario">Actualizar Usuario</button>
            </div>
        </form>
    <?php endif; ?>
</div>
</body>
</html>

<?php
// Cerrar la conexión
mysqli_close($con);
?>