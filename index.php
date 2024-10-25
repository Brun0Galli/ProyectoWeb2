<?php
$servername = "localhost";
$username = "pantera";
$password = "cualquiera";
$dbname = "panteras2";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asesorías Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
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
                            <div class="input-group flex-wrap" style="max-width: 400px;">
                                <span class="input-group-text input-group-text-custom">Inicio:</span>
                                <input type="text form-control" class="input-custom me-2" id="fechaInicio">
                            </div>
                            <button class="btn btn-custom mx-2" type="button" id="calendarioInicio">
                                <i class="bi bi-calendar-x-fill"></i>
                            </button>
                            <div class="input-group flex-wrap" style="max-width: 400px;">
                                <span class="input-group-text input-group-text-custom ms-2">Fin:</span>
                                <input type="text form-control" class="input-custom me-2" id="fechaFin">
                            </div>
                            <button class="btn btn-custom mx-2" type="button" id="calendarioFin">
                                <i class="bi bi-calendar-x-fill"></i>
                            </button>
                            <div class="input-group flex-wrap" style="max-width: 300px; width:100%;">
                                <span class="input-group-text input-group-text-custom ms-2">Talent:</span>
                                <select class="form-select form-select-custom form-control" id="talent">
                                    <option selected value="0">Seleccione un miembro</option>
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
                            <div class="input-group flex-wrap" style="max-width: 400px; width:100%">
                                <span class="input-group-text input-group-text-custom">Sede:</span>
                                <select class="form-select form-select-custom form-control" id="sede">
                                    <option selected value="0">Todas las sedes</option>
                                    <option value="1">Mexico</option>
                                    <option value="4">Aguascalientes</option>
                                    <option value="5">Guadalajara</option>
                                    <option value="6">Ciudad UP</option>
                                    <option value="1007">Sin Sede</option>
                                </select>
                            </div>
                            <div class="input-group ms-4 flex-wrap" style="max-width: 400px; width:100%;">
                                <span class="input-group-text input-group-text-custom">Categorías:</span>
                                <select class="form-select form-select-custom form-control" id="categoria">
                                    <option selected value="0">Seleccione una Categoría</option>
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

        <!-- Filtros activados -->
        <div class="row my-3 filters-summary">
            <div class="d-flex flex-row row m-auto my-2">
                <div class="col-md-2">
                    Intervalo de Fechas: 
                </div>
                <div class="col-md-5" id="filterList-fechaInicio">
                    <span class="me-2">DESDE:</span>
                </div>
                <div class="col-md-5" id="filterList-fechaFin">
                    <span class="me-2">HASTA:</span>
                </div>
            </div>
            <div class="row m-auto my-2" id="filterContainer-talent">
                <div class="col-md-10">Talent:</div>
                <div class="col-md-10" id="filterList-talent"></div>
            </div>
            <div class="row m-auto my-2" id="filterContainer-sede">
                <div class="col-md-10">Sede:</div>
                <div class="col-md-10" id="filterList-sede"></div>
            </div>
            <div class="row m-auto my-2" id="filterContainer-categoria">
                <div class="col-md-10">Categoria:</div>
                <div class="col-md-10" id="filterList-categoria"></div>
            </div>
        </div>

        

        <!-- Tab headers -->
        <div class="my-3">
            <!-- Nav tabs -->
            <ul class="nav" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="resultados-tab" data-bs-toggle="tab" href="#resultados" role="tab">Resultados</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="categorias-tab" data-bs-toggle="tab" href="#categorias" role="tab">Categorias</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="asesores-tab" data-bs-toggle="tab" href="#asesores" role="tab">Asesores</a>
            </li>
        </ul>

        <!-- Tab content -->
        <div class="tab-content my-2" id="myTabContent">
            <div class="tab-pane show active" id="resultados" role="tabpanel">
                <table class="table table-striped table-dark" id="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Correo</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Duracion</th>
                            <th scope="col">Categoria</th>
                            <th scope="col">Asesor</th>
                        </tr>
                    </thead>
                    <tbody id="tableBodyResultados">
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="categorias" role="tabpanel">
                <table class="table table-striped table-dark" id="table">
                    <thead>
                        <tr>
                            <th scope="col">Key</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Sesiones</th>
                            <th scope="col">Profesores</th>
                            <th scope="col">Total Horas Prof</th>
                            <th scope="col">Total Horas TALENT</th>
                            <th scope="col">Duracion Media Prof</th>
                            <th scope="col">Duracion Media TALENT</th>
                        </tr>
                    </thead>
                    <tbody id="tableBodyCategorias">
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="asesores" role="tabpanel">
                <table class="table table-striped table-dark" id="table">
                    <thead>
                        <tr>
                            <th scope="col">Correo</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Sesiones</th>
                            <th scope="col">Total Horas TALENT</th>
                            <th scope="col">Duracion Media Sesion</th>
                            <th scope="col">% Horas Prof</th>
                        </tr>
                    </thead>
                    <tbody id="tableBodyAsesores">
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="script.js"></script>
</body>

</html>