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

    // Filtros opcionales
    $fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null;
    $fecha_fin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;


    // Query para obtener las estadísticas para el talent seleccionado (esto permanece sin cambios)
    $sql1 = "SELECT 
                COUNT(a.ID) AS cantidad_sesiones, 
                SUM(a.Duracion) AS total_horas_alumnos,  
                AVG(a.Duracion) AS duracion_media_sesion,  
                SUM(a.Duracion * (SELECT COUNT(aa.id_Asesor) FROM asesoria_asesor aa WHERE aa.id_Asesoria = a.ID)) AS total_horas_talent, 
                COUNT(DISTINCT a.Correo) AS cantidad_alumnos_unicos  
            FROM asesoria a 
            JOIN asesoria_asesor aa ON a.ID = aa.id_Asesoria  
            JOIN asesor ase ON aa.id_Asesor = ase.ID  
            WHERE ase.ID = $talent_id
            GROUP BY ase.ID";

    // Query para obtener las asesorías del talent seleccionado (con filtros dinámicos)
    $sql2 = "SELECT 
                a.ID AS asesoria_id, 
                a.Correo, 
                a.Fecha, 
                a.Duracion, 
                c.Nombre AS categoria, 
                ase.Nombre AS asesor 
            FROM asesoria a 
            JOIN categoria c ON a.id_Categoria = c.ID 
            JOIN asesoria_asesor aa ON a.ID = aa.id_Asesoria 
            JOIN asesor ase ON aa.id_Asesor = ase.ID 
            WHERE ase.ID = $talent_id";

    // Agregar filtro de rango de fechas si está presente
    if (!empty($fecha_inicio) && !empty($fecha_fin)) {
        $sql2 .= " AND a.Fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
    }

    // Agregar filtro de categoría si está presente
    if (isset($_POST['categoria_id'])) {
        $categoria_id = $_POST['categoria_id'];
        $sql2 .= " AND c.ID = $categoria_id";
    }

    // Ordenar por fecha
    $sql2 .= " ORDER BY a.Fecha DESC";

    // Ejecutar el primer query (estadísticas)
    $result1 = $conn->query($sql1);
    $estadisticas = [
        "sesiones" => 0,
        "total_horas_alumnos" => 0,
        "duracion_media_sesion" => 0,
        "total_horas_talent" => 0,
        "profesores" => 0
    ]; // Valores por defecto

    if ($result1->num_rows > 0) {
        $estadisticas = $result1->fetch_assoc(); // Obtener las estadísticas
    }

    // Ejecutar el segundo query (asesorías) con los filtros dinámicos
    $result2 = $conn->query($sql2);
    $asesorias = [];

    if ($result2->num_rows > 0) {
        while ($row = $result2->fetch_assoc()) {
            $asesorias[] = $row;  // Guardar cada fila en el array de asesorías
        }
    }

    // Combinar ambas respuestas en un solo JSON
    $response = [
        "estadisticas" => $estadisticas,  // Las estadísticas del talent
        "asesorias" => $asesorias         // La tabla de asesorías
    ];

    // Devolver la respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}

$conn->close();
