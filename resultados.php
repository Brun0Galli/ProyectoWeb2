<?php
include "components/sql.php";

function convertirMinutos($minutos)
{
    $horas = floor($minutos / 60);  // Horas completas
    $minutosRestantes = $minutos % 60;  // Minutos restantes
    return sprintf("%02d:%02d", $horas, $minutosRestantes);  // Formato hh:mm
}
$results_per_page = 50;

// Obtener el número total de registros
$total_results_sql = "SELECT COUNT(*) AS total FROM asesoria";
$total_results_result = $conn->query($total_results_sql);
$total_row = $total_results_result->fetch_assoc();
$total_results = $total_row['total'];

// Calcular el número total de páginas
$total_pages = ceil($total_results / $results_per_page);

// Obtener el número de página actual desde la URL, si no está definido, será la página 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calcular el OFFSET para la consulta SQL
$start_from = ($page - 1) * $results_per_page;

// Consulta SQL con paginación
$sql = "SELECT a.ID AS asesoria_id, a.Correo, a.Fecha, a.Duracion, c.Nombre AS categoria, ase.Nombre AS asesor 
                        FROM asesoria a 
                        JOIN categoria c ON a.id_Categoria = c.ID 
                        JOIN asesoria_asesor aa ON a.ID = aa.id_Asesoria 
                        JOIN asesor ase ON aa.id_Asesor = ase.ID 
                        ORDER BY a.Fecha DESC 
                        LIMIT $results_per_page OFFSET $start_from";

$result = $conn->query($sql);

if (!$result) {
    die("Query error: " . $conn->error);
}
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["asesoria_id"] . "</td>";
        echo "<td>" . $row["Correo"] . "</td>";
        echo "<td>" . date("d-m-Y H:i:s", strtotime($row["Fecha"])) . "</td>";
        echo "<td>" . convertirMinutos($row["Duracion"]) . "</td>";
        echo "<td>" . $row["categoria"] . "</td>";
        echo "<td>" . $row["asesor"] . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No se encontraron resultados</td></tr>";
}
