<?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Recoger los datos del formulario
        $titulo = $_POST["titulo"];
        $descripcion = $_POST["texto"];
        $categoria = $_POST["categoria"];
        
        // Verificar si se ha subido la imagen
        if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === UPLOAD_ERR_OK) {
            $imagenTmp = $_FILES["imagen"]["tmp_name"];
            $imagenNombre = $_FILES["imagen"]["name"];
            
            // Ruta de destino donde se almacenará la imagen
            $ruta = "/var/www/html/ejercicios_php/hoja11/";
            $rutaCompleta = $ruta . basename($imagenNombre);

            // Mover el archivo a la ruta especificada
            if (!move_uploaded_file($imagenTmp, $rutaCompleta)) {
                die("Error al subir la imagen.");
            }
        } else {
            // Si no se ha subido una imagen, se puede poner una ruta predeterminada o dejarla vacía
            $rutaCompleta = null;
        }
    }

    // Validar que los datos requeridos no estén vacíos
    if (empty($titulo) || empty($descripcion) || empty($categoria)) {
        echo "Error: Todos los campos marcados con * son obligatorios";
    }

    // Conectar a la base de datos
    try {
        $conexion = new PDO("mysql:host=127.0.0.1;dbname=inmobiliaria", "root", "root");
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Preparar la consulta SQL usando parámetros
        $consulta = $conexion->prepare(
            "INSERT INTO noticias (titulo, texto, categoria, fecha, imagen) 
            VALUES (:titulo, :descripcion, :categoria, :fecha, :imagen)"
        );

        // Ejecutar la consulta con los datos
        $fechaActual = date("Y-m-d"); // Obtener la fecha actual
        $consulta->execute([
            ':titulo' => $titulo,
            ':descripcion' => $descripcion,
            ':categoria' => $categoria,
            ':fecha' => $fechaActual,
            ':imagen' => $rutaCompleta
        ]);
    } catch (PDOException $e) {
        echo "Error al insertar la noticia: " . $e->getMessage();
    }

    echo "<h2>Gestion de noticias</h2>";
    echo "<h3>Resultado insercion nueva noticia</h3>";
    echo "<p>La noticia ha sido recibida correctamente: </p>";
    echo "<p> Titulo:  ".$titulo."</p>";
    echo "<p> Texto:  ".$descripcion."</p>";
    echo "<p> Categoria:  ".$categoria."</p>";
    echo "<p> Fecha:  ".$fechaActual."</p>";
    echo "<p> Imagen:  ".$rutaCompleta."</p>";

    echo "<a href='insertar_noticia.html'>Insertar nueva noticia</a>";
    echo "<br><a href='index.html'>Volver a inicio</a>";
?>
