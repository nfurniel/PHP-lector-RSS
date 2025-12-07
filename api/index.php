<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Lector de Noticias RSS</title>
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
            }

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

                <div style="flex-grow: 1;">
                    <label>BUSCAR (Palabra clave)</label>
                    <input type="text" name="buscar" placeholder="Ej: elecciones..." style="width: 90%;">
                </div>

                <input type="submit" name="filtrar" value="Filtrar Resultados">
            </fieldset>
        </form>
        
        <?php
        require_once "RSSElPais.php";
        require_once "RSSElMundo.php";
        
        function filtros($sql, $link){
             $filtrar = mysqli_query($link, $sql);
             
             // Si hay error en la consulta, lo mostramos
             if(!$filtrar) {
                 echo "<tr><td colspan='6' style='text-align:center; color:red;'>Error en la consulta: " . mysqli_error($link) . "</td></tr>";
                 return;
             }

             while ($arrayFiltro = mysqli_fetch_array($filtrar)) {
                echo "<tr>";              
                    echo "<td><strong>".$arrayFiltro['titulo']."</strong></td>";
                    echo "<td>".substr(strip_tags($arrayFiltro['contenido']), 0, 150)."...</td>"; // Resumen corto
                    echo "<td>".$arrayFiltro['descripcion']."</td>";                      
                    echo "<td><span style='background:#e1f5fe; color:#0277bd; padding:3px 8px; border-radius:4px; font-size:0.8em;'>".$arrayFiltro['categoria']."</span></td>";                        
                    echo "<td><a href='".$arrayFiltro['link']."' target='_blank'>Leer más →</a></td>";                              
                    
                    $fecha = date_create($arrayFiltro['fPubli']);
                    $fechaConversion = date_format($fecha, 'd/m/Y');
                    echo "<td>".$fechaConversion."</td>";
                echo "</tr>";  
             }
        }
        
        require_once "conexionBBDD.php";
        
        // --- CONEXIÓN ---
        $link = conectarBD();
        
        if(!$link){
            printf("<p style='text-align:center; color:red;'>Conexión fallida: %s</p>", mysqli_connect_error());
        }else{
        
            echo "<table>";
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
                
                // Construcción dinámica de la Query (Más limpio)
                $sql = "SELECT * FROM " . $periodicosMin . " WHERE 1=1";

                if($cat != "") {
                    $sql .= " AND categoria LIKE '%$cat%'";
                }
                if($f != "") {
                    $sql .= " AND fPubli='$f'";
                }
                if($palabra != "") {
                    $sql .= " AND descripcion LIKE '%$palabra%'";
                }

                $sql .= " ORDER BY fPubli DESC";
                
                filtros($sql, $link);
                
            } else {
                // Consulta por defecto al entrar
                $sql = "SELECT * FROM elpais ORDER BY fPubli desc";
                filtros($sql, $link);      
            }
                  
            echo "</tbody>";
            echo "</table>";   
        }
        ?>
        
    </body>
</html>
