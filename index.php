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

        <div class="resultados-tira" id="resultados">
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
        <hr>

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

                // Buscar

                // Limpiar
                $('#limpiarCampos').on('click', function() {
                    $('#filterForm')[0].reset(); // Limpiar los campos del formulario
                    filtros = {};
                    fechas = {}; // Vaciar el objeto de filtros
                    actualizarResumen();
                    actualizarFechas(); // Actualizar el resumen visual
                    $('#resultados').empty();
                });
            });
        </script>
</body>

</html>