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

// Obtener el ID del usuario de la sesión
$user_id = $_SESSION['user_id'];

// Iniciar una transacción para garantizar que todo se elimine correctamente
mysqli_begin_transaction($con);

try {
    // Eliminar las reseñas del usuario
    $query_resenas = "DELETE FROM resenas WHERE Usuario = $user_id";
    mysqli_query($con, $query_resenas);

    // Eliminar los elementos del carrito del usuario
    $query_carrito = "DELETE FROM carrito WHERE Usuario = $user_id";
    mysqli_query($con, $query_carrito);

    // Eliminar el historial de compras del usuario
    $query_historial = "DELETE FROM historial WHERE Usuario = $user_id";
    mysqli_query($con, $query_historial);

    // Finalmente, eliminar al usuario
    $query_usuario = "DELETE FROM usuarios WHERE ID = $user_id";
    mysqli_query($con, $query_usuario);

    // Confirmar los cambios
    mysqli_commit($con);

    // Si la eliminación fue exitosa, cerrar la sesión y redirigir a la página de inicio
    session_unset();
    session_destroy();
} catch (Exception $e) {
    // Si ocurre un error, revertir los cambios
    mysqli_rollback($con);
    echo "<div class='alert alert-danger'>Error: No se pudo borrar el usuario y sus datos asociados.</div>";
}




// Cerrar la conexión
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminación de Cuenta</title>
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
            <h1 class="display-1 ">D&D Games</h1>
        </div>
        <br>
        <div class="alert alert-success text-center">
                <strong class="display-1">¡Cuenta Borrada con Éxito!</strong><br>
                <p>Esperamos que vuelvas pronto</p>
            </div>
        <div class='mt-1 p-5 text-center'>
            <a href="index.php" class="btn btn-primary">Volver al inicio</a>
        </div>
    </div>

</body>
</html>

