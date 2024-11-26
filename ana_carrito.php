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
    <title>Añadir al carrito</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función para mostrar el mensaje de éxito y redirigir
        function MensajeExito() {
            setTimeout(function() {
                window.location.href = "carrito.php"; 
            }); 
        }
    </script>
</head>
<body>
    <div class="container mt-5">
    
        <?php if (isset($success) && $success): ?>
            <script>
               MensajeExito(); // Llamar a la función para mostrar el mensaje
            </script>
        <?php endif; ?>
    </div>
</body>
</html>
