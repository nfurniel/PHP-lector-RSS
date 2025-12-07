<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
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
        align-items: flex-end; /* Alinea los inputs y el botón abajo */
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
        border-collapse: separate; /* Permite bordes redondeados */
        border-spacing: 0;
        background-color: #ffffff;
        border-radius: 12px;
        overflow: hidden; /* Recorta las esquinas */
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
    }
    
    /* Forzamos el color blanco en los textos de la cabecera */
    table tr:first-child th p {
        color: white !important;
        margin: 0;
    }

    /* Celdas del cuerpo */
    th, td {
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
        font-weight: 500;
    }

    a:hover {
        text-decoration: underline;
        color: #1d6fa5;
    }
    
    /* Ajuste para móviles */
    @media (max-width: 768px) {
        fieldset {
            flex-direction: column;
            align-items: stretch;
        }
        input[type="submit"] {
            width: 100%;
        }
    }
</style>
    </head>
    <body>
        <form action="index.php">
            <fieldset> 
                <legend>FILTRO</legend>
                <label>PERIODICO : </label>
                <select type="selector" name="periodicos">
                    <option name="elpais">El Pais</option>
                    <option name="elmundo">El Mundo</option>      
                </select> 
                <label>CATEGORIA : </label>
                <select type="selector" name="categoria" value="">
                    <option name=""></option>
                    <option name="Política">Política</option>
                    <option name="Deportes">Deportes</option>
                    <option name="Ciencia">Ciencia</option>
                    <option name="España">España</option>
                    <option name="Economía">Economía</option>
                    <option name="Música">Música</option>
                    <option name="Cine">Cine</option>
                    <option name="Europa">Europa</option>
                    <option name="Justicia">Justicia</option>                
                </select>
                <label>FECHA : </label>
                <input type="date" name="fecha" value=""></input>
                <label style="margin-left: 5vw;">AMPLIAR FILTRO (la descripción contenga la palabra) : </label>
                <input type="text" name="buscar" value=""></input>
                <input type="submit" name="filtrar">
            </fieldset>
        </form>
        
        
        
        
        
        <?php
        
        
        require_once "RSSElPais.php";
        require_once "RSSElMundo.php";
        
        function filtros($sql, $link){
                 $filtrar= mysqli_query($link, $sql);
                 while ($arrayFiltro= mysqli_fetch_array($filtrar)) {

                               echo"<tr>";              
                                    echo "<th style='border: 1px #E4CCE8 solid;'>".$arrayFiltro['titulo']."</th>";
                                    echo "<th style='border: 1px #E4CCE8 solid;'>".$arrayFiltro['contenido']."</th>";
                                    echo "<th style='border: 1px #E4CCE8 solid;'>".$arrayFiltro['descripcion']."</th>";                      
                                    echo "<th style='border: 1px #E4CCE8 solid;'>".$arrayFiltro['categoria']."</th>";                       
                                    echo "<th style='border: 1px #E4CCE8 solid;'>".$arrayFiltro['link']."</th>";                              
                                    $fecha=date_create($arrayFiltro['fPubli']);
                                    $fechaConversion=date_format($fecha,'d-M-Y');
                                    //$fechaConversion=date('j-n-Y',srtotime($arrayFiltro['fPubli']));
                                    echo "<th style='border: 1px #E4CCE8 solid;'>".$fechaConversion."</th>";
                               echo"</tr>";  

                    }
 
        }
        
        require_once "conexionBBDD.php";
        
        if(mysqli_connect_error()){
        printf("Conexión fallida");
        }else{
       
            echo"<table style='border: 5px #E4CCE8 solid;'>";
            echo"<tr><th><p style='color: #66E9D9;'>TITULO</p ></th><th><p  style='color: #66E9D9;'>CONTENIDO</p ></th><th><p  style='color: #66E9D9;'>DESCRIPCIÓN</p ></th><th><p  style='color: #66E9D9;'>CATEGORÍA</p ></th><th><p  style='color: #66E9D9;'>ENLACE</p ></th><th><p  style='color: #66E9D9;'>FECHA DE PUBLICACIÓN</p ></th></tr>"."<br>";

               
           

            if(isset($_REQUEST['filtrar'])){

             $periodicos= str_replace(' ','',$_REQUEST['periodicos']);
             $periodicosMin=strtolower($periodicos);
            

                $cat=$_REQUEST['categoria'];
                $f=$_REQUEST['fecha'];
                $palabra=$_REQUEST["buscar"];
                 
                //FILTRO PERIODICO

                if($cat=="" && $f=="" && $palabra==""){
                 //$periodicos= str_replace(' ','',$_REQUEST['periodicos']);
                 $sql="SELECT * FROM ".$periodicosMin." ORDER BY fPubli desc";
                 
                 filtros($sql,$link);
                
                }

                //FILTRO CATEGORIA
                
                   if($cat!="" && $f=="" && $palabra==""){ 
                    $sql="SELECT * FROM ".$periodicosMin." WHERE categoria LIKE '%$cat%'";
                    
                    filtros($sql,$link);

                        
                    }

                    //FILTRO FECHA

                       if($cat=="" && $f!="" && $palabra==""){
                           $sql="SELECT * FROM ".$periodicosMin." WHERE fPubli='$f'";
                          
                           filtros($sql,$link);
                           
                        }

                        //FILTRO CATEGORIA Y FECHA
                            if($cat!="" && $f!="" && $palabra==""){ 
                              $sql="SELECT * FROM ".$periodicosMin." WHERE categoria LIKE '%$cat%' and fPubli='$f'";
                             
                              filtros($sql,$link);
                              
                            }

                            //FILTRO TODO
                            
                             if($cat!="" && $f!="" && $palabra!=""){ 
                              $sql="SELECT * FROM ".$periodicosMin." WHERE descripcion LIKE '%$palabra%' and categoria LIKE '%$cat%' and fPubli='$f'";
                             
                              filtros($sql,$link);
                            
                            }  

                            //FILTRO CATEGORIA PALABRA
            
                            if($cat!="" && $f=="" && $palabra!=""){ 
                                 //echo $periodicosMin;
                              $sql="SELECT * FROM ".$periodicosMin." WHERE descripcion LIKE '%$palabra%' and categoria LIKE '%$cat%'";
                             
                              filtros($sql,$link);
                            
                            } 

                            //FILTRO FECHA Y PALABRA 
                            
                             if($cat=="" && $f!="" && $palabra!=""){ 
                                 //echo $periodicosMin;
                              $sql="SELECT * FROM ".$periodicosMin." WHERE descripcion LIKE '%$palabra%' and fPubli='$f'";
                             
                              filtros($sql,$link);
                            
                            }  

                            //FILTRO PALABRA
                            
                            if($palabra!="" && $cat=="" && $f=="" ){ 
                               // echo $periodicosMin;
                                // echo $palabra;
                              $sql="SELECT * FROM ".$periodicosMin." WHERE descripcion LIKE '%$palabra%' ";
                             
                              filtros($sql,$link);
                            
                            }  
                
            }else{
                            
                            $sql="SELECT * FROM elpais ORDER BY fPubli desc";
                            
                            filtros($sql,$link);
                            
            }
                  
        }
        
          
        echo"</table>";   
        
           
        
    
    
     
        ?>
        
    </body>
</html>
