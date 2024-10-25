<?php
$servername = "localhost";
$username = "pantera";
$password = "cualquiera";
$dbname = "panteras2";
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_POST['talent_id'])) {
    // Decodificar los arrays JSON en PHP
    $talent_ids = json_decode($_POST['talent_id'], true);
    $categoria_ids = isset($_POST['categoria_id']) ? json_decode($_POST['categoria_id'], true) : [];  // Array vacío si no hay categorías

    // Filtros opcionales
    $fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null;
    $fecha_fin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;

    // Crear placeholders para asesores y categorías
    $talent_placeholders = implode(',', array_fill(0, count($talent_ids), '?'));
    $categoria_placeholders = count($categoria_ids) > 0 ? implode(',', array_fill(0, count($categoria_ids), '?')) : null;

    // Query para obtener las estadísticas
    $sql1 = "
        SELECT 
            COUNT(a.ID) AS cantidad_sesiones, 
            SUM(a.Duracion) AS total_horas_alumnos,  
            AVG(a.Duracion) AS duracion_media_sesion,  
            SUM(a.Duracion * (SELECT COUNT(aa.id_Asesor) FROM asesoria_asesor aa WHERE aa.id_Asesoria = a.ID)) AS total_horas_talent, 
            COUNT(DISTINCT a.Correo) AS cantidad_alumnos_unicos  
        FROM asesoria a 
        JOIN asesoria_asesor aa ON a.ID = aa.id_Asesoria  
        JOIN asesor ase ON aa.id_Asesor = ase.ID  
        JOIN categoria c ON a.id_Categoria = c.ID 
        WHERE ase.Nombre IN ($talent_placeholders)
    ";

    // Agregar filtro de categorías si hay categorías en el array
    if ($categoria_placeholders) {
        $sql1 .= " AND c.ID IN ($categoria_placeholders)";
    }

    // Preparar y ejecutar la primera consulta
    $stmt1 = $conn->prepare($sql1);
    $stmt1->execute(array_merge($talent_ids, $categoria_ids));  // Pasar ambos arrays como parámetros

    // Definir valores por defecto en caso de no haber resultados
    $estadisticas = [
        "sesiones" => 0,
        "total_horas_alumnos" => 0,
        "duracion_media_sesion" => 0,
        "total_horas_talent" => 0,
        "cantidad_alumnos_unicos" => 0
    ];

    // Obtener resultados de estadísticas si existen
    if ($stmt1->rowCount() > 0) {
        $estadisticas = $stmt1->fetch(PDO::FETCH_ASSOC);
    }

    // Query para obtener las asesorías del talent seleccionado con filtros
    $sql2 = "
        SELECT 
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
        WHERE ase.Nombre IN ($talent_placeholders)
    ";

    // Agregar filtro de categorías si hay categorías en el array
    if ($categoria_placeholders) {
        $sql2 .= " AND c.ID IN ($categoria_placeholders)";
    }

    // Agregar filtro de rango de fechas si están presentes
    $params = array_merge($talent_ids, $categoria_ids);  // Combinar IDs de asesores y categorías como parámetros iniciales
    if ($fecha_inicio && $fecha_fin) {
        $sql2 .= " AND a.Fecha BETWEEN ? AND ?";
        array_push($params, $fecha_inicio, $fecha_fin);  // Añadir las fechas al array de parámetros
    }

    // Ordenar los resultados por fecha
    $sql2 .= " ORDER BY a.Fecha DESC";

    // Preparar y ejecutar el segundo query
    $stmt2 = $conn->prepare($sql2);
    $stmt2->execute($params); // Pasar los parámetros completos incluyendo fechas si están presentes

    // Obtener los resultados de asesorías
    $asesorias = [];
    if ($stmt2->rowCount() > 0) {
        while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
            $asesorias[] = $row;
        }
    }

    // Combinar ambas respuestas en un solo JSON
    $response = [
        "estadisticas" => $estadisticas,
        "asesorias" => $asesorias
    ];

    // Devolver la respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}

$conn = null;
