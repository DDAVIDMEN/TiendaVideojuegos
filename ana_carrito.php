<?php
    include("conexion.php");

    $producto_id = $_POST['producto_id'];
    $precio = $_POST['precio'];
    $plataforma = $_POST['plataforma'];
    $cantidad = $_POST['cantidad'];
    $user_id = $_SESSION['user_id'];

    // Verificar la cantidad disponible del producto
    $query_stock = "SELECT cantidad_almacen FROM productos WHERE id = $producto_id";
    $result_stock = mysqli_query($con, $query_stock);
    $producto = mysqli_fetch_assoc($result_stock);

    if (!$producto || $cantidad > $producto['cantidad_almacen']) {
        // Si no hay suficiente inventario, redirigir con un mensaje de error
        header("Location: detalles.php?id=$producto_id&error=stock_insuficiente");
        exit;
    }

    // Verificar si el producto ya existe en el carrito
    $check_query = "SELECT cantidad FROM carrito WHERE usuario = $user_id AND producto = $producto_id AND plataforma = '$plataforma'";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Si el producto existe, actualizar la cantidad
        $row = mysqli_fetch_assoc($check_result);
        $nueva_cantidad = $row['cantidad'] + $cantidad;

        $update_query = "UPDATE carrito SET cantidad = $nueva_cantidad WHERE usuario = $user_id AND producto = $producto_id AND plataforma = '$plataforma'";
        if (mysqli_query($con, $update_query)) {
            $success = true;
        } else {
            echo "<h1>Error al actualizar el producto en el carrito. Inténtalo nuevamente.<h1>";
        }
    } else {
        // Si no existe, insertar un nuevo registro
        $insert_query = "INSERT INTO carrito (usuario, producto, precio, plataforma, cantidad) 
                         VALUES ($user_id, $producto_id, $precio, '$plataforma', $cantidad)";
        if (mysqli_query($con, $insert_query)) {
            $success = true;
        } else {
            echo "<h1>Error al registrar el producto en el carrito. Inténtalo nuevamente.<h1>";
        }
    }
    // Actualizar el inventario del producto
    $nueva_cantidad_almacen = $producto['cantidad_almacen'] - $cantidad;
    $update_stock_query = "UPDATE productos SET cantidad_almacen = $nueva_cantidad_almacen WHERE id = $producto_id";
    if (!mysqli_query($con, $update_stock_query)) {
        echo "<h1>Error al actualizar el inventario del producto. Inténtalo nuevamente.</h1>";
        exit;
    }

    mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir al carrito</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función para mostrar el mensaje de éxito y redirigir
        function MensajeExito() {
            alert("Producto añadido al carrito");
            setTimeout(function() {
                window.location.href = "carrito.php"; 
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
        <?php if (isset($success) && $success): ?>
            <script>
               MensajeExito(); // Llamar a la función para mostrar el mensaje
            </script>
        <?php endif; ?>
    </div>
</body>
</html>
