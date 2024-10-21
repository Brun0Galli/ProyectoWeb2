<?php
include "components/sql.php"
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">

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
                        include "resultados.php";
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
                        include "categorias.php";
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
                        include "asesores.php";
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
        <script src="script.js"></script>
</body>

</html>