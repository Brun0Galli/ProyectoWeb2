$(document).ready(function() {
    var filtros = {
        Talent: [],
        Categoria: [],
        CategoriaID1: []
    };
    var fechas = {};

    cargarFiltrosDesdeCache();

    function cargarFiltrosDesdeCache() {
        filtros.Categoria = JSON.parse(localStorage.getItem('Categoria')) || [];
        filtros.Talent = JSON.parse(localStorage.getItem('TalentID')) || [];
        filtros.CategoriaID1 = JSON.parse(localStorage.getItem('CategoriaID1')) || [];
        actualizarResumen();
        actualizarFechas();
    }

    function guardarFiltrosEnCache() {
        localStorage.setItem('Categoria', JSON.stringify(filtros.Categoria));
        localStorage.setItem('TalentID', JSON.stringify(filtros.Talent));
        localStorage.setItem('CategoriaID1', JSON.stringify(filtros.CategoriaID1));
    }

    function actualizarResumen() {
        var resumen = $("#filtrosSeleccionados");
        resumen.empty();

        filtros.Talent.forEach(function(talent) {
            resumen.append('<span class="filtro-tag">' + talent + ' <button class="remove-tag" data-type="Talent" data-value="' + talent + '">x</button></span> ');
        });

        filtros.Categoria.forEach(function(categoria) {
            resumen.append('<span class="filtro-tag">' + categoria + ' <button class="remove-tag" data-type="Categoria" data-value="' + categoria + '">x</button></span> ');
        });
    }

    $('#filtrosSeleccionados').on('click', '.remove-tag', function() {
        var type = $(this).data('type');
        var value = $(this).data('value');

        if (type === 'Categoria') {
            var index = filtros['Categoria'].indexOf(value);
            if (index > -1) {
                filtros['Categoria'].splice(index, 1);
                filtros['CategoriaID1'].splice(index, 1);
            }
        } else {
            filtros[type] = filtros[type].filter(function(item) {
                return item !== value;
            });
        }

        actualizarResumen();
        guardarFiltrosEnCache();
    });

    function actualizarFechas() {
        var resumen = $("#fechasSeleccionadas");
        resumen.empty();
        $.each(fechas, function(clave, valor) {
            resumen.append('<span class="filtro-tag">' + clave + ': ' + valor + '</span>');
        });
    }

    $('#talent').on('change', function() {
        var talentText = $('#talent option:selected').text();
        if (talentText !== "Seleccione un miembro" && !filtros.Talent.includes(talentText)) {
            filtros.Talent.push(talentText);
            actualizarResumen();
            guardarFiltrosEnCache();
        }
    });

    $('#categoria').on('change', function() {
        var categoriaText = $('#categoria option:selected').text();
        var categoriaID = $('#categoria option:selected').val();
        if (categoriaText !== "Seleccione una Categoría" && !filtros.Categoria.includes(categoriaText)) {
            filtros.Categoria.push(categoriaText);
            filtros.CategoriaID1.push(categoriaID);
            actualizarResumen();
            guardarFiltrosEnCache();
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

    $('#limpiarCampos').on('click', function() {
        filtros = {
            Talent: [],
            Categoria: [],
            CategoriaID1: []
        };
        fechas = {};
        localStorage.removeItem('Categoria');
        localStorage.removeItem('TalentID');
        localStorage.removeItem('CategoriaID1');
        actualizarResumen();
        actualizarFechas();
        $('#filterForm')[0].reset();
        $('#statSession').html('0');
        $('#statHrTotal').html('00:00');
        $('#statDurMedia').html('00:00');
        $('#statHrTotalTalent').html('00:00 ');
        $('#statAlumnosAtendidos').html('0');
    });

    function convertMinutesToHHMM(minutes) {
        var hours = Math.floor(minutes / 60);
        var mins = Math.floor(minutes % 60);
        return hours + ':' + (mins < 10 ? '0' : '') + mins;
    }
    function cargarAsesores() {
        var talentID = JSON.parse(localStorage.getItem('TalentID')) || []; // Obtener TalentID desde localStorage
    
        $.ajax({
            url: 'asesores.php', // Archivo PHP que ejecutará el query
            type: 'POST',
            dataType: 'html', // Esperamos un HTML de respuesta para mostrar en la tabla
            data: {
                talent_id: JSON.stringify(talentID) // Enviar TalentID como JSON
            },
            success: function(response) {
                $('#asesores tbody').html(response); // Insertar la respuesta en la tabla
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error en la solicitud AJAX: " + textStatus + " " + errorThrown);
            }
        });
    }
    function cargarCategorias() {
        var categoriaID1 = JSON.parse(localStorage.getItem('CategoriaID1')) || [];
        $.ajax({
            url: 'categorias.php',
            type: 'POST',
            dataType: 'html',
            data:{
                categoria_id: JSON.stringify(categoriaID1)
            },
            success: function(response) {
                $('#categorias tbody').html(response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error en la solicitud AJAX: " + textStatus + " " + errorThrown);
            }
        });
    }

    $('#buscar').on('click', function(e) {
        e.preventDefault();

        var talentID = filtros.Talent;
        var fechaInicio = $('#fechaInicio').val();
        var fechaFin = $('#fechaFin').val();
        var categoria = filtros.CategoriaID1;

        if (talentID.length > 0) { 
            cargarCategorias();
            cargarAsesores();
            
            $.ajax({
                url: 'actualizar_estadisticas.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    talent_id: JSON.stringify(talentID),
                    fecha_inicio: fechaInicio,
                    fecha_fin: fechaFin,
                    categoria_id: JSON.stringify(categoria)
                },
                success: function(response) {
                    actualizarResultadosTira(response.estadisticas);
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
        $('#statHrTotal').text(convertMinutesToHHMM(data.total_horas_alumnos));
        $('#statDurMedia').text(convertMinutesToHHMM(data.duracion_media_sesion));
        $('#statHrTotalTalent').text(convertMinutesToHHMM(data.total_horas_talent));
        $('#statAlumnosAtendidos').text(data.cantidad_alumnos_unicos);
    }
});

// Lógica de navegación entre pestañas
document.addEventListener("DOMContentLoaded", function() {
    const links = document.querySelectorAll(".nav-menu a");
    const sections = document.querySelectorAll(".section");

    function showSection(hash) {
        sections.forEach(section => section.classList.remove("active"));
        links.forEach(link => link.classList.remove("active"));

        const targetSection = document.querySelector(hash);
        const targetLink = document.querySelector(`a[href='${hash}']`);

        if (targetSection) targetSection.classList.add("active");
        if (targetLink) targetLink.classList.add("active");
    }

    const currentHash = window.location.hash || "#resultados";
    showSection(currentHash);

    links.forEach(link => {
        link.addEventListener("click", function(event) {
            event.preventDefault();
            const hash = this.getAttribute("href");

            window.history.pushState(null, null, hash);
            showSection(hash);
        });
    });

    window.addEventListener("popstate", function() {
        showSection(window.location.hash || "#resultados");
    });
});
