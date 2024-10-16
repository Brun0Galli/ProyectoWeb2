<?php
$servername = "localhost";
$username = "pantera";
$password = "cualquiera";
$dbname = "panteras2";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "connected successfully <br> ";
    $query = "select * from asesor";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "ID: " . $row["ID"] . "             " . " - Correo: " . $row["Correo"] . "              " . " - Nombre: "  . $row["Nombre"]  . "<br>";
        }
    } else {
        echo "0 results";
    }
}
