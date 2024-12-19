<?php
include("conexion.php");

//Admin 
if (isset($_SESSION['user_id'])){
    $admin_id = $_SESSION['user_id'];
    $queryadmin = "SELECT administrador from usuarios where id = $admin_id";
    $resultadmin = mysqli_query($con, $queryadmin);
    $admin = mysqli_fetch_assoc($resultadmin);
}else{
    $admin['administrador'] = 0;
}


$nombre = $_GET['nombre'];
    

//Query
$query = "select id, fotos, nombre from productos where nombre LIKE '%$nombre%';";
if (mysqli_connect_errno()) {
    echo mysqli_connect_error();
}

$result = mysqli_query($con, $query);
$buscar = [];
while ($row = mysqli_fetch_assoc($result)) {
    $buscar[] = $row;
}
mysqli_free_result($result);
mysqli_close($con);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda</title>
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
            <h1 class="display-1">D&D Games</h1>
        </div>
        <br>
        <h2 class="my-2">Búsqueda:</h2>
        <br>
        <div class="container">
            <div class="row">
                <?php
                if ($nombre =="") {
                    echo "<div class='alert alert-danger text-center'><strong>No se proporcionó ningún nombre para buscar.</strong></div>";
                }elseif (empty($buscar)) {
                    echo "<div class='alert alert-danger text-center'><strong>No se encontró ningún producto con ese nombre.</strong></div>";
                } else {          
                    foreach ($buscar as $bus):
                        echo '<div class="col-md text-center mb-4">';
                        echo '<a href="detalles.php?id=' . $bus['id'] . '" class="text-decoration-none">';
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($bus['fotos']) . '" alt="' . $bus['nombre'] . '" width="200" height="300">';
                        echo '<h5 class="text-body">' . htmlspecialchars($bus['nombre']) . '</h5>';
                        echo '</a>';
                        echo '</div>';
                    endforeach;
                }
                ?>
            </div>
        </div>
        <div class="text-center">
            <a href="index.php" class="btn btn-primary my-4">Volver al catálogo</a>
        </div>
    
</body>
</html>
