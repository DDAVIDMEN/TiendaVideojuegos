<?php
include("conexion.php");


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



//Admin 
if (isset($_SESSION['user_id'])){
    $admin_id = $_SESSION['user_id'];
    $queryadmin = "SELECT administrador from usuarios where id = $admin_id";
    $resultadmin = mysqli_query($con, $queryadmin);
    $admin = mysqli_fetch_assoc($resultadmin);
}else{
    $admin['administrador'] = 0;
    header("Location: index.php");
    exit();
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
                        <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown">Administrador</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="inventario.php">Inventario</a></li>
                            <li><a class="dropdown-item" href="nuevo_producto.php">Nuevo Producto</a></li>
                            <li><a class="dropdown-item active" href="modi_producto.php">Modificar Producto</a></li>
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
        <?php if($admin['administrador'] ==1): ?>
        <h2 class="my-3">Asignar Plataformas y Promoción:</h2>

        <!-- Mostrar el mensaje de error si existe -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-center"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="new_product.php" id="nuevouserForm" method="post" onsubmit="return validateForm(event)">
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

            <div id="error-message" class="alert alert-danger mb-3" style="display: none;"></div>

            <div class="mb-3">
                <label for="fecha_inicial" class="form-label">Fecha Inicial:</label>
                <input type="date" class="form-control" id="fecha_inicial" name="fecha_inicial">
            </div>
            <div class="mb-3">
                <label for="fecha_final" class="form-label">Fecha Final:</label>
                <input type="date" class="form-control" id="fecha_final" name="fecha_final">
            </div>
            <div class="my-3">
                <button type="submit" class="btn btn-primary w-100" id="nuevouserButton">Agregar Producto</button>
            </div>
        </form>
        <!-- Modal de confirmación -->
        <div class="modal fade" id="nuevouserModal" tabindex="-1" aria-labelledby="nuevouserModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header bg-success d-flex justify-content-center">
                <h5 class="modal-title" id="nuevouserModalLabel"><strong class="text-light">Añadido Exitoso</strong></h5>
            </div>
            <div class="modal-body text-center">
                <strong> Producto Añadido Correctamente</strong>
            </div>
            </div>
        </div>
        </div>

        <script>
            document.getElementById('nuevouserButton').addEventListener('click', function (event) {
                event.preventDefault();
                
                var form = document.getElementById('nuevouserForm');
                if (form.checkValidity() && ValidarFechas(event)) {
                    // Mostrar el modal si la validación pasa
                    var modal = new bootstrap.Modal(document.getElementById('nuevouserModal'), {});
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
        <?php else: ?>
            <div class="alert alert-danger text-center">
                <strong class="display-5">No eres administrador</strong><br><br><br>
                <a href="index.php" class="alert-link text-center">Volver al catálogo</a>.
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

