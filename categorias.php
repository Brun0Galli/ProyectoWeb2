<?php
include "components/sql.php";
// Query para obtener el resumen por categoría
$sql = "
SELECT 
    c.ID AS categoria_id,
    c.Nombre AS categoria_nombre,
    COUNT(DISTINCT a.ID) AS sesiones,
    COUNT(DISTINCT a.Correo) AS profesores,
    SUM(a.Duracion) AS total_horas_prof,
    SUM(a.Duracion) AS total_horas_talent, -- Esto puede cambiar si manejas horas de talent de otra manera
    AVG(a.Duracion) AS duracion_media_prof,
    AVG(a.Duracion) AS duracion_media_talent -- Asumiendo que las horas de talent son iguales a las de profesor
FROM asesoria a
JOIN categoria c ON a.id_Categoria = c.ID
JOIN asesoria_asesor aa ON a.ID = aa.id_Asesoria
JOIN asesor ase ON aa.id_Asesor = ase.ID
GROUP BY c.ID, c.Nombre
ORDER BY c.Nombre;
";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["categoria_id"] . "</td>";
        echo "<td>" . $row["categoria_nombre"] . "</td>";
        echo "<td>" . $row["sesiones"] . "</td>";
        echo "<td>" . $row["profesores"] . "</td>";
        echo "<td>" . convertirMinutos($row["total_horas_prof"]) . "</td>";  // Convertir minutos a hh:mm
        echo "<td>" . convertirMinutos($row["total_horas_talent"]) . "</td>";  // Convertir minutos a hh:mm
        echo "<td>" . convertirMinutos($row["duracion_media_prof"]) . "</td>";  // Convertir minutos a hh:mm
        echo "<td>" . convertirMinutos($row["duracion_media_talent"]) . "</td>";  // Convertir minutos a hh:mm
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='8'>No se encontraron resultados</td></tr>";
}
