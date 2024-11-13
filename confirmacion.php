<?php
    include("conexion.php");

    $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
    $correo = mysqli_real_escape_string($con, $_POST['correo']);
    $edad = mysqli_real_escape_string($con, $_POST['edad']);

    $query = "INSERT INTO usuarios (nombre,correo,edad) VALUES ('$nombre', '$correo', $edad);";
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
        <?php
            if (!mysqli_query($con,$query)) {
                die('Error: ' . mysqli_error($con));
            }
            echo "<div class= 'alert alert-success alert-dismissible'>
            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            <strong>Success!</strong> This alert box could indicate a successful or positive action.
            </div>";
            mysqli_close($con);
        ?>
        <a href="index.html" class="btn btn-primary">Regresar al inicio</a>
    </div>

</body>
</html>