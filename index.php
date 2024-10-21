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
    <link href="style.css" rel="stylesheet">
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
                            <div class="stat-number" id="statSession">164</div>
                            <div class="stat-label">Sesiones</div>
                        </div>
                    </div>
                    <div class="col m-auto">
                        <div class="stat-box">
                            <div class="stat-number" id="statHrTotal">145:45</div>
                            <div class="stat-label">Total Hrs. Profesor</div>
                        </div>
                    </div>
                    <div class="col m-auto">
                        <div class="stat-box">
                            <div class="stat-number" id="statDurMedia">0:53</div>
                            <div class="stat-label">Duración Media Sesión</div>
                        </div>
                    </div>
                    <div class="col m-auto">
                        <div class="stat-box">
                            <div class="stat-number" id="statHrTotalTalent">147:15</div>
                            <div class="stat-label">Total Hrs. Talent</div>
                        </div>
                    </div>
                    <div class="col m-auto">
                        <div class="stat-box">
                            <div class="stat-number" id="statAlumnosAtendidos">126</div>
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
                            $minutosRestantes = round((float) $minutos) % 60;  // Minutos restantes
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
        <script src="script.js"></script>
</body>

</html>