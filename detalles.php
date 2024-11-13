<?php
    include("conexion.php");
    
    // Obtener el ID del juego desde la URL
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    // Consulta para obtener detalles del juego por ID con información de la promoción si existe
    $query = "SELECT pro.nombre, pro.descripcion, fotos, precio, cantidad_almacen, desarrollador, origen, 
                     cat.nombre AS categoria,
                     promo.descuento, promo.fecha_inicial, promo.fecha_final
              FROM productos pro
              JOIN categoria cat ON cat.id = pro.categoria
              LEFT JOIN promociones promo ON promo.producto = pro.id
              WHERE pro.id = $id";
              
    $result = mysqli_query($con, $query);
    $game = mysqli_fetch_assoc($result);

    // Consulta para obtener las plataformas disponibles para el producto
    $query_platforms = "SELECT plat.nombre 
                        FROM plataforma plat
                        JOIN producto_plataforma pp ON plat.id = pp.plataforma
                        WHERE pp.producto = $id";
    $result_platforms = mysqli_query($con, $query_platforms);
    $platforms = [];
    while ($row = mysqli_fetch_assoc($result_platforms)) {
        $platforms[] = $row['nombre'];
    }
    mysqli_free_result($result_platforms);

    // Consulta para obtener las reseñas del producto
    $query_reviews = "SELECT usr.nombre, res.calificacion, res.comentario, res.fecha 
                      FROM resenas res
                      JOIN usuarios usr ON usr.id = res.usuario
                      WHERE res.producto = $id";
    $result_reviews = mysqli_query($con, $query_reviews);
    $reviews = [];
    while ($row = mysqli_fetch_assoc($result_reviews)) {
        $reviews[] = $row;
    }
    mysqli_free_result($result_reviews);

    mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($game['nombre']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .btn-group .btn-check:checked + .btn {
            background-color: #007bff; /* Cambia el color de fondo del botón seleccionado */
            color: white; /* Cambia el color del texto del botón seleccionado */
        }
        .btn-group .btn {
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container my-5">
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
            <h1 class="display-1">D&D Games</h1>
        </div>
        <h1 class="my-4"><?php echo htmlspecialchars($game['nombre']); ?></h1>
        <div class="row">
            <div class="col-md-6">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($game['fotos']); ?>" 
                     alt="<?php echo htmlspecialchars($game['nombre']); ?>" width="400" height="450">
            </div>
            <div class="col-md-6">
                <p><strong>Descripción:</strong> <?php echo htmlspecialchars($game['descripcion']); ?></p>

                <?php if (!is_null($game['descuento'])): 
                    $descuento = $game['descuento'] / 100;
                    $precio_descuento = $game['precio'] * (1 - $descuento);
                ?>
                    <p class="text-danger"><strong>Oferta Disponible</strong></p>
                    <p><strong>Antes:</strong> <span style="text-decoration: line-through; color: gray;">$<?php echo htmlspecialchars($game['precio']); ?></span></p>
                    <p><strong>Ahora:</strong> $<?php echo number_format($precio_descuento, 0); ?></p>
                <?php else: ?>
                    <p><strong>Precio:</strong> $<?php echo htmlspecialchars($game['precio']); ?></p>
                <?php endif; ?>

                <p><strong>Disponibles:</strong> <?php echo htmlspecialchars($game['cantidad_almacen']); ?></p>
                <p><strong>Desarrollador:</strong> <?php echo htmlspecialchars($game['desarrollador']); ?></p>
                <p><strong>Origen:</strong> <?php echo htmlspecialchars($game['origen']); ?></p>
                <p><strong>Categoría:</strong> <?php echo htmlspecialchars($game['categoria']); ?></p>

                <p><strong>Plataformas Disponibles:</strong></p>

                <!-- Formulario para añadir al carrito -->
                <form action="carrito.php" method="post">
                    <div class="btn-group" role="group" aria-label="Plataformas">
                        <?php foreach ($platforms as $index => $platform): ?>
                            <input type="radio" class="btn-check" name="platform" id="platform<?php echo $index; ?>" value="<?php echo htmlspecialchars($platform); ?>" required>
                            <label class="btn btn-outline-secondary" for="platform<?php echo $index; ?>">
                                <?php echo htmlspecialchars($platform); ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Campos ocultos para enviar id y precio -->
                    <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                    <input type="hidden" name="price" value="<?php echo isset($precio_descuento) ? number_format($precio_descuento, 0) : $game['precio']; ?>">
                    <br>
                    <button type="submit" class="btn btn-primary mt-3">Añadir al carrito</button>
                </form>

                <!-- Sección de Reseñas -->
                <h2 class="my-4">Reseñas</h2>
                <?php if (empty($reviews)): ?>
                    <p>Aún no hay reseñas para este producto.</p>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="review mb-3">
                            <p><strong>Usuario:</strong> <?php echo htmlspecialchars($review['nombre']); ?></p>
                            <p><strong>Calificación:</strong> <?php echo htmlspecialchars($review['calificacion']); ?>/10</p>
                            <p><strong>Fecha:</strong> <?php echo htmlspecialchars($review['fecha']); ?></p>
                            <p><strong>Comentario:</strong> <?php echo htmlspecialchars($review['comentario']); ?></p>
                            
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>




