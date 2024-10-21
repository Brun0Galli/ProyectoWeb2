<?php
$servername = "localhost";
$username = "pantera";
$password = "cualquiera";
$dbname = "panteras2";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    //echo "connected successfully <br> ";
    /*$sede = $_POST['sede'] ?? '';
    $inicio = $_POST['inicio'] ?? '';
    $fin = $_POST['fin'] ?? '';
    $talent = $_POST['talent'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $filters = "";
    if($sede != "0"){
        $filters .= " AND asesoria.id_Sede = ".$sede;
    }
    if($talent != "0"){
        $filters .= " AND asesor.ID = ".$talent;
    }
    if($categoria != "0"){
        $filters .= " AND categoria.ID = ".$categoria;
    }
    $query = "SELECT asesoria.ID, asesoria.Correo AS Correo, Fecha, duracion as Duracion, categoria.Llave as Categoria, asesor.nombre as Asesor FROM asesor,asesoria_asesor,asesoria,categoria WHERE asesor.ID = asesoria_asesor.id_Asesor AND asesoria_asesor.id_Asesoria = asesoria.ID AND categoria.ID = asesoria.id_Categoria ". $filters ." ORDER BY Fecha DESC; ";
    */
    $query = $_POST['query'] ?? '';
    $result = $conn->query($query);
    $response = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($response, $row);
        }
        echo json_encode($response);
    } else {
        echo "0 results";
    }
}
