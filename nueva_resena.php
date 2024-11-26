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
    $calificacion = $_POST['calificacion'];
    $comentario = $_POST['comentario'];
    $fecha = date('Y-m-d');

    // Insertar la reseña en la base de datos
    $insert_query = "INSERT INTO resenas (usuario, producto, calificacion, comentario, fecha) 
    VALUES ($user_id, $producto_id, $calificacion, '$comentario', '$fecha')";
    if (mysqli_query($con, $insert_query)) {
        echo "<script>
            window.location.href = 'detalles.php?id=$producto_id';
        </script>";
    } else {
        $error = "Error al guardar la reseña. Por favor, intenta nuevamente.";
    }




    mysqli_close($con);
?>