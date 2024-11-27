<?php
    include("conexion.php");

    //Admin 
if (isset($_SESSION['user_id'])){
    $admin_id = $_SESSION['user_id'];
    $queryadmin = "SELECT administrador from usuarios where id = $admin_id";
    $resultadmin = mysqli_query($con, $queryadmin);
    $admin = mysqli_fetch_assoc($resultadmin);
}else{
    $admin['administrador']=0;
    header("Location: index.php");
    exit();
}

    $user_id = $_SESSION['user_id'];
    $producto_id = $_POST['producto_id'];
    $plataforma = $_POST['plataforma'];

    // Obtener la cantidad del producto en el carrito antes de eliminarlo
    $query_cantidad = "SELECT cantidad FROM carrito 
                       WHERE usuario = $user_id AND producto = $producto_id AND plataforma = '$plataforma'";
    $resultado_cantidad = mysqli_query($con, $query_cantidad);

    $row = mysqli_fetch_assoc($resultado_cantidad);
    $cantidad = $row['cantidad'];

    // Eliminar el producto del carrito
    $query = "DELETE FROM carrito 
              WHERE usuario = $user_id AND producto = $producto_id AND plataforma = '$plataforma';";

    if (mysqli_query($con, $query)) {
        // Sumar la cantidad eliminada al inventario del producto
        $query_actualizar = "UPDATE productos 
                                 SET cantidad_almacen = cantidad_almacen + $cantidad 
                                 WHERE id = $producto_id";
        if (mysqli_query($con, $query_actualizar)) {
            echo "  
                <script>
                    setTimeout(function() {
                        window.location.href = 'carrito.php'; 
                    }); 
                
                </script>";
        }else {
            echo "Error al actualizar el inventario del producto.";
        }  
    } else {
        echo "Error al eliminar el producto";
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
