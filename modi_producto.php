<?php
include("conexion.php");


$productos = [];
$categorias = [];
$plataformas = [];
$producto_seleccionado = null;
$promocion = null;

// Obtener todos los productos de la tabla `productos`
$query_productos = "SELECT ID, Nombre FROM productos";
$result_productos = mysqli_query($con, $query_productos);

if ($result_productos) {
    while ($row = mysqli_fetch_assoc($result_productos)) {
        $productos[] = $row;
    }
}

// Obtener todas las categorías
$query_categorias = "SELECT ID, Nombre FROM categoria";
$result_categorias = mysqli_query($con, $query_categorias);

if ($result_categorias) {
    while ($row = mysqli_fetch_assoc($result_categorias)) {
        $categorias[] = $row;
    }
}

// Obtener todas las plataformas
$query_plataformas = "SELECT ID, Nombre FROM plataforma";
$result_plataformas = mysqli_query($con, $query_plataformas);

if ($result_plataformas) {
    while ($row = mysqli_fetch_assoc($result_plataformas)) {
        $plataformas[] = $row;
    }
}

// Manejar la selección del producto
if (isset($_POST['producto_seleccionado'])) {
    $producto_id = mysqli_real_escape_string($con, $_POST['producto_seleccionado']);
    
    // Obtener información del producto seleccionado
    $query_producto = "SELECT * FROM productos WHERE ID = '$producto_id'";
    $result_producto = mysqli_query($con, $query_producto);
    if ($result_producto && mysqli_num_rows($result_producto) > 0) {
        $producto_seleccionado = mysqli_fetch_assoc($result_producto);
    }
    
    // Obtener información de promoción (si existe)
    $query_promocion = "SELECT * FROM promociones WHERE Producto = '$producto_id'";
    $result_promocion = mysqli_query($con, $query_promocion);
    if ($result_promocion && mysqli_num_rows($result_promocion) > 0) {
        $promocion = mysqli_fetch_assoc($result_promocion);
    }
}


