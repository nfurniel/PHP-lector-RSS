<?php
function download($ruta){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $ruta);
    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    // IMPORTANTE: Seguir redirecciones (si te mandan de http a https)
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    
    // EL TRUCO: Disfrazarnos de navegador Chrome para que no nos bloqueen
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
    
    // Ignorar problemas de certificados SSL (útil en algunos servidores)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $salida = curl_exec($ch);
    curl_close($ch);
    
    return $salida;
}
?>



