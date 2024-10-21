<?php
$servername = "localhost";
$username = "pantera";
$password = "cualquiera";
$dbname = "panteras2";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asesorías Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <style>
        body {
            background-color: #1e1e1e;
            color: #fff;
        }

        .input-group {
            width: auto;
        }

        .input-group-text-custom {
            background-color: #333;
            color: #807834;
            border-radius: 20px 0 0 20px;
            border: none;
            padding: 0.375rem 0.75rem;
        }

        .form-select-custom {
            background-color: #333;
            color: #6c757d;
            border: none;
            border-radius: 20px;
        }

        .input-custom {
            background-color: #333;
            color: white;
            border: none;
            border-radius: 20px;
        }

        .btn-custom {
            background-color: #6c757d;
            /* Color del botón */
            border: none;
            /* Sin borde */
            border-radius: 10px;
            /* Bordes redondeados */
            padding: 0.375rem 0.75rem;
            /* Espaciado */
            color: white;
        }

        .btn-custom:hover {
            background-color: #52595f;
        }

        .btn-custom:hover:active {
            background-color: #52595f;
        }

        .btn-custom2 {
            background-color: #635e29;
            /* Color del botón */
            border: none;
            /* Sin borde */
            border-radius: 10px;
            /* Bordes redondeados */
            padding: 0.375rem 0.75rem;
            /* Espaciado */
            color: white;
        }

        .btn-custom2:hover {
            background-color: #5a5418;
            /* Color del botón */
        }

        .btn-custom2:hover:active {
            background-color: #5a5418;
            /* Color del botón */
        }

        .btn-custom i {
            font-size: 1.2rem;
        }

        .daterangepicker {
            background-color: white;
            /* Fondo oscuro */
            color: black;
            /* Texto blanco */
        }

        .daterangepicker .calendar-table {
            color: black;
        }

        .daterangepicker td.active,
        .daterangepicker td.active:hover {
            background-color: #635e29;
            /* Color del fondo activo */
            color: white;
        }

        .daterangepicker td.start-date,
        .daterangepicker td.end-date {
            background-color: #635e29;
            color: white;
        }

        .daterangepicker td.available:hover {
            background-color: #6c757d;
            /* Color del hover */
            color: white;
        }

        .daterangepicker .applyBtn,
        .daterangepicker .cancelBtn {
            background-color: #635e29;
            /* Botones aplicar/cancelar */
            border: none;
        }

        .daterangepicker .applyBtn:hover,
        .daterangepicker .cancelBtn:hover {
            background-color: #6c757d;
        }

        .resumen-filtros {
            background-color: #333;
            padding: 10px;
            border-radius: 20px;
            margin-top: 15px;
        }

        .filtro-tag {
            background-color: #6c757d;
            border-radius: 5px;
            padding: 5px 10px;
            margin-right: 5px;
            display: inline-block;
        }

        .resultados-tira {
            background-color: #dbb90f;
            padding: 10px;
            border-radius: 20px;
            margin-top: 50px;
            margin-bottom: 50px;
            margin-left: auto;
            margin-right: auto;
            max-width: 70%;
        }

        .stat-box {
            /* Color dorado */
            color: white;
            padding: 20px;
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
        }

        .stat-label {
            font-size: 1rem;
        }

        /* Encabezado de la tabla */
        .table-custom thead th {
            background-color: #1e1e1e;
            /* Color de fondo de los encabezados */
            color: #f1b24a;
            /* Color de texto dorado */
            font-weight: bold;
            text-transform: uppercase;
            text-align: left;
        }

        /* Estilo de las celdas */
        .table-custom tbody td {
            padding: 10px;
            border-color: #1e1e1e;
            background-color: #1e1e1e;
        }

        /* Efecto hover para las filas */
        .table-custom tbody tr:hover {
            background-color: #444;
            /* Color de fondo al pasar el cursor */
        }

        .pagination {
            justify-content: center;
        }

        .nav-menu {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .nav-menu a {
            margin: 0 15px;
            padding: 10px;
            text-decoration: none;
            color: white;
            font-weight: bold;
        }

        .nav-menu a:hover {
            color: #f1b24a;
            /* Efecto hover, color dorado */
        }

        .nav-menu a.active {
            color: #f1b24a;
            /* Color para el enlace activo */
        }

        /* Secciones de contenido */
        .section {
            padding: 50px;
            margin-top: 20px;
            display: none;
            /* Ocultar todas las secciones por defecto */
        }

        .section.active {
            display: block;
            /* Mostrar solo la sección activa */
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="d-flex">
                <button class="btn btn-custom mx-2">
                    <i class="bi bi-file-minus-fill"></i>
                </button>
                <h4>Filtros</h4>
            </div>
        </div>
        <hr>
        <form class="row" id="filterForm">
            <div class="row">
                <div class="row m-auto mt-3">
                    <div class="col-md-10">
                        <div class="d-flex align-items-center">
                            <div class="input-group" style="max-width: 400px;">
                                <span class="input-group-text input-group-text-custom">Inicio:</span>
                                <input type="text" class="input-custom me-2" id="fechaInicio">
                            </div>
                            <button class="btn btn-custom mx-2" type="button" id="calendarioInicio">
                                <i class="bi bi-calendar-x-fill"></i>
                            </button>
                            <div class="input-group" style="max-width: 400px;">
                                <span class="input-group-text input-group-text-custom ms-2">Fin:</span>
                                <input type="text" class="input-custom me-2" id="fechaFin">
                            </div>
                            <button class="btn btn-custom mx-2" type="button" id="calendarioFin">
                                <i class="bi bi-calendar-x-fill"></i>
                            </button>
                            <div class="input-group" style="max-width: 400px;">
                                <span class="input-group-text input-group-text-custom ms-2">Talent:</span>
                                <select class="form-select form-select-custom" id="talent">
                                    <option selected>Seleccione un miembro</option>
                                    <!-- Aquí cargarás los profesores desde la base de datos -->
                                    <?php
                                    $query = "select * from asesor";
                                    $result = $conn->query($query);
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row["ID"] . "'>" . $row["Nombre"] . "</option>";
                                        }
                                    } else {
                                        echo "<option value='0'>No hay profesores</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col m-auto">
                        <button type="reset" class="btn btn-lg rounded-pill btn-custom me-3" id="limpiarCampos">
                            Limpiar<i class="bi bi-trash ms-2"></i>
                        </button>
                    </div>
                </div>
                <div class="row m-auto mt-3">
                    <div class="col-md-10">
                        <div class="d-flex">
                            <div class="input-group" style="max-width: 400px;">
                                <span class="input-group-text input-group-text-custom">Sede:</span>
                                <select class="form-select form-select-custom" id="sede">
                                    <option selected>Todas las sedes</option>
                                    <option value="1">CDMX</option>
                                    <option value="2">AGS</option>
                                    <option value="3">GDL</option>
                                    <option value="5">CUP</option>
                                </select>
                            </div>
                            <div class="input-group ms-4" style="max-width: 400px;">
                                <span class="input-group-text input-group-text-custom">Categorías:</span>
                                <select class="form-select form-select-custom" id="categoria">
                                    <option selected>Seleccione una Categoría</option>
                                    <?php
                                    $query = "select * from categoria";
                                    $result = $conn->query($query);
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row["ID"] . "'>" . $row["Nombre"] . "</option>";
                                        }
                                    } else {
                                        echo "<option value='0'>No hay categorías</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-lg rounded-pill btn-custom2" id="buscar">
                            <i class="bi bi-search me-3"></i>Buscar
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <div class="resumen-filtros" id="resumenFiltros">
            <div class="row">
                <div class=" col-md-2">
                    <div class="row m-auto">
                        Intervalo de Fechas:
                    </div>
                    <div class="row m-auto mt-3">
                        Miembro Talent:
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row m-auto">
                        <div id="fechasSeleccionadas">

                        </div>
                    </div>
                    <div class="row m-auto mt-4">
                        <div id="filtrosSeleccionados">
                            <!-- Aquí se mostrarán los filtros seleccionados -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="resultados-tira" id="resultadosTira">
            <div class="row m-auto">
                <div class="d-flex">
                    <div class="col m-auto">
                        <div class="stat-box">
                            <div class="stat-number" id="statSession"></div>
                            <div class="stat-label">Sesiones</div>
                        </div>
                    </div>
                    <div class="col m-auto">
                        <div class="stat-box">
                            <div class="stat-number" id="statHrTotal"></div>
                            <div class="stat-label">Total Hrs. Profesor</div>
                        </div>
                    </div>
                    <div class="col m-auto">
                        <div class="stat-box">
                            <div class="stat-number" id="statDurMedia"></div>
                            <div class="stat-label">Duración Media Sesión</div>
                        </div>
                    </div>
                    <div class="col m-auto">
                        <div class="stat-box">
                            <div class="stat-number" id="statHrTotalTalent"></div>
                            <div class="stat-label">Total Hrs. Talent</div>
                        </div>
                    </div>
                    <div class="col m-auto">
                        <div class="stat-box">
                            <div class="stat-number" id="statAlumnosAtendidos"></div>
                            <div class="stat-label">Profesores</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mt-5">
            <nav class="nav-menu">
                <a href="#resultados" id="link-resultados" class="active">Resultados</a>
                <a href="#categorias" id="link-categorias">Categorías</a>
                <a href="#asesores" id="link-asesores">Asesores</a>
            </nav>

            <div id="resultados" class="section active">
                <table class="table table-hover table-dark table-custom">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Correo</th>
                            <th>Fecha</th>
                            <th>Duración</th>
                            <th>Categoría</th>
                            <th>Asesor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
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
                        ?>
                    </tbody>
                </table>

                <!-- Navegación de paginación -->
                <nav>
                    <ul class="pagination">
                        <?php
                        // Botón "anterior"
                        if ($page > 1) {
                            echo "<li class='page-item'><a class='page-link' href='?page=" . ($page - 1) . "'>Anterior</a></li>";
                        }

                        // Botones de números de página
                        for ($i = 1; $i <= $total_pages; $i++) {
                            if ($i == $page) {
                                echo "<li class='page-item active'><a class='page-link' href='?page=$i'>$i</a></li>";
                            } else {
                                echo "<li class='page-item'><a class='page-link' href='?page=$i'>$i</a></li>";
                            }
                        }

                        // Botón "siguiente"
                        if ($page < $total_pages) {
                            echo "<li class='page-item'><a class='page-link' href='?page=" . ($page + 1) . "'>Siguiente</a></li>";
                        }
                        ?>
                    </ul>
                </nav>
            </div>
            <div id="categorias" class="section">
                <h2>Categorías</h2>
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
                        // Query para obtener el resumen por categoría
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
            <div id="asesores" class="section">
                <h2>Asesores</h2>
                <table class="table table-hover table-dark table-custom">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Especialidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query para obtener la información de los asesores
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
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
        <!-- Scripts -->
        <script>
            $(document).ready(function() {

                var filtros = {};
                var fechas = {};

                // Agregar filtros seleccionados al resumen
                function actualizarResumen() {
                    var resumen = $("#filtrosSeleccionados");

                    resumen.empty(); // Limpiar el resumen visual
                    $.each(filtros, function(clave, valor) {
                        resumen.append('<span class="filtro-tag">' + clave + ': ' + valor + '</span>');
                    });
                }

                function actualizarFechas() {
                    var resumen = $("#fechasSeleccionadas");

                    resumen.empty(); //
                    $.each(fechas, function(clave, valor) {
                        resumen.append('<span class="filtro-tag">' + clave + ': ' + valor + '</span>');
                    });
                }
                // Cuando se selecciona un talent, se agrega automáticamente al resumen
                $('#talent').on('change', function() {
                    var talentText = $('#talent option:selected').text();
                    if (talentText !== "Seleccione un miembro") {
                        filtros["Talent"] = talentText;
                        actualizarResumen();
                    }
                });
                $('#fechaInicio').on('change', function() {
                    var fechaInicio = $('#fechaInicio').val();
                    if (fechaInicio) {
                        fechas["Inicio"] = fechaInicio;
                        actualizarFechas();
                    }
                });
                $('#fechaFin').on('change', function() {
                    var fechaFin = $('#fechaFin').val();
                    if (fechaFin) {
                        fechas["Fin"] = fechaFin;
                        actualizarFechas();
                    }
                });
                // Cuando se selecciona un talent, se realiza una consulta AJAX para actualizar los datos

                // Inicializar el calendario
                $('#calendarioInicio').on('click', function() {
                    $('#fechaInicio').daterangepicker({
                        singleDatePicker: true,
                        timePicker: true,
                        timePicker24Hour: true,
                        showDropdowns: true, // Agregar dropdowns para seleccionar el año y el mes
                        locale: {
                            format: 'YYYY-MM-DD HH:mm',
                            applyLabel: "Aplicar",
                            cancelLabel: "Cancelar",
                            daysOfWeek: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
                            monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                            firstDay: 1 // Comienza la semana en lunes
                        }
                    });
                });

                $('#calendarioFin').on('click', function() {
                    $('#fechaFin').daterangepicker({
                        singleDatePicker: true,
                        timePicker: true,
                        timePicker24Hour: true,
                        showDropdowns: true, // Agregar dropdowns para seleccionar el año y el mes
                        locale: {
                            format: 'YYYY-MM-DD HH:mm',
                            applyLabel: "Aplicar",
                            cancelLabel: "Cancelar",
                            daysOfWeek: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
                            monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                            firstDay: 1 // Comienza la semana en lunes
                        }
                    });
                });

                // Limpiar
                $('#limpiarCampos').on('click', function() {
                    $('#filterForm')[0].reset(); // Limpiar los campos del formulario
                    filtros = {};
                    fechas = {}; // Vaciar el objeto de filtros
                    actualizarResumen();
                    actualizarFechas();
                    $('#statSession').html('0');
                    $('#statHrTotal').html('00:00');
                    $('#statDurMedia').html('00:00');
                    $('#statHrTotalTalent').html('00:00 ');
                    $('#statAlumnosAtendidos').html('0');

                    // Actualizar el resumen visual
                });

                function convertMinutesToHHMM(minutes) {
                    var hours = Math.floor(minutes / 60); // Obtener las horas
                    var mins = Math.floor(minutes % 60); // Obtener los minutos restantes
                    return hours + ':' + (mins < 10 ? '0' : '') + mins; // Formatear como hh:mm
                }
                $('#buscar').on('click', function(e) {
                    e.preventDefault(); // Evitar el comportamiento predeterminado del formulario

                    var talentID = $('#talent').val();

                    if (talentID !== "0") { // Solo ejecutar si se selecciona un talent válido
                        // Hacer la solicitud AJAX para ejecutar el query
                        $.ajax({
                            url: 'actualizar_estadisticas.php', // Archivo PHP que ejecutará el query
                            type: 'POST',
                            dataType: 'json', // Esperamos recibir JSON en la respuesta
                            data: {
                                talent_id: talentID
                            },
                            success: function(response) {
                                // Insertar los resultados de la búsqueda en los divs
                                console.log(response);
                                var totalHorasAlumnos = convertMinutesToHHMM(response.total_horas_alumnos);
                                var duracionMediaSesion = convertMinutesToHHMM(response.duracion_media_sesion);
                                var totalHorasTalent = convertMinutesToHHMM(response.total_horas_talent);
                                $('#statSession').html(response.cantidad_sesiones);
                                $('#statHrTotal').html(totalHorasAlumnos);
                                $('#statDurMedia').html(duracionMediaSesion);
                                $('#statHrTotalTalent').html(totalHorasTalent);
                                $('#statAlumnosAtendidos').html(response.cantidad_alumnos_unicos);
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log("Error: " + textStatus + " " + errorThrown);
                            }
                        });
                    } else {
                        alert("Por favor, selecciona un Talent válido.");
                    }
                });
            });
            document.addEventListener("DOMContentLoaded", function() {
                // Obtener todos los enlaces del menú y todas las secciones
                const links = document.querySelectorAll(".nav-menu a");
                const sections = document.querySelectorAll(".section");

                // Función para cambiar de sección
                function showSection(hash) {
                    // Ocultar todas las secciones
                    sections.forEach(section => {
                        section.classList.remove("active");
                    });

                    // Remover clase "active" de todos los enlaces
                    links.forEach(link => {
                        link.classList.remove("active");
                    });

                    // Mostrar la sección correcta y resaltar el enlace activo
                    document.querySelector(hash).classList.add("active");
                    document.querySelector(`a[href='${hash}']`).classList.add("active");
                }

                // Detectar el hash actual en la URL y mostrar la sección correspondiente
                const currentHash = window.location.hash || "#resultados";
                showSection(currentHash);

                // Añadir evento click a cada enlace
                links.forEach(link => {
                    link.addEventListener("click", function(event) {
                        event.preventDefault(); // Prevenir el comportamiento predeterminado del enlace
                        const hash = this.getAttribute("href");
                        window.location.hash = hash; // Cambiar la URL
                        showSection(hash); // Mostrar la sección correspondiente
                    });
                });
            });
        </script>
</body>

</html>