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

    // Obtener la fecha y hora actual del servidor
    $fecha_actual = date("Y-m-d H:i:s"); // Formato compatible con TIMESTAMP


        $insert_query = "INSERT INTO historial (usuario, producto,fecha, precio, plataforma, cantidad)
        SELECT usuario, producto, '$fecha_actual', precio, plataforma, cantidad
        FROM carrito
        WHERE usuario = $user_id";

        if (mysqli_query($con, $insert_query)) {
            
            $delete_query = "DELETE FROM carrito WHERE usuario = $user_id";
            if (mysqli_query($con, $delete_query)) {
                echo "<script>
                        window.location.href = 'historial.php';
                    </script>";
            } else {
                // Si no se pudieron eliminar los productos del carrito
                echo "<script>
                    alert('Hubo un problema al vaciar el carrito. Por favor, inténtalo nuevamente.');
                    window.location.href = 'carrito.php';
                </script>";
            }
        } else {
            echo "<h1>Error al registrar el producto en el carrito. Inténtalo nuevamente.<h1>";
        }


    

    mysqli_close($con);
?>
