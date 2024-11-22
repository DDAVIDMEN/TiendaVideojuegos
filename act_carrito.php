<?php
    include("conexion.php");
    $user_id = $_SESSION['user_id'];
    $producto_id = $_POST['producto_id'];
    $plataforma = $_POST['plataforma'];
    $cantidad = $_POST['cantidad'];


    // Cantidad vieja
    $cantidad_vieja = "SELECT cantidad FROM carrito WHERE usuario = $user_id AND producto = $producto_id AND plataforma = '$plataforma';";
    $result_cant = mysqli_query($con, $cantidad_vieja);
    $cant_old = mysqli_fetch_assoc($result_cant);

    $diferencia = $cantidad - $cant_old['cantidad'];

    // 
    $query_cantidad = "UPDATE carrito
                       SET cantidad = $cantidad 
                       WHERE usuario = $user_id AND producto = $producto_id AND plataforma = '$plataforma';";
    if(mysqli_query($con, $query_cantidad)){
        echo "  
            
                <script>
                    setTimeout(function() {
                        window.location.href = 'carrito.php'; 
                    }); 
                
                </script>";
        }else {
            echo "Error al actualizar el inventario del producto.";
        
    }

    // Verificar la cantidad disponible del producto
    $query_stock = "SELECT cantidad_almacen FROM productos WHERE id = $producto_id";
    $result_stock = mysqli_query($con, $query_stock);
    $producto = mysqli_fetch_assoc($result_stock);

    // Actualizar el inventario del producto
    $nueva_cantidad_almacen = $producto['cantidad_almacen'] - $diferencia;
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
    <title>Producto actualizado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </head>
</html>