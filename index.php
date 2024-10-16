
<?php

    include 'components/sql.php';

    $productos = [];
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="style.css" rel="stylesheet">
        <script src="script.js"></script>
    </head>
    <body>
        <div class="container vh-10 vw-100">
            <div class="d-flex justify-content-around my-4">
                <div id="filters" class="filters-gap justify-content-left flex-column">
                    <div id="row-1" class="filters-gap d-flex justify-content-left">
                        <div class="filter-box box-med">
                            <span class="t-accent">Sede:</span>
                            <span>Fecha</span>
                        </div>
                    </div>
                    <div id="row-2" class="filters-gap d-flex justify-content-left">
                        <div class="filter-box box-std">
                            <span class="t-accent">Inicio:</span>
                            <span class="">Fecha</span>
                        </div>
                        <div class="btn button-box calendar-box"><img src="calendar-icon.png" class="icon"></div>
                        <div class="filter-box box-std">
                            <span class="t-accent">Fin:</span>
                            <span>Fecha</span>
                        </div>
                        <div class="btn button-box calendar-box"><img src="calendar-icon.png" class="icon"></div>
                    </div>
                    <div id="row-3" class="filters-gap d-flex justify-content-left">
                        <div class="filter-box box-med">
                            <span class="t-accent">Talent:</span>
                            <span>Fecha</span>
                        </div>
                        <div class="filter-box box-med">
                            <span class="t-accent">Categorias:</span>
                            <span>Fecha</span>
                        </div>
                    </div>
                </div>
                <div id="buttons" class="d-flex justify-content-right flex-column">
                    <button class="btn button-box button-std" onclick="limpiar()">
                        <span>Limpiar</span>
                        <img src="search-icon.png" class="icon">
                    </button>
                    <button class="btn button-box bg-accent button-std" onclick="buscar()">
                        <img src="trash-icon.png" class="icon">
                        <span>Trash</span>
                    </button>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    </body>
</html>