<?php
    include("conexion.php");

    //Query
    $query = "select p.nombre,p.precio, descuento,fecha_inicial,fecha_final from promociones s, productos
        p where s.producto = p.id;";
    if (mysqli_connect_errno()) {
        echo " <div class='alert alert-danger'>
            <strong>Error!</strong>" . mysqli_connect_error() ."
            </div>" ;
      }
  
      $result = mysqli_query($con,$query);
      mysqli_close($con);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D&D Games</title>
    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <!--Contenedor principal de BS5-->
    <div class="container">
        <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
            <div class="container text-center">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="navbar-brand" href="index.php">
                            <img src="logo.png" alt="Game Logo" style="width: 40px;" class="rounded-pill">
                        </a>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown">Categorías</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="accion.php">Acción</a></li>
                            <li><a class="dropdown-item" href="deportes.php">Deportes</a></li>
                            <li><a class="dropdown-item" href="estrategia.php">Estrategia</a></li>
                            <li><a class="dropdown-item" href="role.php">Role-Play</a></li>
                            <li><a class="dropdown-item" href="carreras.php">Carreras</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="oferta.php">Ofertas</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown">Exclusivos</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="play.php">PlayStation</a></li>
                            <li><a class="dropdown-item" href="xbox.php">Xbox</a></li>
                            <li><a class="dropdown-item" href="switch.php">Switch</a></li>
                            <li><a class="dropdown-item" href="pc.php">PC</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.html">Acerca de</a>
                    </li>
                </ul>
                <form class="d-flex">
                    <input class="form-control me-2" type="text" placeholder="Buscar">
                    <button class="btn btn-primary" type="button">Buscar</button>
                  </form>
            </div>
        </nav>
        <div class="mt-4 p-5 bg-primary text-white rounded text-center">
            <h1 class="display-1 ">D&D Games</h1>
        </div>
        <br>
        <h2 class= "my-2"> Ofertas</h2>
        <div class="container">
        <table class="table table-striped" >
            <thead>
                <tr>
                    <th>Juego</th>
                    <th>Precio Anterior</th>
                    <th>Precio Nuevo</th>
                    <th>Desde</th>
                    <th>Hasta</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while ($row = mysqli_fetch_array($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['nombre'] . "</td>";
                        echo "<td>" . $row['precio'] . "</td>";
                        echo "<td>" . $row['descuento'] . "</td>";
                        echo "<td>" . $row['fecha_inicial'] . "</td>";
                        echo "<td>" . $row['fecha_final'] . "</td>";
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Selecciona todas las filas de la tabla (excluyendo el encabezado)
            let filas = document.querySelectorAll("table.table tbody tr");

            // Itera sobre cada fila
            filas.forEach(fila => {
                // Obtiene el precio anterior y el descuento en porcentaje
                let precioCelda = fila.cells[1];
                let descuentoCelda = fila.cells[2];

                // Convierte los valores a números
                let precio = parseFloat(precioCelda.textContent);
                let descuento = parseFloat(descuentoCelda.textContent);

                // Convierte el descuento en porcentaje (por ejemplo, 30 -> 0.3)
                let descuentoPorcentaje = descuento / 100;

                // Calcula el nuevo precio aplicando el descuento
                let precioConDescuento = precio * (1 - descuentoPorcentaje);

                // Redondea el resultado a dos decimales si es necesario
                precioConDescuento = precioConDescuento.toFixed(0);

                // Actualiza la celda de descuento con el nuevo precio
                descuentoCelda.textContent = precioConDescuento;
            });
        });
    </script>
</body>
</html>