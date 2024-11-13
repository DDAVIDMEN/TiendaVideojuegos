<?php
    $con=mysqli_connect("localhost","root","","tiendavideojuegos");

    // Check connection
    if (mysqli_connect_errno()) {
      echo "<p>No se pudo realiza la conexión" . mysqli_connect_error(). "</p>";
    }
?>