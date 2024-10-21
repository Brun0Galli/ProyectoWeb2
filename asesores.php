<?php
include "components/sql.php";
$sql = "SELECT ase.ID, ase.Nombre, ase.Correo, 'Especialidad' AS especialidad FROM asesor ase";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["ID"] . "</td>";
        echo "<td>" . $row["Nombre"] . "</td>";
        echo "<td>" . $row["Correo"] . "</td>";
        echo "<td>" . $row["especialidad"] . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No se encontraron asesores</td></tr>";
}
