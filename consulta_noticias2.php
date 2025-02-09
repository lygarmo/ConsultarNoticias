<?php
    // Datos de conexión
    try {
        $conexion = new PDO ("mysql:host=127.0.0.1; dbname=inmobiliaria", "root", "root");
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //saber cuantas filas hay
        $numeroRegistros = $conexion->prepare("SELECT count(*) FROM noticias");
        $numeroRegistros->execute();
        //guarda el total de registros
        $totalRegistros = $numeroRegistros->fetchColumn();

        //cuantas noticias queremos mostrar en cada pagina
        $noticiasPorPagina = 3;
        //calculamos el numero total de paginas y redondeamos hacia arriba con ceil
        $totalPaginas = ceil($totalRegistros / $noticiasPorPagina);

        //manda en la url en la pagina que esta
        $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

        //determinamos la pagina actual
        if ($paginaActual < 1) {
            $paginaActual = 1;
        } elseif ($paginaActual > $totalPaginas) {
            $paginaActual = $totalPaginas;
        }

        // Calcula el número de registros a omitir (offset) según la página actual y el número de registros por página
        // Si estamos en la página 1, no se omiten registros, si estamos en la página 2, se omiten los primeros 'noticiasPorPagina' registros, etc.
        $offset = ($paginaActual - 1) * $noticiasPorPagina;

        //ejecuta la consulta
        $consulta = $conexion->prepare("SELECT * FROM noticias LIMIT :offset, :noticiasPorPagina");
        $consulta->bindParam(':offset', $offset, PDO::PARAM_INT);
        $consulta->bindParam(':noticiasPorPagina', $noticiasPorPagina, PDO::PARAM_INT);
        $consulta->execute();

        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

        //muestra la noticia
        if (count($resultados) > 0) {
            foreach ($resultados as $noticia) {
                echo "<h2>" . $noticia['TITULO'] . "</h2>";
                echo "<p><strong>Categoría:</strong> " . $noticia['CATEGORIA'] . "</p>";
                echo "<p><strong>Fecha:</strong> " . $noticia['FECHA'] . "</p>";
                echo "<p>" . $noticia['TEXTO'] . "</p>";
                echo "<br>";
            }
        } else {
            echo "No se encontraron resultados.";
        }

        // Paginación
        echo "<p>Mostrando " . count($resultados) . " de " . $totalRegistros . " registros.</p>";
        
        // Mostrar botones de paginación
        if ($paginaActual > 1) {
            echo "<a href='consulta_noticias2.php?pagina=" . ($paginaActual - 1) . "'>Anterior</a> ";
        }

        for ($i = 1; $i <= $totalPaginas; $i++) {
            if ($i == $paginaActual) {
                echo "<strong>$i</strong> ";
            } else {
                echo "<a href='consulta_noticias2.php?pagina=$i'>$i</a> ";
            }
        }

        if ($paginaActual < $totalPaginas) {
            echo "<a href='consulta_noticias2.php?pagina=" . ($paginaActual + 1) . "'>Siguiente</a>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    echo "<br><a href='index.html'>Volver a inicio</a>";
?>
