<?php
include("conexion.php");

$error = "";
$producto_reciente = null;
$plataformas = [];

// Obtener el producto más reciente (el último insertado)
$query_producto_reciente = "SELECT ID, Nombre FROM productos ORDER BY ID DESC LIMIT 1";
$result_producto_reciente = mysqli_query($con, $query_producto_reciente);

if ($result_producto_reciente && mysqli_num_rows($result_producto_reciente) > 0) {
    $producto_reciente = mysqli_fetch_assoc($result_producto_reciente);
}

// Obtener todas las plataformas de la tabla `plataforma`
$query_plataformas = "SELECT ID, Nombre FROM plataforma";
$result_plataformas = mysqli_query($con, $query_plataformas);

if ($result_plataformas) {
    while ($row = mysqli_fetch_assoc($result_plataformas)) {
        $plataformas[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $producto_id = mysqli_real_escape_string($con, $_POST['producto']);
    $plataformas_seleccionadas = $_POST['plataformas']; // Array de plataformas seleccionadas
    $descuento = mysqli_real_escape_string($con, $_POST['descuento']);
    $fecha_inicial = mysqli_real_escape_string($con, $_POST['fecha_inicial']);
    $fecha_final = mysqli_real_escape_string($con, $_POST['fecha_final']);

    // Insertar las plataformas relacionadas con el producto
    foreach ($plataformas_seleccionadas as $plataforma_id) {
        $query_producto_plataforma = "INSERT INTO producto_plataforma (Producto, Plataforma) VALUES ('$producto_id', '$plataforma_id')";
        if (!mysqli_query($con, $query_producto_plataforma)) {
            $error = "Error al asignar plataformas al producto.";
        }
    }

    // Si hay un descuento, agregarlo a la tabla promociones
    if (!empty($descuento)) {
        $query_promocion = "INSERT INTO promociones (Producto, Descuento, Fecha_Inicial, Fecha_Final) 
                            VALUES ('$producto_id', '$descuento', '$fecha_inicial', '$fecha_final')";
        if (!mysqli_query($con, $query_promocion)) {
            $error = "Error al registrar la promoción.";
        }
    }

    // Redirigir o mostrar un mensaje de éxito
    if (empty($error)) {
        echo "<script>
                alert('Producto añadido correctamente');
                window.location.href = 'index.php';
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
    $admin['administrador'] = 0;
}

// Cerrar la conexión
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Producto</title>
    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function validateForm(event) {
            const descuento = document.getElementById("descuento").value;
            const fechaInicial = document.getElementById("fecha_inicial").value;
            const fechaFinal = document.getElementById("fecha_final").value;

            if (descuento) { // Si hay descuento ingresado
                if (!fechaInicial || !fechaFinal) { // Validar que las fechas no estén vacías
                    alert("Si hay descuento, debes ingresar la fecha inicial y final.");
                    event.preventDefault(); // Prevenir el envío del formulario
                    return false;
                }
            }

            return true; // Permitir el envío si todo es válido
        }
    </script>
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
                                <li><a class="dropdown-item active" href="nuevo_producto.php">Nuevo Producto</a></li>
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
        <h2 class="my-3">Asignar Plataformas y Promoción:</h2>

        <!-- Mostrar el mensaje de error si existe -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-center"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="plat_desc.php" method="post" onsubmit="return validateForm(event)">
            <div class="mb-3">
                <label for="producto" class="form-label">Producto:</label>
                <select class="form-control" id="producto" name="producto" required>
                    <?php if ($producto_reciente): ?>
                        <option value="<?php echo $producto_reciente['ID']; ?>"><?php echo $producto_reciente['Nombre']; ?></option>
                    <?php else: ?>
                        <option value="" disabled>No hay productos recientes</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="plataformas" class="form-label">Plataformas:</label>
                <div class="form-check">
                    <?php foreach ($plataformas as $plataforma): ?>
                        <input type="checkbox" class="form-check-input" id="plataforma_<?php echo $plataforma['ID']; ?>" name="plataformas[]" value="<?php echo $plataforma['ID']; ?>">
                        <label class="form-check-label" for="plataforma_<?php echo $plataforma['ID']; ?>"><?php echo $plataforma['Nombre']; ?></label><br>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="mb-3">
                <label for="descuento" class="form-label">Descuento (%):</label>
                <input type="number" class="form-control" id="descuento" name="descuento" min="0" max="100" placeholder="Deje vacío si no hay descuento">
            </div>
            <div class="mb-3">
                <label for="fecha_inicial" class="form-label">Fecha Inicial:</label>
                <input type="date" class="form-control" id="fecha_inicial" name="fecha_inicial">
            </div>
            <div class="mb-3">
                <label for="fecha_final" class="form-label">Fecha Final:</label>
                <input type="date" class="form-control" id="fecha_final" name="fecha_final">
            </div>
            <div class="my-3">
                <button type="submit" class="btn btn-primary w-100">Agregar Producto</button>
            </div>
        </form>
    </div>
</body>
</html>
