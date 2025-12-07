<?php

function conectarBD() {
    $db_host = getenv('DB_HOST') ?: 'localhost';
    $db_user = getenv('DB_USER') ?: 'root';
    $db_pass = getenv('DB_PASS') ?: '';
    $db_name = getenv('DB_NAME') ?: 'periodicos';
    $db_port = getenv('DB_PORT') ?: 3306;

    $conexion = mysqli_connect($db_host, $db_user, $db_pass, $db_name, $db_port);

    if (!$conexion) {
        // Esto mostrará el error exacto si falla
        die("Error de conexión (" . mysqli_connect_errno() . "): " . mysqli_connect_error());
    }

    mysqli_set_charset($conexion, "utf8");
    return $conexion;
}
?>
