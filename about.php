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
    }


    mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nosotros</title>
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
                    
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="collapsibleNavbar">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link">Catálogo</a>
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
                            <a class="nav-link active" href="about.php">Acerca de</a>
                        </li>
                        <!--Administracion -->
                        <?php if($admin['administrador'] ==1): ?>
                            <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown">Administrador</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="inventario.php">Inventario</a></li>
                                <li><a class="dropdown-item" href="nuevo_producto.php">Nuevo Producto</a></li>
                                <li><a class="dropdown-item" href="modi_producto.php">Modificar Producto</a></li>
                                <li><a class="dropdown-item" href="usuarios.php">Usuarios</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                    </div>
                </ul>
                <form class="d-flex" action="buscar.php" method="GET">
                    <input class="form-control me-2" type="text" name="nombre" placeholder="Buscar">
                    <button class="btn btn-primary" type="submit">Buscar</button>
                </form>

                <!-- Mostrar enlaces dependiendo del estado de sesión -->
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="registro.php" class="nav-link">Crear cuenta</a>
                        </li>
                        <li class="nav-item">
                            <a href="login.php" class="nav-link">Iniciar sesión</a>
                        </li>
                    </ul>
                <?php else: ?>

                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="navbar-brand" href="carrito.php">
                                <img src="carrito.png" alt="Game Logo" style="width: 40px;" class="rounded-pill">
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown">Mi cuenta</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="cuenta.php">Detalles de Mi cuenta</a></li>
                                <li><a class="dropdown-item" href="historial.php">Historial de Pedidos</a></li>
                                <li><a class="dropdown-item" href="cerrar_sesion.php">Cerrar Sesión</a></li>
                            </ul>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
        </nav>
        <div class="mt-4 p-5 bg-primary text-white rounded text-center">
            <h1 class="display-1 ">D&D Games</h1>
        </div>
        <br>
        <h2 class="my-2">Acerca de nosotros</h2>
        <img src="Logo.png" class="mx-auto d-block" height="250">
        <p class="mt-2">¡Bienvenido a D&D Games, el paraíso para los verdaderos gamers! Nuestra tienda online fue fundada con la visión de ofrecer 
            una experiencia única y emocionante para todos los amantes de los videojuegos. Inspirada en mi pasión por los videojuegos y 
            mi nombre, David David, D&D Games se especializa en ofrecer lo mejor del mundo del gaming para PlayStation, Xbox, PC y 
            Nintendo Switch.</p>

        <h2>¿Quiénes somos?</h2>
        <p>En D&D Games creemos que los videojuegos no son solo entretenimiento; son historias, aventuras y momentos inolvidables. Nos esforzamos 
            por seleccionar un catálogo diverso de más de 50 títulos que abarcan desde los clásicos más queridos hasta los lanzamientos más esperados. 
            Además, garantizamos una experiencia de compra fácil, rápida y segura para que pases menos tiempo comprando y más tiempo jugando.</p>
        
        <h2>¿Qué ofrecemos?</h2>
        <ul>
            <li><strong>Variedad de plataformas:</strong> Juegos para PlayStation, Xbox, PC y Nintendo Switch.</li>
            <li><strong>Experiencia personalizada:</strong> Encuentra toda la información detallada de cada juego, incluyendo descripción, precio, disponibilidad y desarrollador.</li>
            <li><strong>Funcionalidades modernas:</strong> Navegación intuitiva, carrito de compras, historial de compras y mucho más.</li>
            <li><strong>Diseño responsivo:</strong> Compra desde cualquier dispositivo, en cualquier momento.</li>
        </ul>

        <h2>Nuestro compromiso</h2>
        <p>La satisfacción de nuestros clientes es nuestra prioridad. En D&D Games trabajamos para ofrecer precios competitivos, 
            productos de calidad y un servicio de atención al cliente excepcional. Porque entendemos lo importante que es para ti encontrar el juego perfecto, 
            en el momento perfecto.</p>
        
        <h2>Información de contacto</h2>
        <p>¿Tienes alguna pregunta o necesitas ayuda? ¡Estamos aquí para ti!</p>
        <ul>
            <li>📧 Correo Electrónico: <a href="mailto:ddavidmen@gmail.com">ddavidmen@gmail.com</a></li>
            <li>📞 Teléfono: +52 5636230001</li>
            <li>📍Ubicación: Ciudad de México, México (100% tienda en línea)</li>
            <li>📷 Siguenos en: <a href="https://www.instagram.com/ddavidmen/" target="_blank">ddavidmen</a></li>
        </ul>

        <h2>¡Forma parte de nuestra comunidad!</h2>
        <p>Únete a miles de gamers que ya confían en nosotros. En D&D Games, más que una tienda, somos tu aliado en esta increíble aventura que es el mundo de los videojuegos.</p>
        
        <p class="mb-4">¿Listo para empezar? 🎮 ¡Explora, juega y disfruta con D&D Games!</p>
    </div>
</body>
</html>