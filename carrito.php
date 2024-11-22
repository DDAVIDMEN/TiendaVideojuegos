<?php
    
    include("conexion.php");

    $user_id = $_SESSION['user_id'];

    //Query
    $query = "SELECT pro.id, pro.nombre, pro.cantidad_almacen, ca.precio, ca.plataforma, pro.fotos, ca.cantidad 
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
                            <a class="navbar-brand active" href="carrito.php">
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
        <h2 class="my-2">Carrito</h2>
        <br>
        <div class="container">
            <div class="row">
                <?php
                $total = 0; // Inicializa el total
                $cantproductos = 0;

                if (empty($carrito)) {
                    echo '<p class="display-5 text-center">El carrito está vacío</p>';
                    echo '<div class="text-center">';
                    echo '<a href="index.php" class="btn btn-primary mt-5">Volver al catálogo</a></div';
                } else {
                    foreach ($carrito as $car):
                        $uniqueId = $car['id'] . '-' . htmlspecialchars($car['plataforma']); // Genera un ID único basado en el producto y plataforma
                        $subtotal = $car['precio'] * $car['cantidad'];
                        $total += $subtotal;
                        $cantproductos += $car['cantidad'];
                ?>
                        <div class="col-12 d-flex align-items-center mb-4 border-bottom pb-3">
                            <div class="col-3 text-center">
                                <a href="detalles.php?id=<?= $car['id'] ?>" class="text-decoration-none">
                                    <img src="data:image/jpeg;base64,<?= base64_encode($car['fotos']) ?>" alt="<?= $car['nombre'] ?>" width="100" height="150">
                                    <h5 class="text-body"><?= htmlspecialchars($car['nombre']) ?></h5>
                                </a>
                            </div>
                            <div class="col-9 text-end">
                                <h5 class="text-body">Precio: $<?= htmlspecialchars($car['precio']) ?></h5>
                                <h6 class="text-body">Plataforma: <?= htmlspecialchars($car['plataforma']) ?></h6>
                                <form method="POST" action="act_carrito.php" class="text-end" id="actualizarForm-<?= $uniqueId ?>">
                                    <label for="cantidad" class="form-label">
                                        <small class="text-secondary">Cantidad:</small>
                                    </label>
                                    <input type="number" class="form-control text-end text-secondary mb-2" id="cantidad-<?= $uniqueId ?>" 
                                        style="width: 60px; display: inline-block;" name="cantidad" placeholder="<?= $car['cantidad'] ?>" 
                                        min="1" value="<?= $car['cantidad'] ?>" max="<?= $car['cantidad_almacen'] + $car['cantidad'] ?>">
                                    <input type="hidden" name="producto_id" value="<?= $car['id'] ?>">
                                    <input type="hidden" name="plataforma" value="<?= $car['plataforma'] ?>">
                                    <br>
                                    <button type="button" class="btn btn-primary btn-sm mt-2 actualizarButton" data-target="#actualizarModal-<?= $uniqueId ?>">Actualizar Producto</button>
                                </form>
                                <!-- Modal de confirmación -->
                                <div class="modal fade" id="actualizarModal-<?= $uniqueId ?>" tabindex="-1" aria-labelledby="actualizarModalLabel-<?= $uniqueId ?>" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary d-flex justify-content-center">
                                                <h5 class="modal-title text-light" id="actualizarModalLabel-<?= $uniqueId ?>">¡Actualización Exitosa!</h5>
                                            </div>
                                            <div class="modal-body text-center">
                                                <strong>El producto ha sido actualizado exitosamente</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    // Escuchar el clic en todos los botones "Actualizar Producto"
                                    document.querySelectorAll('.actualizarButton').forEach(function(button) {
                                    button.addEventListener('click', function() {
                                        var targetModalId = this.getAttribute('data-target');

                                        // Eliminar cualquier capa de fondo previa
                                        document.querySelectorAll('.modal-backdrop').forEach(function(backdrop) {
                                            backdrop.remove();
                                        });


                                        // Mostrar el nuevo modal
                                        var modal = new bootstrap.Modal(document.querySelector(targetModalId), {});
                                        modal.show();

                                        // Asociar el formulario correcto al modal y enviar después de 3 segundos
                                        var formId = this.closest('form').getAttribute('id');
                                        var form = document.getElementById(formId);

                                        setTimeout(function() {
                                            form.submit();
                                        }, 2000);
                                    });
                                });

                                </script>

                                
                                <form method="POST" action="eliminar_producto.php" id="eliminarForm-<?= $uniqueId ?>">
                                    <input type="hidden" name="producto_id" value="<?php echo $car['id'] ?>">
                                    <input type="hidden" name="plataforma" value="<?php echo $car['plataforma'] ?>">
                                    <button type="button" class="btn btn-danger btn-sm mt-2 eliminarButton" data-target="#confirmarModal-<?= $uniqueId ?>">Eliminar Producto</button>
                                </form>

                                <!-- Modal de confirmación -->
                                <div class="modal fade" id="confirmarModal-<?= $uniqueId ?>" tabindex="-1" aria-labelledby="confirmarModalLabel-<?= $uniqueId ?>" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-dark">
                                                <h5 class="modal-title text-light" id="confirmarModalLabel-<?= $uniqueId ?>"><strong>Confirmar Eliminación</strong> </h5>
                                            </div>
                                            <div class="modal-body text-start">
                                                <strong>¿Estás seguro que quieres eliminar este producto del carrito?</strong>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="button" class="btn btn-danger confirmarEliminacionButton" data-target="#eliminadoModal-<?= $uniqueId ?>" data-form="eliminarForm-<?= $uniqueId ?>" data-dismiss="#confirmarModal-<?= $uniqueId ?>">Eliminar Producto</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal de producto eliminado -->
                                <div class="modal fade" id="eliminadoModal-<?= $uniqueId ?>" tabindex="-1" aria-labelledby="eliminadoModalLabel-<?= $uniqueId ?>" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger d-flex justify-content-center">
                                                <h5 class="modal-title text-light" id="eliminadoModalLabel-<?= $uniqueId ?>">Producto Eliminado</h5>
                                            </div>
                                            <div class="modal-body text-center">
                                                <strong>Producto Eliminado del Carrito</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <script>
                                    // Escuchar el clic en todos los botones "Eliminar Producto"
                                    document.querySelectorAll('.eliminarButton').forEach(function(button) {
                                        button.addEventListener('click', function() {
                                            // Eliminar cualquier capa de fondo previa
                                            document.querySelectorAll('.modal-backdrop').forEach(function(backdrop) {
                                                backdrop.remove();
                                            });

                                            var targetModalId = this.getAttribute('data-target');
                                            var modal = new bootstrap.Modal(document.querySelector(targetModalId), {});
                                            modal.show();
                                        });
                                    });

                                    // Escuchar el clic en los botones de confirmación de eliminación
                                    document.querySelectorAll('.confirmarEliminacionButton').forEach(function(button) {
                                        button.addEventListener('click', function() {
                                            // Eliminar cualquier capa de fondo previa
                                            document.querySelectorAll('.modal-backdrop').forEach(function(backdrop) {
                                                backdrop.remove();
                                            });

                                            var targetEliminadoModalId = this.getAttribute('data-target');
                                            var dismissModalId = this.getAttribute('data-dismiss');
                                            var formId = this.getAttribute('data-form');
                                            var form = document.getElementById(formId);

                                            // Cerrar el modal de confirmación
                                            var dismissModal = bootstrap.Modal.getInstance(document.querySelector(dismissModalId));
                                            dismissModal.hide();

                                            // Mostrar el modal de "Producto Eliminado"
                                            var eliminadoModal = new bootstrap.Modal(document.querySelector(targetEliminadoModalId), {});
                                            eliminadoModal.show();

                                            // Cerrar el modal después de 3 segundos y enviar el formulario
                                            setTimeout(function() {
                                                eliminadoModal.hide();
                                                form.submit();
                                            }, 3000);
                                        });
                                    });

                                    // Escuchar el cierre de cualquier modal y eliminar capas de fondo residuales
                                    document.querySelectorAll('.modal').forEach(function(modal) {
                                        modal.addEventListener('hidden.bs.modal', function() {
                                            // Eliminar cualquier capa de fondo residual
                                            document.querySelectorAll('.modal-backdrop').forEach(function(backdrop) {
                                                backdrop.remove();
                                            });

                                            // Restaurar el scroll de la página
                                            document.body.classList.remove('modal-open');
                                            document.body.style.overflow = '';
                                            document.body.style.paddingRight = '';
                                        });
                                    });

                                </script>

                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php

                    // Muestra el total y el botón para proceder a la compra
                    echo '<div class="col-12 d-flex align-items-center justify-content-end my-4">';
                    echo '    <div class="text-end">';
                    if($cantproductos >1){// Muestra el total
                        echo '        <h4 class="text-end">Total ('. $cantproductos . ' productos): $' .  $total . '</h4>'; 
                    }else{
                        echo '        <h4 class="text-end">Total ('. $cantproductos . ' producto): $' .  $total . '</h4>'; 
                    }
                    echo '        <a href="conf_compra.php" class="btn btn-success mt-2 text-end">Comprar</a>'; // Botón para ir a compra.php
                    echo '    </div>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>

    </div>
</body>
</html>