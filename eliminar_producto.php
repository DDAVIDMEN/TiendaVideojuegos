<?php
    include("conexion.php");
    $user_id = $_SESSION['user_id'];
    $producto_id = $_POST['producto_id'];
    $plataforma = $_POST['plataforma'];

    // Eliminar el producto del carrito
    $query = "DELETE FROM carrito 
              WHERE usuario = $user_id AND producto = $producto_id AND plataforma = '$plataforma';";

    if (mysqli_query($con, $query)) {
        echo "<script>
                alert('Producto eliminado del carrito correctamente');
                window.location.href = 'carrito.php';
            </script>";

    } else {
        echo "Error al eliminar el producto";
    }

    mysqli_close($con);
?>
