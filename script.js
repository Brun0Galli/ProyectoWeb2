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
        $("#tableBodyAsesores").html("");
        ResultadoTab();
        CategoriasTab();
        AsesoresTab();
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
    //console.log(query);

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
    //console.log(query);

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

function AsesoresTab(){
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
        filtersQuery["talent"] = " AND UniqueAsesores.id_Asesor IN ("+filters["talent"]+")";
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
            asesor.ID AS id_Asesor,
            asesor.Correo AS Correo,
            asesor.Nombre AS Nombre,
            SUM(asesoria.Duracion) AS TotalDurationPerAdvisor,
            COUNT(DISTINCT asesoria.ID) AS TotalSessions
        FROM 
            asesor
        LEFT JOIN asesoria_asesor ON asesor.ID = asesoria_asesor.id_Asesor
        LEFT JOIN asesoria ON asesoria_asesor.id_Asesoria = asesoria.ID
        GROUP BY asesor.ID
    ),
    TotalDurations AS (
        SELECT 
            SUM(TotalDurationPerAdvisor) AS TotalDuration
        FROM UniqueAsesores
        WHERE
        1=1 `+filtersQuery["talent"]+`
    )
    SELECT 
        UniqueAsesores.Correo,
        UniqueAsesores.Nombre,
        UniqueAsesores.TotalSessions AS Sesiones,

        TIME_FORMAT(
            SEC_TO_TIME(IFNULL(UniqueAsesores.TotalDurationPerAdvisor * 60, 0)), '%H:%i'
        ) AS TalentHoras,

        TIME_FORMAT(
            SEC_TO_TIME(
                IFNULL(UniqueAsesores.TotalDurationPerAdvisor * 60 / NULLIF(UniqueAsesores.TotalSessions, 0), 0)
            ), '%H:%i'
        ) AS DuracionMediaSesion,

        ROUND(
            (UniqueAsesores.TotalDurationPerAdvisor / (SELECT TotalDuration FROM TotalDurations)) * 100, 2
        ) AS PorcentajeTiempo

    FROM UniqueAsesores

    WHERE
    1=1 `+filtersQuery["talent"]+`;
    `
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
                        "<td>"+response[i]["Correo"]+"</td>"+
                        "<td>"+response[i]["Nombre"]+"</td>"+
                        "<td>"+response[i]["Sesiones"]+"</td>"+
                        "<td>"+response[i]["TalentHoras"]+"</td>"+
                        "<td>"+response[i]["DuracionMediaSesion"]+"</td>"+
                        "<td>"+response[i]["PorcentajeTiempo"]+"%</td>"+
                        "</tr>";
                        // console.log(element);
                        $("#tableBodyAsesores").append(element);
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
    //console.log(query);

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
AsesoresTab();
SummaryBox();