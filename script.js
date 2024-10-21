
$(document).ready(function() {

    var filtros = {};
    var fechas = {};
    var categorias = {};

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
    $('#categoria').on('change', function() {
        var categoriaText = $('#categoria option:selected').text();
        if (categoriaText !== "Seleccione una Categoría") {
            filtros["Categoría"] = categoriaText;
            actualizarResumen();
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
        var fechaInicio = $('#fechaInicio').val();
        var fechaFin = $('#fechaFin').val();
        var categoria = $('#categoria').val();
        console.log(categoria)
        if (talentID !== "0") { // Solo ejecutar si se selecciona un talent válido
            // Hacer la solicitud AJAX para ejecutar los dos queries
            $.ajax({
                url: 'actualizar_estadisticas.php', // Archivo PHP que ejecutará los queries
                type: 'POST',
                dataType: 'json', // Esperamos recibir un JSON con ambas respuestas
                data: {
                    talent_id: talentID,
                    fecha_inicio: fechaInicio,
                    fecha_fin: fechaFin,
                    categoria_id: categoria

                },
                success: function(response) {
                    // Actualizar la tira de resultados con las estadísticas
                    actualizarResultadosTira(response.estadisticas);
                    console.log(response)
                    // Insertar los resultados de la búsqueda en la tabla
                    let html = '';
                    response.asesorias.forEach(function(asesoria) {
                        html += '<tr>';
                        html += '<td>' + asesoria.asesoria_id + '</td>';
                        html += '<td>' + asesoria.Correo + '</td>';
                        html += '<td>' + asesoria.Fecha + '</td>';
                        html += '<td>' + asesoria.Duracion + '</td>';
                        html += '<td>' + asesoria.categoria + '</td>';
                        html += '<td>' + asesoria.asesor + '</td>';
                        html += '</tr>';
                    });
                    $('#resultados tbody').html(html);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("Error: " + textStatus + " " + errorThrown);
                }
            });
        } else {
            alert("Por favor, selecciona un Talent válido.");
        }
    });

    function actualizarResultadosTira(data) {
        $('#statSession').text(data.cantidad_sesiones);
        $('#statHrTotal').text(convertMinutesToHHMM(data.total_horas_alumnos)); // Total Hrs Alumnos
        $('#statDurMedia').text(convertMinutesToHHMM(data.duracion_media_sesion)); // Duración media de sesión
        $('#statHrTotalTalent').text(convertMinutesToHHMM(data.total_horas_talent)); // Total Hrs Talent
        $('#statAlumnosAtendidos').text(data.cantidad_alumnos_unicos); // Profesores (Alumnos atendidos)
    }
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
