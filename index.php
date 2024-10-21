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

    <style>
        body {
            background-color: #1e1e1e;
            color: #fff;
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

        .filters-summary {
            width: auto;
            background-color: #333;
            color: white;
            border-radius: 20px;
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
        background-color: #6c757d; /* Color del botón */
        border: none; /* Sin borde */
        border-radius: 10px; /* Bordes redondeados */
        padding: 0.375rem 0.75rem; /* Espaciado */
        color: white;
        }
        .btn-custom:hover {
        background-color: #52595f;
        }
        .btn-custom:hover:active {
        background-color: #52595f;
        }
        .btn-custom2 {
        background-color: #635e29; /* Color del botón */
        border: none; /* Sin borde */
        border-radius: 10px; /* Bordes redondeados */
        padding: 0.375rem 0.75rem; /* Espaciado */
        color: white;
        }
        .btn-custom2:hover {
        background-color: #5a5418; /* Color del botón */
        }
        .btn-custom2:hover:active {
        background-color: #5a5418; /* Color del botón */
        }
        .btn-filter {
        background-color: #6c757d; /* Color del botón */
        border: none; /* Sin borde */
        padding: 0.2rem 0.75rem; /* Espaciado */
        color: white;
        }

        .btn-custom i {
            font-size: 1.2rem;
        }

        .daterangepicker {
            background-color: #333;
            /* Fondo oscuro */
            color: white;
            /* Texto blanco */
        }

        .daterangepicker .calendar-table {
            color: #807834;
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

        .nav-link{
            color:white;
        }
        .nav-link:hover{
            color:#807834;
        }
        .nav-link.active{
            color:#807834;
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
                            <th scope="col">ID</td>
                            <th scope="col">Correo</td>
                            <th scope="col">Fecha</td>
                            <th scope="col">Duracion</td>
                            <th scope="col">Categoria</td>
                            <th scope="col">Asesor</td>
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
                            <th scope="col">Key</td>
                            <th scope="col">Nombre</td>
                            <th scope="col">Sesiones</td>
                            <th scope="col">Profesores</td>
                            <th scope="col">Total Horas Prof</td>
                            <th scope="col">Total Horas TALENT</td>
                            <th scope="col">Duracion Media Prof</td>
                            <th scope="col">Duracion Media TALENT</td>
                        </tr>
                    </thead>
                    <tbody id="tableBodyCategorias">
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="asesores" role="tabpanel">
                <h3>WIP</h3>
            </div>
        </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        $(document).ready(function() {
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
            $('#buscar').on('click', function(e) {
                e.preventDefault();
                $("#tableBodyResultados").html("");
                $("#tableBodyCategorias").html("");
                ResultadoTab();
                CategoriasTab();
                SummaryBox();
            });

            // Limpiar
            $('#limpiarCampos').on('click', function() {
                $('#filterForm')[0].reset();
                $('#filterList-fechaInicio').html("");
                $('#filterList-fechaInicio').hide();
                $('#filterList-fechaFin').html("");
                $('#filterList-fechaFin').hide();
                $("#filterList-talent").html("");
                $('#filterContainer-talent').hide();
                $("#filterList-sede").html("");
                $('#filterContainer-sede').hide();
                $("#filterList-categoria").html("");
                $('#filterContainer-categoria').hide();
            });

            //Filtros
            //Fecha
            $('#fechaInicio').on("change keyup paste",function () {
                if($('#fechaInicio').val() != ""){
                    $('#filterList-fechaInicio').show();
                    let innerValue = '<span class="me-2">DESDE:</span>' + "<span class='me-3'>"+ $('#fechaInicio').val() +"</span>";
                    $('#filterList-fechaInicio').html(innerValue);
                }else{
                    $('#filterList-fechaInicio').hide();
                }
            });

            $('#fechaFin').on("change keyup paste",function () {
                if($('#fechaFin').val() != ""){
                    $('#filterList-fechaFin').show();
                    let innerValue = '<span class="me-2">HASTA:</span>' + "<span class='me-3'>"+ $('#fechaFin').val() +"</span>";
                    $('#filterList-fechaFin').html(innerValue);
                }else{
                    $('#filterList-fechaFin').hide();
                }
            });

            $('#filterList-fechaInicio').hide();
            $('#filterList-fechaFin').hide();
            $("#filterContainer-"+"talent").hide();
            $("#filterContainer-"+"sede").hide();
            $("#filterContainer-"+"categoria").hide();
            listenToFilter("talent");
            listenToFilter("sede");
            listenToFilter("categoria");

        });
        function ResultadoTab(){
            filters = getFilters();
            filteryQuery = "";
            console.log(filters);
            
            if(filters["sede"].length > 0){
                filteryQuery += " AND asesoria.id_Sede IN ("+filters["sede"]+")";
            }
            if(filters["fechaInicio"] != ''){
                filteryQuery += " AND asesoria.Fecha >= '"+filters["fechaInicio"]+"'";
            }
            if(filters["fechaFin"] != ''){
                filteryQuery += " AND asesoria.Fecha <= '"+filters["fechaFin"]+"'";
            }
            if(filters["talent"].length > 0){
                filteryQuery += " AND asesor.ID IN ("+filters["talent"]+")";
            }
            if(filters["categoria"].length > 0){
                filteryQuery += " AND categoria.ID IN ("+filters["categoria"]+")";
            }
            let query = "SELECT asesoria.ID, asesoria.Correo AS Correo, " +
            "asesoria.Fecha, asesoria.duracion AS Duracion, " +
            "categoria.Llave AS Categoria, asesor.nombre AS Asesor " +
            "FROM asesor " +
            "INNER JOIN asesoria_asesor ON asesor.ID = asesoria_asesor.id_Asesor " +
            "INNER JOIN asesoria ON asesoria_asesor.id_Asesoria = asesoria.ID " +
            "INNER JOIN categoria ON categoria.ID = asesoria.id_Categoria " +
            "WHERE 1=1" + filteryQuery + " " +
            "ORDER BY asesoria.Fecha DESC;";
            console.log(query);

            $.ajax({
                    url: 'components/buscar.php',
                    method: 'POST',
                    data: {
                        query: query
                    },
                    success: function(response) {
                        //console.log(response);
                        if (response != "0"){
                            response = JSON.parse(response);
                            for(let i = 0; i < response.length; i++){
                                //console.log(response[i]);
                                let element = "<tr>"+
                                "<td>"+response[i]["ID"]+"</td>"+
                                "<td>"+response[i]["Correo"]+"</td>"+
                                "<td>"+response[i]["Fecha"]+"</td>"+
                                "<td>"+response[i]["Duracion"]+"</td>"+
                                "<td>"+response[i]["Categoria"]+"</td>"+
                                "<td>"+response[i]["Asesor"]+"</td>"+
                                "</tr>";
                                // console.log(element);
                                $("#tableBodyResultados").append(element);
                            }
                        }
                    }
            });
        }

        function CategoriasTab(){
            filters = getFilters();
            var filtersQuery = {};
            console.log(filters);
            
            if(filters["sede"].length > 0){
                filtersQuery["sede"] = " AND asesoria.id_Sede IN ("+filters["sede"]+")";
            }else{
                filtersQuery["sede"] = "";
            }
            if(filters["fechaInicio"] != ''){
                filtersQuery["fechaInicio"] = " AND asesoria.Fecha >= '"+filters["fechaInicio"]+"'";
            }else{
                filtersQuery["fechaInicio"] = "";
            }
            if(filters["fechaFin"] != ''){
                filtersQuery["fechaFin"] = " AND asesoria.Fecha <= '"+filters["fechaFin"]+"'";
            }else{
                filtersQuery["fechaFin"] = "";
            }
            if(filters["talent"].length > 0){
                filtersQuery["talent"] = " AND asesoria_asesor.id_Asesor IN ("+filters["talent"]+")";
            }else{
                filtersQuery["talent"] = "";
            }
            if(filters["categoria"].length > 0){
                filtersQuery["categoria"] = " AND categoria.ID IN ("+filters["categoria"]+")";
            }else{
                filtersQuery["categoria"] = "";
            }
            let query =`
            WITH UniqueAsesores AS (
            SELECT 
                    asesoria.id_Categoria,
                    asesoria.ID,
                    SUM(Duracion) AS Unique_Durations,
                    COUNT(DISTINCT asesor.ID) AS Unique_Count
                FROM 
                    asesoria
                LEFT JOIN asesoria_asesor ON asesoria.ID = asesoria_asesor.id_Asesoria
                LEFT JOIN asesor ON asesoria_asesor.id_Asesor = asesoria.ID
                GROUP BY 
                    asesoria.ID
            )
            SELECT Llave AS "Key", categoria.Nombre, 
            COUNT(asesoria.id_Categoria) AS Sesiones,
            COUNT(DISTINCT asesoria.Correo) AS Profesores,
                TIME_FORMAT(SEC_TO_TIME(IFNULL(SUM(asesoria.Duracion) * 60, 0)), '%H:%i') AS ProfesorHoras,
                TIME_FORMAT(SEC_TO_TIME(IFNULL(SUM(UniqueAsesores.Unique_Durations) * 60, 0)), '%H:%i') AS TalentHoras,
                TIME_FORMAT(
                    SEC_TO_TIME(IFNULL(SUM(asesoria.Duracion) * 60 / NULLIF(COUNT(asesoria.id_Categoria), 0), 0)), 
                    '%H:%i'
                ) AS ProfesoresMedia,
                TIME_FORMAT(
                    SEC_TO_TIME(IFNULL(SUM(UniqueAsesores.Unique_Durations) * 60 / NULLIF(COUNT(asesoria.id_Categoria), 0), 0)), 
                    '%H:%i'
                ) AS TalentMedia


            FROM categoria
            INNER JOIN asesoria ON categoria.ID = asesoria.id_Categoria`+filtersQuery["sede"]+``+filtersQuery["categoria"]+``+filtersQuery["fechaInicio"]+``+filtersQuery["fechaFin"]+`
            INNER JOIN asesoria_asesor ON asesoria.ID = asesoria_asesor.id_Asesoria`+filtersQuery["talent"]+`
            LEFT JOIN asesor ON asesoria_asesor.id_Asesor = asesoria.ID
            LEFT JOIN UniqueAsesores ON asesoria.ID = UniqueAsesores.ID

            GROUP BY Llave;`
            console.log(query);

            $.ajax({
                    url: 'components/buscar.php',
                    method: 'POST',
                    data: {
                        query: query
                    },
                    success: function(response) {
                        //console.log(response);
                        if (response != "0"){
                            response = JSON.parse(response);
                            for(let i = 0; i < response.length; i++){
                                //console.log(response[i]);
                                let element = "<tr>"+
                                "<td>"+response[i]["Key"]+"</td>"+
                                "<td>"+response[i]["Nombre"]+"</td>"+
                                "<td>"+response[i]["Sesiones"]+"</td>"+
                                "<td>"+response[i]["Profesores"]+"</td>"+
                                "<td>"+response[i]["ProfesorHoras"]+"</td>"+
                                "<td>"+response[i]["TalentHoras"]+"</td>"+
                                "<td>"+response[i]["ProfesoresMedia"]+"</td>"+
                                "<td>"+response[i]["TalentMedia"]+"</td>"+
                                "</tr>";
                                // console.log(element);
                                $("#tableBodyCategorias").append(element);
                            }
                        }
                    }
            });
        }

        function SummaryBox(){
            filters = getFilters();
            var filtersQuery = {};
            console.log(filters);
            
            if(filters["sede"].length > 0){
                filtersQuery["sede"] = " AND asesoria.id_Sede IN ("+filters["sede"]+")";
            }else{
                filtersQuery["sede"] = "";
            }
            if(filters["fechaInicio"] != ''){
                filtersQuery["fechaInicio"] = " AND asesoria.Fecha >= '"+filters["fechaInicio"]+"'";
            }else{
                filtersQuery["fechaInicio"] = "";
            }
            if(filters["fechaFin"] != ''){
                filtersQuery["fechaFin"] = " AND asesoria.Fecha <= '"+filters["fechaFin"]+"'";
            }else{
                filtersQuery["fechaFin"] = "";
            }
            if(filters["talent"].length > 0){
                filtersQuery["talent"] = " AND asesoria_asesor.id_Asesor IN ("+filters["talent"]+")";
            }else{
                filtersQuery["talent"] = "";
            }
            if(filters["categoria"].length > 0){
                filtersQuery["categoria"] = " AND categoria.ID IN ("+filters["categoria"]+")";
            }else{
                filtersQuery["categoria"] = "";
            }
            let query =`
            WITH UniqueAsesores AS (
            SELECT 
                    asesoria.id_Categoria,
                    asesoria.ID,
                    SUM(Duracion) AS Unique_Durations,
                    COUNT(DISTINCT asesor.ID) AS Unique_Count
                FROM 
                    asesoria
                LEFT JOIN asesoria_asesor ON asesoria.ID = asesoria_asesor.id_Asesoria
                LEFT JOIN asesor ON asesoria_asesor.id_Asesor = asesoria.ID
                GROUP BY 
                    asesoria.ID
            )
            SELECT Llave AS "Key", categoria.Nombre, 
            COUNT(asesoria.id_Categoria) AS Sesiones,
            COUNT(DISTINCT asesoria.Correo) AS Profesores,
                TIME_FORMAT(SEC_TO_TIME(IFNULL(SUM(asesoria.Duracion) * 60, 0)), '%H:%i') AS ProfesorHoras,
                TIME_FORMAT(SEC_TO_TIME(IFNULL(SUM(UniqueAsesores.Unique_Durations) * 60, 0)), '%H:%i') AS TalentHoras,
                TIME_FORMAT(
                    SEC_TO_TIME(IFNULL(SUM(asesoria.Duracion) * 60 / NULLIF(COUNT(asesoria.id_Categoria), 0), 0)), 
                    '%H:%i'
                ) AS ProfesoresMedia,
                TIME_FORMAT(
                    SEC_TO_TIME(IFNULL(SUM(UniqueAsesores.Unique_Durations) * 60 / NULLIF(COUNT(asesoria.id_Categoria), 0), 0)), 
                    '%H:%i'
                ) AS TalentMedia


            FROM categoria
            INNER JOIN asesoria ON categoria.ID = asesoria.id_Categoria`+filtersQuery["sede"]+``+filtersQuery["categoria"]+``+filtersQuery["fechaInicio"]+``+filtersQuery["fechaFin"]+`
            INNER JOIN asesoria_asesor ON asesoria.ID = asesoria_asesor.id_Asesoria`+filtersQuery["talent"]+`
            LEFT JOIN asesor ON asesoria_asesor.id_Asesor = asesoria.ID
            LEFT JOIN UniqueAsesores ON asesoria.ID = UniqueAsesores.ID;`
            console.log(query);

            $.ajax({
                    url: 'components/buscar.php',
                    method: 'POST',
                    data: {
                        query: query
                    },
                    success: function(response) {
                        //console.log(response);
                        if (response != "0"){
                            response = JSON.parse(response);
                            responseStats = {
                                Sesiones: 0,
                                ProfesorHoras: '00:00',
                                ProfesoresMedia: '00:00',
                                TalentHoras: '00:00',
                                Profesores: 0
                            };
                            let totalMinutesProfesorHoras = 0;
                            let totalMinutesProfesoresMedia = 0;
                            let totalMinutesTalentHoras = 0;
                            for(let i = 0; i < response.length; i++){
                                responseStats["Sesiones"] = parseFloat(response[i]["Sesiones"]);
                                responseStats["ProfesorHoras"] = response[i]["ProfesorHoras"];
                                responseStats["ProfesoresMedia"] = response[i]["ProfesoresMedia"];
                                responseStats["TalentHoras"] = response[i]["TalentHoras"];
                                responseStats["Profesores"] = parseFloat(response[i]["Profesores"]);
                            }
                        }
                        console.log(responseStats);
                        $('#statSession').html(responseStats["Sesiones"]);
                        $('#statHrTotal').html(responseStats["ProfesorHoras"]);
                        $('#statDurMedia').html(responseStats["ProfesoresMedia"]);
                        $('#statHrTotalTalent').html(responseStats["TalentHoras"]);
                        $('#statAlumnosAtendidos').html(responseStats["Profesores"]);
                    }
            });
        }

        function getFilters(){
            var filters = {};
            filters["sede"] = $('#filterList-sede span').map(function () {return $(this).attr('value')}).get();
            filters["talent"] = $('#filterList-talent span').map(function () {return $(this).attr('value')}).get();
            filters["categoria"] = $('#filterList-categoria span').map(function () {return $(this).attr('value')}).get();
            filters["fechaInicio"] = $('#fechaInicio').val();
            filters["fechaFin"] = $('#fechaFin').val();

            console.log(filters);
            return filters;
        }

        function deleteSelfFilter(button){
            if($(button).parent().children().length <= 1){
                $(button).parent().parent().hide();
            }
            $(button).remove();
        }

        function listenToFilter(category){
            $('#'+category).on("change keyup paste",function () {
                    $("#filterContainer-"+category).show();

                    let element = '<button class="btn-filter me-3" onclick="deleteSelfFilter(this)"><span value="'+
                    $('#'+category).val()+'">'+
                    $('#'+category+' option:selected').text()+
                    '</span><i class="bi bi-trash ms-3"></i></button>';

                    $('#filterList-'+category).append(element);
                    $('#'+category).val("0"); 
            });
        }

        ResultadoTab();
        CategoriasTab();
        SummaryBox();
    </script>
</body>

</html>