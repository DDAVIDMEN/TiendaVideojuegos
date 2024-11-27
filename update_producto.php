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

$error = "";

    $producto_id = mysqli_real_escape_string($con, $_POST['producto']);
    $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($con, $_POST['descripcion']);
    $precio = mysqli_real_escape_string($con, $_POST['precio']);
    $cantidad_almacen = mysqli_real_escape_string($con, $_POST['cantidad_almacen']);
    $desarrollador = mysqli_real_escape_string($con, $_POST['desarrollador']);
    $origen = mysqli_real_escape_string($con, $_POST['origen']);
    $categoria = mysqli_real_escape_string($con, $_POST['categoria']);
    $plataformas_seleccionadas = $_POST['plataformas'];
    $descuento = mysqli_real_escape_string($con, $_POST['descuento']);
    $fecha_inicial = mysqli_real_escape_string($con, $_POST['fecha_inicial']);
    $fecha_final = mysqli_real_escape_string($con, $_POST['fecha_final']);


    if (isset($_FILES['fotos']) && $_FILES['fotos']['error'] === UPLOAD_ERR_OK && !empty($_FILES['fotos']['tmp_name'])) {
        $foto_data = file_get_contents($_FILES['fotos']['tmp_name']);
    } else {
        $query = "select fotos from productos where id = $producto_id;";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($result);
        $foto_data = $row['fotos']; // No se seleccionó ninguna imagen
    }
    
    // Actualizar información del producto
    $query_update_producto = "UPDATE productos SET 
                                Nombre = ?, 
                                Descripcion = ?, 
                                Fotos = ?,
                                Precio = ?, 
                                Cantidad_almacen = ?, 
                                Desarrollador = ?, 
                                Origen = ?, 
                                Categoria = ? 
                              WHERE ID = ?";

    $stmt = mysqli_prepare($con, $query_update_producto);

    // Asume que $precio y $cantidad_almacen son números. Usa "s" para cadenas y "i" para enteros o decimales según corresponda.
    mysqli_stmt_bind_param($stmt, "sssiissii", 
        $nombre, 
        $descripcion, 
        $foto_data, 
        $precio, 
        $cantidad_almacen, 
        $desarrollador, 
        $origen, 
        $categoria, 
        $producto_id
    );

    // Ejecuta la consulta
    if (mysqli_stmt_execute($stmt)) {
    } else {
        echo "Error al actualizar el producto: " . mysqli_error($con);
    }

    
    // Actualizar plataformas asociadas al producto
    $query_delete_plataformas = "DELETE FROM producto_plataforma WHERE Producto = '$producto_id'";
    mysqli_query($con, $query_delete_plataformas);
    
    foreach ($plataformas_seleccionadas as $plataforma_id) {
        $query_insert_plataforma = "INSERT INTO producto_plataforma (Producto, Plataforma) VALUES ('$producto_id', '$plataforma_id')";
        if (!mysqli_query($con, $query_insert_plataforma)) {
            $error = "Error al actualizar plataformas.";
        }
    }
    
    // Actualizar o insertar promoción
    // Manejo de promoción
    if (!empty($descuento)) {
        // Verificar si ya existe una promoción para este producto
        $query_promocion_existente = "SELECT * FROM promociones WHERE Producto = '$producto_id'";
        $result_promocion_existente = mysqli_query($con, $query_promocion_existente);

        if (mysqli_num_rows($result_promocion_existente) > 0) {
            // Si la promoción ya existe, actualizarla
            $query_update_promocion = "UPDATE promociones SET 
                                        Descuento = '$descuento', 
                                        Fecha_Inicial = '$fecha_inicial', 
                                        Fecha_Final = '$fecha_final' 
                                    WHERE Producto = '$producto_id'";
            if (!mysqli_query($con, $query_update_promocion)) {
                $error = "Error al actualizar la promoción.";
            }
        } else {
            // Si no existe, insertar una nueva promoción
            $query_insert_promocion = "INSERT INTO promociones (Producto, Descuento, Fecha_Inicial, Fecha_Final) 
                                    VALUES ('$producto_id', '$descuento', '$fecha_inicial', '$fecha_final')";
            if (!mysqli_query($con, $query_insert_promocion)) {
                $error = "Error al registrar la promoción.";
            }
        }
    } else {
        // Si no hay descuento, eliminar cualquier promoción existente para este producto
        $query_delete_promocion = "DELETE FROM promociones WHERE Producto = '$producto_id'";
        if (!mysqli_query($con, $query_delete_promocion)) {
            $error = "Error al eliminar la promoción.";
        }
    }


    if (empty($error)) {
        echo "<script>
                window.location.href = 'index.php';
            </script>";
    }

?>