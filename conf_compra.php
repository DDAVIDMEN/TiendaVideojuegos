<?php
include("conexion.php");

$user_id = $_SESSION['user_id'];

// Consulta para obtener los datos del carrito
$query = "SELECT p.ID, p.Nombre, c.Precio, c.Plataforma, p.Fotos, c.Cantidad 
FROM carrito c 
JOIN productos p ON c.Producto = p.ID 
WHERE c.Usuario = $user_id
ORDER BY c.id desc";
$result = mysqli_query($con, $query);

$carrito = [];
$totalCarrito = 0;
$totalProductos = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $carrito[] = $row;
    $totalCarrito += $row['Precio'] * $row['Cantidad'];
    $totalProductos += $row['Cantidad'];
}
mysqli_free_result($result);

// Consulta para obtener los datos del usuario
$queryUsuario = "SELECT Nombre, Direccion, Codigo_Postal, Tarjeta FROM usuarios WHERE ID = $user_id";
$resultUsuario = mysqli_query($con, $queryUsuario);
$usuario = mysqli_fetch_assoc($resultUsuario);

// Calcular si hay ofertas (usando la tabla promociones y comparando precios)
$totalOriginal = 0;
foreach ($carrito as $item) {
    $queryProducto = "SELECT Precio FROM productos WHERE ID = " . $item['ID'];
    $resultProducto = mysqli_query($con, $queryProducto);
    $producto = mysqli_fetch_assoc($resultProducto);
    $totalOriginal += $producto['Precio'] * $item['Cantidad'];
    mysqli_free_result($resultProducto);
}

$ahorro = $totalOriginal - $totalCarrito;


//Admin 
if (isset($_SESSION['user_id'])){
    $admin_id = $_SESSION['user_id'];
    $queryadmin = "SELECT administrador from usuarios where id = $admin_id";
    $resultadmin = mysqli_query($con, $queryadmin);
    $admin = mysqli_fetch_assoc($resultadmin);
}else{
    $admin['administrador'] = 0;
}

// Cerrar conexión
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Compra</title>
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
                            <a class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown">Administrador</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="inventario.php">Inventario</a></li>
                                <li><a class="dropdown-item" href="nuevo_producto.php">Nuevo Producto</a></li>
                                <li><a class="dropdown-item" href="modi_producto.php">Modificar Producto</a></li>
                                <li><a class="dropdown-item" href="usuarios.php">Usuarios</a></li>
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
            <h1 class="display-1">D&D Games</h1>
        </div>
        <br>
        <?php 
            if($totalProductos >1){// Muestra el total
                echo '<h1 class="text-center mb-4 display-4"><strong>Proceder al Pago ('. $totalProductos . ' productos)</strong></h1>'; 
            }else{
                echo '<h1 class="text-center mb-4 display-4"><strong>Proceder al Pago ('. $totalProductos . ' producto)</strong></h1>'; 
            }
        ?>

        <!-- Dirección de envío -->
        <div class="container p-5 my-5 border">
        <h2>Dirección de envío</h2>
        <ul>
            <li><strong>Nombre:</strong> <?= htmlspecialchars($usuario['Nombre']) ?></li>
            <li><strong>Dirección:</strong> <?= htmlspecialchars($usuario['Direccion']) ?></li>
            <li><strong>Código Postal:</strong> <?= htmlspecialchars($usuario['Codigo_Postal']) ?></li>
        </ul>

        <!-- Método de pago -->
        <h2>Método de pago</h2>
        <ul>
            <li><strong>Tarjeta:</strong> <?= htmlspecialchars($usuario['Tarjeta']) ?></li>
        </ul>

        <!-- Ofertas -->
        <h2>Ofertas</h2>
        <?php if ($ahorro > 0): ?>
            <p>¡Te ahorraste <strong>$<?=$ahorro ?></strong> en esta compra!</p>
        <?php else: ?>
            <p>No hay ofertas disponibles en esta compra.</p>
        <?php endif; ?>

        <!-- Revisar los productos -->
        <h2>Revisar los productos</h2>
        <div class="row">
            <?php foreach ($carrito as $car): 
                 echo '<div class="col-12 d-flex align-items-center mb-4 border-bottom pb-3">';
                 echo '    <div class="col-3 text-center">';
                 echo '        <img src="data:image/jpeg;base64,' . base64_encode($car['Fotos']) . '" alt="' . $car['Nombre'] . '" width="100" height="150">';
                 echo '        <h5 class="text-body">' . htmlspecialchars($car['Nombre']) . '</h5>';
                 echo '    </div>';
                 echo '    <div class="col-9 text-end">';
                 echo '        <h5 class="text-body">Precio: $' . htmlspecialchars($car['Precio']) . '</h5>';
                 echo '        <h6 class="text-body">Plataforma: ' . htmlspecialchars($car['Plataforma']) . '</h6>';
                 echo '        <p class="text-secondary"><small>Cantidad:</small> ' . $car['Cantidad'] . '</p>';
                 echo '    </div>';
                 echo '</div>';
            endforeach; ?>
        </div>

        <!-- Total -->
        <?php 
            if($totalProductos >1){// Muestra el total
                echo '<h2 class="text-end mb-4">Total ('. $totalProductos . ' productos): $' .  $totalCarrito . '</h2>'; 
            }else{
                echo '<h2 class="text-end mb-4">Total ('. $totalProductos . ' producto): $' .  $totalCarrito . '</h2>'; 
            }
        ?>

        <!-- Botón para confirmar compra -->
    
        <div class="text-end my-4">
            <a href="carrito.php" class="btn btn-danger">Cancelar Compra</a>
            <button class="btn btn-success" id="confirmarCompraButton">Confirmar Compra</button>
        </div>

        <!-- Modal de Confirmación -->
        <div class="modal fade" id="compraExitosaModal" tabindex="-1" aria-labelledby="compraExitosaLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title text-light" id="compraExitosaLabel">¡Compra Exitosa!</h5>
                    </div>
                    <div class="modal-body">
                        <strong> ¡Gracias por tu compra! Tu pedido ha sido confirmado.</strong>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Escuchar el clic en el botón "Confirmar Compra"
            document.getElementById('confirmarCompraButton').addEventListener('click', function() {
                // Mostrar el modal
                var modal = new bootstrap.Modal(document.getElementById('compraExitosaModal'), {});
                modal.show();

                // Ocultar el modal automáticamente después de 3 segundos
                setTimeout(function() {
                    modal.hide();
                    window.location.href = 'compra.php';
                }, 4000);
            });
        </script>

        </div>
    </div>
</body>

</html>

