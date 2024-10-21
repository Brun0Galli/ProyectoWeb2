<?php
$servername = "localhost";
$username = "pantera";
$password = "cualquiera";
$dbname = "panteras2";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Consulta SQL para obtener el resumen de categorías
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

if (!$result) {
    die("Query error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen por Categoría</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .table-custom {
            background-color: #1e1e1e;
        }

        .table-custom th {
            background-color: #6c757d;
            color: #f1b24a;
            font-weight: bold;
            text-transform: uppercase;
        }

        .table-custom td {
            background-color: #333;
            color: #fff;
        }

        .table-custom tbody tr:hover {
            background-color: #444;
        }

        .table-custom tbody tr:nth-of-type(even) {
            background-color: #2a2a2a;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <h2 class="text-white">Resumen de Categorías</h2>

        <table class="table table-hover table-dark table-custom">
            <thead>
                <tr>
                    <th>Key</th>
                    <th>Nombre</th>
                    <th>Sesiones</th>
                    <th>Profesores</th>
                    <th>Total Horas Prof</th>
                    <th>Total Horas Talent</th>
                    <th>Duración Media Prof</th>
                    <th>Duración Media Talent</th>
                </tr>
            </thead>
            <tbody>
                <?php
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
                ?>
            </tbody>
        </table>
    </div>

</body>

</html>

<?php
$conn->close();

// Función para convertir minutos en horas y minutos
function convertirMinutos($minutos)
{
    $horas = floor($minutos / 60);  // Horas completas
    $minutosRestantes = $minutos % 60;  // Minutos restantes
    return sprintf("%02d:%02d", $horas, $minutosRestantes);  // Formato hh:mm
}
?>