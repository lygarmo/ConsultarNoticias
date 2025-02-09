<?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $titulo = $_POST["titulo"];
    }

    if (empty($titulo)) {
        echo "Error: titulo vacio";
    }

    try {
        $conexion = new PDO("mysql:host=127.0.0.1;dbname=inmobiliaria", "root", "root");
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $consulta = $conexion->prepare("DELETE FROM noticias WHERE TITULO = :titulo");
        $consulta->execute([':titulo' => $titulo]);

        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

        if ($consulta->rowCount() > 0) {
            echo "La noticia: ".$titulo.", ha sido eliminada";
        } else {
            echo "No se encontraron resultados";
        }
    } catch (PDOException $e) {
        echo "Error al insertar la noticia: " . $e->getMessage();
    }

    echo "<br><br><a href='eliminar_noticia.html'>Eliminar otra noticia</a>";
    echo "<br><a href='index.html'>Volver a inicio</a>";
?>