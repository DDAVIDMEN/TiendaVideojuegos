<?php
    include("conexion.php");

    $producto_id = $_POST['producto_id'];
    $precio = $_POST['precio'];
    $plataforma = $_POST['plataforma'];
    $user_id = $_SESSION['user_id'];

    // Aquí puedes escribir el código para agregar el producto al carrito en tu base de datos o sesión
    $insert_query = "INSERT INTO carrito (usuario, producto) 
                         VALUES ($user_id, $producto_id);";

        if (mysqli_query($con, $insert_query)) {
            echo"<h1>Se logro</h1>";
        }else {
            // Mostrar un error si ocurre un problema con la inserción
            echo "<h1>Error al registrar el usuario. Inténtalo nuevamente.<h1>";
        }
     
   
?>