//Admin 
if (isset($_SESSION['user_id'])){
    $admin_id = $_SESSION['user_id'];
    $queryadmin = "SELECT administrador from usuarios where id = $admin_id";
    $resultadmin = mysqli_query($con, $queryadmin);
    $admin = mysqli_fetch_assoc($resultadmin);
}else{
    $admin['administrador'] = 0;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Producto</title>
    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Latest compiled JavaScript -->
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
                            <a class="nav-link dropdown-toggle active" href="#" role="button"
                                data-bs-toggle="dropdown">Administrador</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="inventario.php">Inventario</a></li>
                                <li><a class="dropdown-item" href="nuevo_producto.php">Nuevo Producto</a></li>
                                <li><a class="dropdown-item active" href="modi_producto.php">Modificar Producto</a></li>
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
        <h2 class="my-3">Modificar Producto</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Formulario para seleccionar el producto -->
        <form method="post">
            <div class="mb-3">
                <label for="producto_seleccionado" class="form-label">Seleccionar Producto:</label>
                <select class="form-control" id="producto_seleccionado" name="producto_seleccionado" onchange="this.form.submit()">
                    <option value="" disabled selected>Seleccione un producto</option>
                    <?php foreach ($productos as $producto): ?>
                        <option value="<?php echo $producto['ID']; ?>" <?php echo (isset($_POST['producto_seleccionado']) && $_POST['producto_seleccionado'] == $producto['ID']) ? 'selected' : ''; ?>>
                            <?php echo $producto['Nombre']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <?php if ($producto_seleccionado): ?>
            <!-- Formulario para modificar el producto -->
            <form method="post" id="actualizarForm" action="update_producto.php" onsubmit="return ValidarFechas(event)">
                <input type="hidden" name="producto" value="<?php echo $producto_seleccionado['ID']; ?>">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $producto_seleccionado['Nombre']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción:</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?php echo $producto_seleccionado['Descripcion']; ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="precio" class="form-label">Precio:</label>
                    <input type="number" class="form-control w-25" id="precio" name="precio" value="<?php echo $producto_seleccionado['Precio']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="cantidad_almacen" class="form-label">Cantidad en Almacén:</label>
                    <input type="number" class="form-control w-25" id="cantidad_almacen" name="cantidad_almacen" value="<?php echo $producto_seleccionado['Cantidad_almacen']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="desarrollador" class="form-label">Desarrollador:</label>
                    <input type="text" class="form-control w-50" id="desarrollador" name="desarrollador" value="<?php echo $producto_seleccionado['Desarrollador']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="origen" class="form-label">Origen:</label>
                    <input type="text" class="form-control w-50" id="origen" name="origen" value="<?php echo $producto_seleccionado['Origen']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="categoria" class="form-label">Categoría:</label>
                    <select class="form-control w-25" id="categoria" name="categoria" required>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?php echo $categoria['ID']; ?>" <?php echo ($producto_seleccionado['Categoria'] == $categoria['ID']) ? 'selected' : ''; ?>>
                                <?php echo $categoria['Nombre']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="plataformas" class="form-label">Plataformas:</label>
                    <div class="form-check">
                        <?php foreach ($plataformas as $plataforma): ?>
                            <input type="checkbox" class="form-check-input" id="plataforma_<?php echo $plataforma['ID']; ?>" name="plataformas[]" value="<?php echo $plataforma['ID']; ?>"
                                <?php
                                $query_plataforma_producto = "SELECT * FROM producto_plataforma WHERE Producto = '{$producto_seleccionado['ID']}' AND Plataforma = '{$plataforma['ID']}'";
                                $result_plataforma_producto = mysqli_query($con, $query_plataforma_producto);
                                echo (mysqli_num_rows($result_plataforma_producto) > 0) ? 'checked' : '';
                                ?>>
                            <label class="form-check-label" for="plataforma_<?php echo $plataforma['ID']; ?>"><?php echo $plataforma['Nombre']; ?></label><br>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="descuento" class="form-label">Descuento (%):</label>
                    <input type="number" class="form-control" id="descuento" name="descuento" value="<?php echo $promocion['Descuento'] ?? ''; ?>" placeholder="Deje vacío si no hay descuento" max="100">
                </div>
                <div id="error-message" class="alert alert-danger mb-3" style="display: none;"></div>
                <div class="mb-3">
                    <label for="fecha_inicial" class="form-label">Fecha Inicial:</label>
                    <input type="date" class="form-control w-25" id="fecha_inicial" name="fecha_inicial" value="<?php echo $promocion['Fecha_Inicial'] ?? ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="fecha_final" class="form-label">Fecha Final:</label>
                    <input type="date" class="form-control w-25" id="fecha_final" name="fecha_final" value="<?php echo $promocion['Fecha_Final'] ?? ''; ?>">
                </div>
                <div class="my-5">
                    <button type="submit" class="btn btn-primary w-100" id="actualizarButton" name="actualizar_producto">Actualizar Producto</button>
                </div>
            </form>
           <!-- Modal de confirmación -->
        <div class="modal fade" id="actualizarModal" tabindex="-1" aria-labelledby="actualizarModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header bg-primary d-flex justify-content-center">
                <h5 class="modal-title" id="actualizarModalLabel"><strong class="text-light">Actualización Exitosa</strong></h5>
            </div>
            <div class="modal-body text-center">
                <strong> Producto Actualizado.</strong>
            </div>
            </div>
        </div>
        </div>

        <script>
            document.getElementById('actualizarButton').addEventListener('click', function (event) {
                event.preventDefault();
                
                var form = document.getElementById('actualizarForm');
                if (form.checkValidity() && ValidarFechas(event)) {
                    // Mostrar el modal si la validación pasa
                    var modal = new bootstrap.Modal(document.getElementById('actualizarModal'), {});
                    modal.show();

                    // Esperar y luego enviar el formulario
                    setTimeout(function () {
                        form.submit();
                    }, 2000);
                } else {
                    // Mostrar errores de validación
                    form.reportValidity();
                }
            });

            function ValidarFechas(event) {
                const errorMessage = document.getElementById("error-message");
                const descuento = document.getElementById("descuento").value;
                const fechaInicial = document.getElementById("fecha_inicial").value;
                const fechaFinal = document.getElementById("fecha_final").value;

                // Limpiar mensaje de error previo
                errorMessage.style.display = "none";
                errorMessage.innerText = "";

                if (descuento && (!fechaInicial || !fechaFinal)) {
                    // Mostrar mensaje de error
                    errorMessage.innerText = "Si hay descuento, debes ingresar la fecha inicial y final.";
                    errorMessage.style.display = "block"; // Hacerlo visible
                    return false;
                }
                return true;
            }
        </script>

        <?php endif; ?>
    </div>
</body>
</html>

<?php
// Cerrar la conexión
mysqli_close($con);
?>
