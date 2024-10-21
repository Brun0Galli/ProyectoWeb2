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