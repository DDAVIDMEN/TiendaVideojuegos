<?php
include("conexion.php");

$error = "";


    $producto_id = mysqli_real_escape_string($con, $_POST['producto']);
    $plataformas_seleccionadas = $_POST['plataformas']; // Array de plataformas seleccionadas
    $descuento = mysqli_real_escape_string($con, $_POST['descuento']);
    $fecha_inicial = mysqli_real_escape_string($con, $_POST['fecha_inicial']);
    $fecha_final = mysqli_real_escape_string($con, $_POST['fecha_final']);

    // Insertar las plataformas relacionadas con el producto
    foreach ($plataformas_seleccionadas as $plataforma_id) {
        $query_producto_plataforma = "INSERT INTO producto_plataforma (Producto, Plataforma) VALUES ('$producto_id', '$plataforma_id')";
        if (!mysqli_query($con, $query_producto_plataforma)) {
            $error = "Error al asignar plataformas al producto.";
        }
    }

    // Si hay un descuento, agregarlo a la tabla promociones
    if (!empty($descuento)) {
        $query_promocion = "INSERT INTO promociones (Producto, Descuento, Fecha_Inicial, Fecha_Final) 
                            VALUES ('$producto_id', '$descuento', '$fecha_inicial', '$fecha_final')";
        if (!mysqli_query($con, $query_promocion)) {
            $error = "Error al registrar la promoción.";
        }
    }

    // Redirigir o mostrar un mensaje de éxito
    if (empty($error)) {
        echo "<script>
                window.location.href = 'index.php';
            </script>";
    }

?>