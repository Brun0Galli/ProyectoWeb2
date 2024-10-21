<?php
$servername = "localhost";
$username = "pantera";
$password = "panteracualquiera";
$dbname = "panteras";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    //echo "connected successfully <br> ";
    $query = "select * from asesor";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            //echo "ID: " . $row["ID"] . "             " . " - Correo: " . $row["Correo"] . "              " . " - Nombre: "  . $row["Nombre"]  . "<br>";
            echo "<tr>";
            echo "<td>".$row["ID"]."</td>";
            echo "<td>".$row["Correo"]."</td>";
            echo "<td>".$row["Nombre"]."</td>";
            echo "</tr>";
        }
    } else {
        echo "0";
    }
}
