<?php
$servername = "localhost";
$username = "pantera";
$password = "cualquiera";
$dbname = "panteras2";
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$talent_ids = isset($_POST['talent_id']) ? json_decode($_POST['talent_id'], true) : [];

$sql = "SELECT ase.ID, ase.Nombre, ase.Correo, 'Especialidad' AS especialidad FROM asesor ase";

if (!empty($talent_ids)) {
    $placeholders = implode(',', array_fill(0, count($talent_ids), '?'));
    $sql .= " WHERE ase.Nombre IN ($placeholders)";
}

$stmt = $conn->prepare($sql);

if (!empty($talent_ids)) {
    $stmt->execute($talent_ids);
} else {
    $stmt->execute();
}

if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["ID"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["Nombre"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["Correo"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["especialidad"]) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No se encontraron asesores</td></tr>";
}
