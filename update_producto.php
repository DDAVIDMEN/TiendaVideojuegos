<?php
include("conexion.php");

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
    
    // Actualizar información del producto
    $query_update_producto = "UPDATE productos SET 
                                Nombre = '$nombre', 
                                Descripcion = '$descripcion', 
                                Precio = '$precio', 
                                Cantidad_almacen = '$cantidad_almacen', 
                                Desarrollador = '$desarrollador', 
                                Origen = '$origen', 
                                Categoria = '$categoria' 
                              WHERE ID = '$producto_id'";
    if (!mysqli_query($con, $query_update_producto)) {
        $error = "Error al actualizar el producto.";
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