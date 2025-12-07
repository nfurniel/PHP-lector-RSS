<?php
require_once "conexionRSS.php";

// Usamos la URL del RSS de El Mundo
$sXML = download("https://e00-elmundo.uecdn.es/elmundo/rss/espana.xml");

// Validación básica
if (empty($sXML)) {
    die("Error: No se ha recibido contenido XML de El Mundo.");
}

$oXML = new SimpleXMLElement($sXML);

require_once "conexionBBDD.php";

// 1. INICIALIZAR CONEXIÓN (Igual que hicimos en El País)
$link = conectarBD();

if (!$link) {
    printf("Conexión a el periódico El Mundo ha fallado: " . mysqli_connect_error());
} else {

    $categoria = ["Política", "Deportes", "Ciencia", "España", "Economía", "Música", "Cine", "Europa", "Justicia"];
    $categoriaFiltro = "";

    foreach ($oXML->channel->item as $item) {

        // --- EXTRAYENDO DATOS ---
        // El Mundo usa namespaces para las imágenes y descripciones a veces
        $media = $item->children("media", true);
        $descripcion = (string)$media->description;
        
        // Si la descripción especial está vacía, usamos la estándar
        if (empty($descripcion)) {
            $descripcion = (string)$item->description;
        }

        // --- FILTRO CATEGORÍAS ---
        foreach ($item->category as $catXML) {
            for ($j = 0; $j < count($categoria); $j++) {
                if ((string)$catXML == $categoria[$j]) {
                    $categoriaFiltro = "[" . $categoria[$j] . "]" . $categoriaFiltro;
                }
            }
        }

        // --- FECHA ---
        $fPubli = strtotime($item->pubDate);
        $new_fPubli = date('Y-m-d', $fPubli);

        // --- COMPROBAR DUPLICADOS ---
        $linkNoticia = mysqli_real_escape_string($link, (string)$item->link);
        $sqlCheck = "SELECT COUNT(*) as total FROM elmundo WHERE link = '$linkNoticia'";
        $resultCheck = mysqli_query($link, $sqlCheck);
        $rowCheck = mysqli_fetch_assoc($resultCheck);

        // Si no existe y tiene categoría válida, insertamos
        if ($rowCheck['total'] == 0 && $categoriaFiltro != "") {

            // 2. ESCAPAR DATOS (Seguridad contra comillas raras)
            $titulo = mysqli_real_escape_string($link, (string)$item->title);
            $descSafe = mysqli_real_escape_string($link, $descripcion);
            $catSafe = mysqli_real_escape_string($link, $categoriaFiltro);
            // El Mundo suele meter el GUID o contenido extra al final
            $contenido = mysqli_real_escape_string($link, (string)$item->guid); 

            // 3. LA CORRECCIÓN CLAVE: Usamos 'NULL' en vez de '' para el cod
            $sql = "INSERT INTO elmundo (cod, titulo, link, descripcion, categoria, fPubli, contenido) 
                    VALUES (NULL, '$titulo', '$linkNoticia', '$descSafe', '$catSafe', '$new_fPubli', '$contenido')";
            
            if (!mysqli_query($link, $sql)) {
                // Opcional: ver error si falla una inserción concreta
                // echo "Error insertando: " . mysqli_error($link);
            }
        }

        $categoriaFiltro = ""; // Resetear para la siguiente vuelta
    }
}
?>
