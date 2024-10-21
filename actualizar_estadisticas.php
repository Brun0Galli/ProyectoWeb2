<?php
$servername = "localhost";
$username = "pantera";
$password = "cualquiera";
$dbname = "panteras2";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['talent_id'])) {
    $talent_id = $_POST['talent_id'];

    // Query para obtener las estadísticas para el talent seleccionado
    $sql = "SELECT COUNT(a.ID) AS cantidad_sesiones, SUM(a.Duracion) AS total_horas_alumnos,  AVG(a.Duracion) AS duracion_media_sesion,  SUM(a.Duracion * (SELECT COUNT(aa.id_Asesor) FROM asesoria_asesor aa WHERE aa.id_Asesoria = a.ID)) AS total_horas_talent, COUNT(DISTINCT a.Correo) AS cantidad_alumnos_unicos  FROM asesoria a JOIN asesoria_asesor aa ON a.ID = aa.id_Asesoria  JOIN asesor ase ON aa.id_Asesor = ase.ID  WHERE ase.ID = $talent_id GROUP BY ase.ID;;
    ";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode($data);
    } else {
        echo json_encode([
            "sesiones" => 0,
            "total_horas_alumnos" => 0,
            "duracion_media_sesion" => 0,
            "total_horas_talent" => 0,
            "profesores" => 0
        ]);
    }
}

$conn->close();
