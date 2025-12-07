<?php
require_once "conexionRSS.php";

// 1. Usamos la URL segura de El País (HTTPS)
$sXML = download("https://feeds.elpais.com/mrss-s/pages/ep/site/elpais.com/portada");

// Validación por si la descarga falla
if (empty($sXML)) {
    // Si falla, intentamos parar aquí para no generar errores XML
    die("Error: No se ha recibido contenido XML de El País.");
}

$oXML = new SimpleXMLElement($sXML);

require_once "conexionBBDD.php";

// 2. IMPORTANTE: Inicializamos la conexión a la base de datos
$link = conectarBD();

if (!$link) {
    printf("Conexión a el periódico El País ha fallado: " . mysqli_connect_error());
} else {

    $categoria = ["Política", "Deportes", "Ciencia", "España", "Economía", "Música", "Cine", "Europa", "Justicia"];
    $categoriaFiltro = "";

    foreach ($oXML->channel->item as $item) {

        // --- FILTRO DE CATEGORÍAS ---
        // Recorremos las categorías del XML y las comparamos con nuestro array
        foreach ($item->category as $catXML) {
            for ($j = 0; $j < count($categoria); $j++) {
                if ((string)$catXML == $categoria[$j]) {
                    $categoriaFiltro = "[" . $categoria[$j] . "]" . $categoriaFiltro;
                }
            }
        }

        // --- FECHAS ---
        $fPubli = strtotime($item->pubDate);
        $new_fPubli = date('Y-m-d', $fPubli);

        // --- CONTENIDO ---
        // El País a veces usa 'content:encoded' para el cuerpo de la noticia
        $content = $item->children("content", true);
        $encoded = (string)$content->encoded;
        
        // Si no hay contenido extendido, usamos la descripción normal
        if(empty($encoded)){
            $encoded = (string)$item->description;
        }

        // --- COMPROBACIÓN DE DUPLICADOS (Optimizada) ---
        // Usamos COUNT para preguntar a la base de datos si el link ya existe.
        // Es mucho más rápido que traerse todos los links y compararlos uno a uno en PHP.
        
        $linkNoticia = mysqli_real_escape_string($link, (string)$item->link);
        $sqlCheck = "SELECT COUNT(*) as total FROM elpais WHERE link = '$linkNoticia'";
        $resultCheck = mysqli_query($link, $sqlCheck);
        $rowCheck = mysqli_fetch_assoc($resultCheck);

        // Si total es 0, significa que NO existe y podemos insertar
        if ($rowCheck['total'] == 0 && $categoriaFiltro != "") {

            // 3. LIMPIEZA DE DATOS (Seguridad)
            // Usamos mysqli_real_escape_string para evitar errores si el título lleva comillas
            $titulo = mysqli_real_escape_string($link, (string)$item->title);
            $descripcion = mysqli_real_escape_string($link, (string)$item->description);
            $contenido = mysqli_real_escape_string($link, $encoded);
            $catSafe = mysqli_real_escape_string($link, $categoriaFiltro);

            // Insertamos (dejamos el primer valor vacío '' para el autoincrement del ID)
            $sql = "INSERT INTO elpais (cod, titulo, link, descripcion, categoria, fPubli, contenido) 
                    VALUES (null, '$titulo', '$linkNoticia', '$descripcion', '$catSafe', '$new_fPubli', '$contenido')";
            
            mysqli_query($link, $sql);
        }

        // Reiniciamos la categoría para la siguiente vuelta del bucle
        $categoriaFiltro = "";
    }
}
?>
