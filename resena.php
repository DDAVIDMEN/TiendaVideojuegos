<?php
include("conexion.php");


// Obtener el ID del producto enviado desde detalles.php
$producto_id = $_POST['producto_id'];

// Consulta para obtener el nombre e imagen del producto
$query = "SELECT nombre, fotos FROM productos WHERE id = $producto_id";
$result = mysqli_query($con, $query);
$game = mysqli_fetch_assoc($result);

//Admin 
if (isset($_SESSION['user_id'])){
    $admin_id = $_SESSION['user_id'];
    $queryadmin = "SELECT administrador from usuarios where id = $admin_id";
    $resultadmin = mysqli_query($con, $queryadmin);
    $admin = mysqli_fetch_assoc($resultadmin);
}else{
    $admin['administrador']=0;
}


mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
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
        <?php if (!$game): ?>
            <div class='container my-5 text-center'>
                <h2 class="display-5">Producto no encontrado</h2>
                <a href="index.php" class="btn btn-primary mt-5">Volver al inicio</a>
            </div>
        <?php else: ?>
            <h1 class="my-4"><?php echo htmlspecialchars($game['nombre']); ?></h1>
            <div class="row">
                <div class="col-md-6">
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($game['fotos']); ?>" 
                        alt="<?php echo htmlspecialchars($game['nombre']); ?>" width="520" height="650">
                </div>
                <div class="col-md-6">
                    <form action="nueva_resena.php" method="POST" id="resenaForm">
                        <input type="hidden" name="producto_id" value="<?php echo $producto_id; ?>">
                        <div class="d-flex align-items-center">
                            <label for="calificacion" class="form-label fs-5 me-2"><strong>Calificación (1-10):</strong></label>
                            <input type="number" class="form-control" id="calificacion" name="calificacion" min="1" max="10" step="0.1" style="width: 100px;" required>
                        </div>
                        <label for="comentario" class="form-label fs-5"><strong>Comentarios:</strong></label>
                        <textarea class="form-control" id="comentario" name="comentario" rows="4" maxlength="600" required></textarea>
                        <button type="submit" class="btn btn-primary mt-5" id="resenaButton">Enviar Reseña</button>
                        <a href="detalles.php?id=<?php echo $producto_id; ?>" class="btn btn-danger mt-5">Cancelar</a>
                    </form>

                     <!-- Modal de confirmación -->
                     <div class="modal fade" id="resenaModal" tabindex="-1" aria-labelledby="resenaModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                        <div class="modal-dialog">
                            <div class="modal-content text-center">
                            <div class="modal-header bg-success d-flex justify-content-center">
                                <h5 class="modal-title" id="addToCartModalLabel"><strong class="text-light">¡Reseña Exitosa!</strong></h5>
                            </div>
                            <div class="modal-body text-center">
                                <strong> Reseña publicada.</strong>
                            </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        // Escuchar el clic en el botón "Enviar Reseña"
                        document.getElementById('resenaButton').addEventListener('click', function(event) {
                            event.preventDefault(); // Evitar el envío inmediato del formulario

                            var form = document.getElementById('resenaForm');
                            
                            // Validar manualmente el formulario
                            if (form.checkValidity()) {
                                // Mostrar el modal si los campos son válidos
                                var modal = new bootstrap.Modal(document.getElementById('resenaModal'), {});
                                modal.show();

                                // Esperar 3 segundos y enviar el formulario
                                setTimeout(function() {
                                    form.submit();
                                }, 3000);
                            } else {
                                // Si no es válido, mostrar mensajes de error nativos de HTML5
                                form.reportValidity();
                            }
                        });
                    </script>

                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>




