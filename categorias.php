<?php
$servername = "localhost";
$username = "pantera";
$password = "cualquiera";
$dbname = "panteras2";

try {
    // Conectar a la base de datos con PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener el array de categorías desde el POST
    $categoria_ids = isset($_POST['categoria_id']) ? json_decode($_POST['categoria_id'], true) : [];

    // Crear el query base
    $sql = "
    SELECT 
        c.ID AS categoria_id,
        c.Nombre AS categoria_nombre,
        COUNT(DISTINCT a.ID) AS sesiones,
        COUNT(DISTINCT a.Correo) AS profesores,
        SUM(a.Duracion) AS total_horas_prof,
        SUM(a.Duracion) AS total_horas_talent,
        AVG(a.Duracion) AS duracion_media_prof,
        AVG(a.Duracion) AS duracion_media_talent
    FROM asesoria a
    JOIN categoria c ON a.id_Categoria = c.ID
    JOIN asesoria_asesor aa ON a.ID = aa.id_Asesoria
    JOIN asesor ase ON aa.id_Asesor = ase.ID
    ";

    // Si hay categorías en el array, añadir un filtro `WHERE`
    if (!empty($categoria_ids)) {
        $placeholders = implode(',', array_fill(0, count($categoria_ids), '?'));
        $sql .= " WHERE c.ID IN ($placeholders)";
    }

    // Completar la consulta con `GROUP BY` y `ORDER BY`
    $sql .= " GROUP BY c.ID, c.Nombre ORDER BY c.Nombre";

    // Preparar y ejecutar el statement
    $stmt = $conn->prepare($sql);

    // Ejecutar con o sin parámetros según el caso
    if (!empty($categoria_ids)) {
        $stmt->execute($categoria_ids);
    } else {
        $stmt->execute();
    }

    // Generar la salida en formato HTML
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["categoria_id"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["categoria_nombre"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["sesiones"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["profesores"]) . "</td>";
            echo "<td>" . htmlspecialchars(convertirMinutos($row["total_horas_prof"])) . "</td>";
            echo "<td>" . htmlspecialchars(convertirMinutos($row["total_horas_talent"])) . "</td>";
            echo "<td>" . htmlspecialchars(convertirMinutos($row["duracion_media_prof"])) . "</td>";
            echo "<td>" . htmlspecialchars(convertirMinutos($row["duracion_media_talent"])) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='8'>No se encontraron resultados</td></tr>";
    }
} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
}
