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
    header("Location: index.php");
    exit();
}

$error = "";
$nombre = $descripcion = $fotos = $precio = $cantidad_almacen = $desarrollador = $origen = $categoria = ""; // Inicializar variables


// Obtener las categorías de la base de datos
$categorias = [];
$query = "SELECT ID, Nombre FROM categoria";
$result = mysqli_query($con, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $categorias[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escapar y almacenar los datos del formulario
    $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($con, $_POST['descripcion']);
    $fotos = mysqli_real_escape_string($con, $_FILES['fotos']['tmp_name']);
    $precio = mysqli_real_escape_string($con, $_POST['precio']);
    $cantidad_almacen = mysqli_real_escape_string($con, $_POST['cantidad_almacen']);
    $desarrollador = mysqli_real_escape_string($con, $_POST['desarrollador']);
    $origen = mysqli_real_escape_string($con, $_POST['origen']);
    $categoria = mysqli_real_escape_string($con, $_POST['categoria']);

    // Convertir la imagen a formato binario
    $foto_data = file_get_contents($fotos);

    // Insertar los datos en la tabla de productos
    $insert_query = "INSERT INTO productos (Nombre, Descripcion, Fotos, Precio, Cantidad_almacen, Desarrollador, Origen, Categoria) 
                     VALUES ('$nombre', '$descripcion', ?, '$precio', '$cantidad_almacen', '$desarrollador', '$origen', '$categoria')";

    $stmt = mysqli_prepare($con, $insert_query);
    mysqli_stmt_bind_param($stmt, "s", $foto_data);

    if (mysqli_stmt_execute($stmt)) {
        // Redirigir si la inserción fue exitosa
        header("Location: plat_desc.php");
        exit();
    } else {
        // Mostrar un error si ocurre un problema con la inserción
        $error = "Error al registrar el producto. Inténtalo nuevamente.";
    }

    // Cerrar la declaración
    mysqli_stmt_close($stmt);
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
                            <li><a class="dropdown-item active" href="nuevo_producto.php">Nuevo Producto</a></li>
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
        <?php if($admin['administrador'] ==1): ?>
        <h2 class="my-3">Agregar Nuevo Producto</h2>

        <!-- Mostrar el mensaje de error si existe -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-center"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="nuevo_producto.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control w-50" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción:</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="fotos" class="form-label">Foto:</label>
                <input type="file" class="form-control w-50" id="fotos" name="fotos" required>
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label">Precio:</label>
                <input type="number" class="form-control w-25" id="precio" name="precio" min="1" required>
            </div>
            <div class="mb-3">
                <label for="cantidad_almacen" class="form-label">Cantidad en Almacén:</label>
                <input type="number" class="form-control w-25" id="cantidad_almacen" name="cantidad_almacen" min="1" required>
            </div>
            <div class="mb-3">
                <label for="desarrollador" class="form-label">Desarrollador:</label>
                <input type="text" class="form-control w-50" id="desarrollador" name="desarrollador" required>
            </div>
            <div class="mb-3">
                <label for="origen" class="form-label">Origen:</label>
                <input type="text" class="form-control w-25" id="origen" name="origen" required>
            </div>
            <div class="mb-3">
                <label for="categoria" class="form-label">Categoría:</label>
                <select class="form-control w-25" id="categoria" name="categoria" required>
                    <option value="" disabled selected>Seleccione una categoría</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?php echo $cat['ID']; ?>"><?php echo $cat['Nombre']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="my-3">
                <button type="submit" class="btn btn-primary w-100">Continuar</button>
            </div>
        </form>
        <?php else: ?>
            <div class="alert alert-danger text-center">
                <strong class="display-5">No eres administrador</strong><br><br><br>
                <a href="index.php" class="alert-link text-center">Volver al catálogo</a>.
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
