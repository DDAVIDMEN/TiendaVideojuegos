<?php
    include("conexion.php");
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
            echo "<script>
                alert('Producto eliminado del carrito correctamente');
                window.location.href = 'carrito.php';
            </script>";
        }else {
            echo "Error al actualizar el inventario del producto.";
        }  
    } else {
        echo "Error al eliminar el producto";
    }

    mysqli_close($con);
?>
