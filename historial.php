<?php
include("conexion.php");

$user_id = $_SESSION['user_id'];

// Consulta SQL
$query = "SELECT his.fecha, pro.id, pro.nombre, his.precio, his.plataforma, pro.fotos, his.cantidad
          FROM historial his
          JOIN productos pro ON his.producto = pro.id
          WHERE his.usuario = $user_id
          ORDER BY his.fecha DESC, his.id DESC;";
if (mysqli_connect_errno()) {
    echo "<div class='alert alert-danger'><strong>Error!</strong>" . mysqli_connect_error() . "</div>";
}

$result = mysqli_query($con, $query);
$historial = [];
while ($row = mysqli_fetch_assoc($result)) {
    $fecha = $row['fecha'];
    $historial[$fecha][] = $row; // Agrupa por fecha
}
mysqli_free_result($result);
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial</title>
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
                <form class="d-flex">
                    <input class="form-control me-2" type="text" placeholder="Buscar">
                    <button class="btn btn-primary" type="button">Buscar</button>
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
                        <li class="nav-item">
                            <a href="cuenta.php" class="nav-link text-light">Mi cuenta</a>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
        </nav>

        <div class="mt-4 p-5 bg-primary text-white rounded text-center">
            <h1 class="display-1">D&D Games</h1>
        </div>
        <br>
        <h2 class="my-4">Historial de Compras</h2>
        <div class="container">
            <?php if (empty($historial)): ?>
                <p class="text-center">No hay historial disponible</p>
            <?php else: ?>
                <?php foreach ($historial as $fecha => $productos): ?>
                    <?php 
                    $totalPorFecha = 0; 
                    $totalProductos =0;
                    ?>
                    <div class="my-4">
                        <h4>Fecha: <?php echo htmlspecialchars($fecha); ?></h4>
                        <hr>
                        <?php foreach ($productos as $producto): ?>
                            <?php 
                                $subtotal = $producto['precio'] * $producto['cantidad'];
                                $totalPorFecha += $subtotal;
                                $totalProductos += $producto['cantidad'];
                            ?>
                            <div class="d-flex align-items-center mb-4 pb-3">
                                <div class="col-3 text-center">
                                    <a href="detalles.php?id=<?php echo $producto['id']; ?>" class="text-decoration-none">
                                        <img src="data:image/jpeg;base64,<?php echo base64_encode($producto['fotos']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" width="100" height="150">
                                        <h5 class="text-body"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                                    </a>
                                </div>
                                <div class="col-9 text-end">
                                    <h5 class="text-body">Precio: $<?php echo htmlspecialchars($producto['precio']); ?></h5>
                                    <h6 class="text-body">Plataforma: <?php echo htmlspecialchars($producto['plataforma']); ?></h6>
                                    <p class="text-secondary"><small>Cantidad:</small> <?php echo $producto['cantidad']; ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <!-- Muestra el total por fecha -->
                        <div class="text-end">
                            <?php
                                if($totalProductos >1){// Muestra el total
                                    echo '        <h4>Total ('. $totalProductos . ' productos): $' .  $totalPorFecha . '</h4>'; 
                                }else{
                                    echo '        <h4>Total ('. $totalProductos . ' producto): $' .  $totalPorFecha . '</h4>'; 
                                }
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>