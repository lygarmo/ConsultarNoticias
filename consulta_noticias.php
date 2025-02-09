<?php
    // Datos de conexión
    try {
        $conexion = new PDO ("mysql:host=127.0.0.1; dbname=inmobiliaria", "root", "root");
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Preparar y ejecutar la consulta
        $consulta = $conexion->prepare("SELECT * FROM noticias");
        $consulta->execute();

        // Obteniendo los resultados
        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

        if (count($resultados) > 0) {
            echo "<table border='1'>";
            echo "<thead>";
            echo "<tr>";
            // Encabezados de la tabla (obtenidos dinámicamente)
            foreach (array_keys($resultados[0]) as $columna) {
                echo "<th>" . $columna . "</th>";
            }
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            // Filas de la tabla
            foreach ($resultados as $fila) {
                echo "<tr>";
                foreach ($fila as $valor) {
                    echo "<td>" .$valor. "</td>";
                }
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "No se encontraron resultados.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    echo "<br><a href='index.html'>Volver a inicio</a>";
?>