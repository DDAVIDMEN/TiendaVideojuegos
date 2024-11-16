<?php
    
    include("conexion.php");

    $user_id = $_SESSION['user_id'];

    //Query
    $query = "SELECT pro.id, pro.nombre, ca.precio, ca.plataforma, pro.fotos, ca.cantidad 
    FROM productos pro 
    JOIN carrito ca ON ca.producto = pro.id 
    WHERE ca.usuario = $user_id 
    GROUP BY pro.id, ca.plataforma 
    ORDER BY ca.id DESC;";
    if (mysqli_connect_errno()) {
        echo "<div class='alert alert-danger'>
            <strong>Error!</strong>" . mysqli_connect_error() ."
            </div>";
    }

    $result = mysqli_query($con, $query);
    $carrito = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $carrito[] = $row;
    }
    mysqli_free_result($result);

    mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D&D Games</title>
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
        <h2 class="my-2">Carrito</h2>
        <br>
        <div class="container">
            <div class="row">
                <?php
                $total = 0; // Inicializa el total
                $cantproductos = 0;

                if (empty($carrito)) {
                    echo '<p class="text-center">El carrito está vacío</p>';
                } else {
                    foreach ($carrito as $car):
                        $subtotal = $car['precio'] * $car['cantidad']; // Calcula el subtotal del producto
                        $total += $subtotal; // Suma el subtotal al total general
                        $cantproductos += $car['cantidad'];

                        echo '<div class="col-12 d-flex align-items-center mb-4 border-bottom pb-3">';
                        echo '    <div class="col-3 text-center">';
                        echo '        <a href="detalles.php?id=' . $car['id'] . '" class="text-decoration-none">';
                        echo '            <img src="data:image/jpeg;base64,' . base64_encode($car['fotos']) . '" alt="' . $car['nombre'] . '" width="100" height="150">';
                        echo '            <h5 class="text-body">' . htmlspecialchars($car['nombre']) . '</h5>';
                        echo '        </a>';
                        echo '    </div>';
                        echo '    <div class="col-9 text-end">';
                        echo '        <h5 class="text-body">Precio: $' . htmlspecialchars($car['precio']) . '</h5>';
                        echo '        <h6 class="text-body">Plataforma: ' . htmlspecialchars($car['plataforma']) . '</h6>';
                        echo '        <p class="text-secondary"><small>Cantidad:</small> ' . $car['cantidad'] . '</p>';
                        echo '        <form method="POST" action="eliminar_producto.php" onsubmit="return confirm(\'¿Estás seguro de que quieres eliminar este producto?\');">';
                        echo '            <input type="hidden" name="producto_id" value="' . $car['id'] . '">';
                        echo '            <input type="hidden" name="plataforma" value="' . $car['plataforma'] . '">';
                        echo '            <button type="submit" class="btn btn-danger btn-sm mt-2">Eliminar Producto</button>';
                        echo '        </form>';
                        echo '    </div>';
                        echo '</div>';
                    endforeach;

                    // Muestra el total y el botón para proceder a la compra
                    echo '<div class="col-12 d-flex align-items-center justify-content-end my-4">';
                    echo '    <div class="text-end">';
                    if($cantproductos >1){// Muestra el total
                        echo '        <h4 class="text-end">Total ('. $cantproductos . ' productos): $' .  $total . '</h4>'; 
                    }else{
                        echo '        <h4 class="text-end">Total ('. $cantproductos . ' producto): $' .  $total . '</h4>'; 
                    }
                    echo '        <a href="compra.php" class="btn btn-success mt-2 text-end">Comprar</a>'; // Botón para ir a compra.php
                    echo '    </div>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>

    </div>
</body>
</html>