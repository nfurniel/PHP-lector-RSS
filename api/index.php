<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>Lector de Noticias RSS</title>
        <style>
            /* Reset básico y fuente moderna */
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: #f0f2f5;
                margin: 0;
                padding: 20px;
                color: #333;
            }

            /* Estilo del Formulario (Tarjeta flotante) */
            form {
                background-color: #ffffff;
                max-width: 1000px;
                margin: 0 auto 30px auto;
                padding: 25px;
                border-radius: 12px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.05);
                border: 1px solid #e1e4e8;
            }

            fieldset {
                border: none;
                padding: 0;
                margin: 0;
                display: flex;
                flex-wrap: wrap;
                gap: 15px;
                align-items: flex-end;
                justify-content: center;
            }

            legend {
                font-size: 1.5rem;
                font-weight: 700;
                color: #2c3e50;
                width: 100%;
                text-align: center;
                margin-bottom: 20px;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            label {
                display: block;
                font-size: 0.85rem;
                font-weight: 600;
                color: #555;
                margin-bottom: 5px;
            }

            /* Inputs bonitos */
            select, input[type="date"], input[type="text"] {
                padding: 10px 15px;
                border: 1px solid #ccc;
                border-radius: 6px;
                font-size: 1rem;
                background-color: #fff;
                transition: border-color 0.3s;
                min-width: 150px;
            }

            select:focus, input:focus {
                border-color: #3498db;
                outline: none;
                box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            }

            /* Botón de filtrar */
            input[type="submit"] {
                background-color: #3498db;
                color: white;
                border: none;
                padding: 11px 25px;
                border-radius: 6px;
                font-size: 1rem;
                font-weight: bold;
                cursor: pointer;
                transition: background-color 0.3s, transform 0.1s;
            }

            input[type="submit"]:hover {
                background-color: #2980b9;
            }

            input[type="submit"]:active {
                transform: scale(0.98);
            }

            /* Estilos de la Tabla */
            table {
                width: 100%;
                max-width: 1200px;
                margin: 0 auto;
                border-collapse: separate;
                border-spacing: 0;
                background-color: #ffffff;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 4px 15px rgba(0,0,0,0.05);
                table-layout: fixed; /* Ayuda a que la tabla no se ensanche infinitamente */
            }

            /* Cabecera de la tabla */
            table tr:first-child th {
                background-color: #2c3e50;
                color: #ffffff;
                padding: 18px;
                text-transform: uppercase;
                font-size: 0.85rem;
                letter-spacing: 0.05em;
                border: none;
                font-weight: bold;
            }

            /* Celdas del cuerpo */
            td {
                padding: 15px 20px;
                text-align: left;
                border-bottom: 1px solid #eee;
                font-weight: normal;
                color: #444;
                font-size: 0.95rem;
                line-height: 1.5;
                vertical-align: top; /* Alinea el contenido arriba */
                word-wrap: break-word; /* Evita que textos largos rompan la tabla */
            }

            /* --- SOLUCIÓN PARA IMÁGENES GIGANTES --- */
            td img {
                max-width: 100% !important; /* Fuerza el ancho máximo al 100% del contenedor */
                height: auto !important;    /* Mantiene la proporción */
                display: block;
                margin: 10px auto 15px auto; /* Espacio alrededor */
                border-radius: 8px;
            }
            /* --------------------------------------- */

            /* Filas alternas (Zebra) */
            tr:nth-child(even) {
                background-color: #f8f9fa;
            }

            /* Efecto Hover al pasar el ratón */
            tr:hover {
                background-color: #e8f4fd;
            }

            /* Enlaces dentro de la tabla */
            a {
                color: #3498db;
                text-decoration: none;
                font-weight: 600;
                border-bottom: 2px solid transparent;
                transition: border-color 0.3s;
                display: inline-block; /* Mejor comportamiento para el botón */
            }

            a:hover {
                color: #1d6fa5;
                border-bottom: 2px solid #1d6fa5;
            }
            
            @media (max-width: 768px) {
                fieldset {
                    flex-direction: column;
                    align-items: stretch;
                }
                input[type="submit"] {
                    width: 100%;
                }
                table {
                    display: block;
                    overflow-x: auto;
                }
            }
        </style>
    </head>
    <body>
        <form action="index.php">
            <fieldset> 
                <legend>FILTRO DE NOTICIAS</legend>
                
                <div>
                    <label>PERIODICO</label>
                    <select name="periodicos">
                        <option value="elpais">El Pais</option>
                        <option value="elmundo">El Mundo</option>      
                    </select> 
                </div>

                <div>
                    <label>CATEGORIA</label>
                    <select name="categoria">
                        <option value=""></option>
                        <option value="Política">Política</option>
                        <option value="Deportes">Deportes</option>
                        <option value="Ciencia">Ciencia</option>
                        <option value="España">España</option>
                        <option value="Economía">Economía</option>
                        <option value="Música">Música</option>
                        <option value="Cine">Cine</option>
                        <option value="Europa">Europa</option>
                        <option value="Justicia">Justicia</option>                
                    </select>
                </div>

                <div>
                    <label>FECHA</label>
                    <input type="date" name="fecha">
                </div>

                <div style="flex-grow: 1; min-width: 250px;"> <label>BUSCAR (Palabra clave)</label>
                    <input type="text" name="buscar" placeholder="Ej: elecciones..." style="width: 100%; box-sizing: border-box;">
                </div>

                <input type="submit" name="filtrar" value="Filtrar Resultados">
            </fieldset>
        </form>
        
        <?php
        require_once "RSSElPais.php";
        require_once "RSSElMundo.php";
        
        function filtros($sql, $link){
             $filtrar = mysqli_query($link, $sql);
             
             if(!$filtrar) {
                 echo "<tr><td colspan='6' style='text-align:center; color:red; padding: 20px;'>Error en la consulta: " . mysqli_error($link) . "</td></tr>";
                 return;
             }

             if(mysqli_num_rows($filtrar) === 0){
                  echo "<tr><td colspan='6' style='text-align:center; padding: 30px; color: #666;'>No se encontraron noticias con esos filtros.</td></tr>";
             }

             while ($arrayFiltro = mysqli_fetch_array($filtrar)) {
                echo "<tr>";              
                    // Título un poco más grande
                    echo "<td><strong style='font-size: 1.1em;'>".$arrayFiltro['titulo']."</strong></td>";
                    
                    // Contenido (Aquí es donde ahora se aplicará el CSS a las imágenes)
                    echo "<td>".$arrayFiltro['contenido']."</td>"; 
                    
                    // Descripción (Aquí también puede haber imágenes)
                    echo "<td>".$arrayFiltro['descripcion']."</td>";                      
                    
                    // Categoría estilo etiqueta
                    echo "<td><span style='background:#e1f5fe; color:#0277bd; padding:4px 10px; border-radius:15px; font-size:0.85em; font-weight:500; display: inline-block; margin-bottom: 5px;'>".$arrayFiltro['categoria']."</span></td>";                        
                    
                    // Botón de enlace más visual
                    echo "<td><a href='".$arrayFiltro['link']."' target='_blank' style='background: #3498db; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; border:none; font-size: 0.9em;'>Leer noticia ↗</a></td>";                              
                    
                    $fecha = date_create($arrayFiltro['fPubli']);
                    $fechaConversion = date_format($fecha, 'd/m/Y');
                    echo "<td style='white-space: nowrap;'>".$fechaConversion."</td>"; // Evita que la fecha se parta en dos líneas
                echo "</tr>";  
             }
        }
        
        require_once "conexionBBDD.php";
        
        // --- CONEXIÓN ---
        $link = conectarBD();
        
        if(!$link){
            printf("<div style='text-align:center; color:red; padding:20px; background: #fee; border-radius: 8px;'>Conexión fallida: %s</div>", mysqli_connect_error());
        }else{
        
            echo "<table>";
            // Definimos anchos aproximados para las columnas para que quede más ordenado
            echo "<colgroup>
                    <col style='width: 15%'> <col style='width: 30%'> <col style='width: 20%'> <col style='width: 10%'> <col style='width: 10%'> <col style='width: 10%'> </colgroup>";
            echo "<thead>
                    <tr>
                        <th>TITULO</th>
                        <th>CONTENIDO</th>
                        <th>DESCRIPCIÓN</th>
                        <th>CATEGORÍA</th>
                        <th>ENLACE</th>
                        <th>FECHA</th>
                    </tr>
                  </thead>
                  <tbody>";

            if(isset($_REQUEST['filtrar'])){

                $periodicos = str_replace(' ','',$_REQUEST['periodicos']);
                $periodicosMin = strtolower($periodicos);
            
                $cat = $_REQUEST['categoria'];
                $f = $_REQUEST['fecha'];
                $palabra = $_REQUEST["buscar"];
                
                $sql = "SELECT * FROM " . $periodicosMin . " WHERE 1=1";

                if($cat != "") { $sql .= " AND categoria LIKE '%$cat%'"; }
                if($f != "") { $sql .= " AND fPubli='$f'"; }
                if($palabra != "") { $sql .= " AND descripcion LIKE '%$palabra%'"; }

                $sql .= " ORDER BY fPubli DESC LIMIT 50"; // Limitamos a 50 para que no cargue lento
                
                filtros($sql, $link);
                
            } else {
                // Consulta por defecto (las últimas 20 de El País)
                $sql = "SELECT * FROM elpais ORDER BY fPubli DESC LIMIT 20";
                filtros($sql, $link);      
            }
                  
            echo "</tbody>";
            echo "</table>";   
        }
        ?>
        <div style="height: 50px;"></div>
    </body>
</html>
