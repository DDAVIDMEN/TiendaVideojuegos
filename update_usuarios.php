<?php
include("conexion.php");

$error = "";

// Manejar la actualización del usuario

    $usuario_id = mysqli_real_escape_string($con, $_POST['usuario']);
    $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
    $correo = mysqli_real_escape_string($con, $_POST['correo']);
    $contrasena = mysqli_real_escape_string($con, $_POST['contra']);
    $nacimiento = mysqli_real_escape_string($con, $_POST['nacimiento']);
    $tarjeta = mysqli_real_escape_string($con, $_POST['tarjeta']);
    $direccion = mysqli_real_escape_string($con, $_POST['direccion']);
    $codigo_postal = mysqli_real_escape_string($con, $_POST['codigo_postal']);
    $administrador = isset($_POST['administrador']) ? 1 : 0;

    // Actualizar información del usuario
    $query_update_usuario = "UPDATE usuarios SET 
                                Nombre = '$nombre', 
                                Correo = '$correo', 
                                Contrasena = '$contrasena', 
                                Nacimiento = '$nacimiento', 
                                Tarjeta = '$tarjeta', 
                                Direccion = '$direccion', 
                                Codigo_Postal = '$codigo_postal', 
                                Administrador = '$administrador'
                              WHERE ID = '$usuario_id'";
    if (!mysqli_query($con, $query_update_usuario)) {
        $error = "Error al actualizar el usuario.";
    }
    if (empty($error)) {
        echo "<script>
                window.location.href = 'usuarios.php';
            </script>";
    }else{
        echo $error;
    }


// Cerrar la conexión
mysqli_close($con);
?>