<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Agrega Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha384-d/1s7lKGOw9jZc3zL6syb5VMFLX4rOzx2zD0dcKVXeIIboBw9QdI5ZF5VO3Ppz8" crossorigin="anonymous">

    <title>Lista de Documentos</title>
    <style>
        /* Estilo para aumentar el tamaño del radio del checkbox */
        .custom-checkbox .custom-control-label::before {
            width: 1.5rem;
            /* Aumenta el ancho */
            height: 1.5rem;
            /* Aumenta la altura */
        }

        .documento-card {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 20px;
        }

        /* Estilo para el icono */
        .icono-archivo {
            font-size: 3rem;
            /* Tamaño del icono */
            margin-bottom: 10px;
            /* Espaciado inferior */
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <h1 class="mt-3">Lista de Documentos</h1>
        <!-- Agrega este formulario en tu archivo file_view.php -->
        <form action="<?php echo site_url('file/subir_archivo'); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="archivo">Selecciona un archivo:</label>
                <input type="file" class="form-control" name="archivo" id="archivo" required>
            </div>

            <button type="submit" class="btn btn-primary mb-2 float-right">Subir archivo</button>
        </form>


        <!-- Agrega este formulario en tu file_view.php -->
        <form id="operacionesForm" action="<?= site_url('file/ejecutar_operacion') ?>" method="post">
            <input type="hidden" name="operacion" id="operacion" value="">
            <input type="hidden" name="values_files" id="values_files" value="">
            <button type="button" class="btn btn-primary mb-2 float-right" id="encriptarBtn" onclick="setOperacion('encriptar')">Encriptar</button>
            <button type="button" class="btn btn-primary mb-2 float-right" id="desencriptarBtn" onclick="setOperacion('desencriptar')">Desencriptar</button>
            <button type="button" class="btn btn-primary mb-2 float-right" id="desencriptarBtn" onclick="setOperacion('descargar')">Descargar</button>
            <br>
            <div class="row">
            <?php if(isset($documento['ID'])){ ?>
                <?php foreach ($documentos as $documento) : ?>
                    <div class="col-md-4">
                        <div class="documento-card">
                            <div class="custom-control custom-checkbox">
                                <!-- Utiliza un input hidden para almacenar los IDs de los documentos seleccionados -->
                                <input type="hidden" name="documentos_seleccionados[]" value="<?= $documento['ID'] ?>">
                                <input type="checkbox" class="custom-control-input documentoCheckbox" id="checkbox<?= $documento['ID'] ?>">
                                <label class="custom-control-label" for="checkbox<?= $documento['ID'] ?>"></label>
                            </div>
                            <!-- Incorpora la lógica en el icono directamente -->
                            <?php
                            $iconClass = '';
                            switch ($documento['TipoDocumento']) {
                                case 'PDF':
                                    $iconClass = 'https://cdn-icons-png.flaticon.com/256/337/337946.png';
                                    break;
                                case 'DOCX':
                                    $iconClass = 'https://cdn.icon-icons.com/icons2/112/PNG/512/word_18896.png';
                                    break;
                                case 'PNG':
                                    $iconClass = 'https://cdn-icons-png.flaticon.com/512/2694/2694755.png';
                                    break;
                                case 'JPG':
                                    $iconClass = 'https://cdn-icons-png.flaticon.com/512/2694/2694755.png';
                                    break;
                                case 'JPEG':
                                    $iconClass = 'https://cdn-icons-png.flaticon.com/512/2694/2694755.png';
                                    break;
                                case 'GIF':
                                    $iconClass = 'https://cdn-icons-png.flaticon.com/512/2694/2694755.png';
                                    break;
                                case 'TXT':
                                    $iconClass = 'https://cdn-icons-png.flaticon.com/512/2694/2694755.png';
                                    break;
                                    // Agrega más casos según los tipos de archivos que desees manejar
                                default:
                                    $iconClass = 'https://icones.pro/wp-content/uploads/2021/06/icone-fichier-document-noir.png'; // Icono genérico si no se encuentra el tipo de archivo
                                    break;
                            }
                            ?>
                            <img src="<?= $iconClass ?>" alt="" height="200px" width="200px">
                            <h4 style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?= $documento['NombreDocumento'] ?></h4>
                            <p>Tipo de Documento: <?= $documento['TipoDocumento'] ?></p>
                            <p>Datos del Documento: <?= $documento['DatosDocumento']['type'] ?></p>
                            <p>Fecha de Carga: <?= $documento['FechaCarga'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php } ?>
            </div>
            <!-- Agrega un botón de submit adicional fuera del bucle para enviar el formulario -->
            <button type="submit" class="btn btn-primary mb-2 float-right">Ejecutar Operación</button>
        </form>

        <script>
            function setOperacion(operacion) {
                // Obtener todos los checkbox
                var checkboxes = document.querySelectorAll('.documentoCheckbox');

                // Crear un arreglo para almacenar los IDs de los documentos seleccionados
                var documentosSeleccionados = [];

                // Iterar a través de los checkbox
                checkboxes.forEach(function(checkbox) {
                    if (checkbox.checked) {
                        // Obtener el valor del atributo 'value' del input hidden correspondiente al checkbox seleccionado
                        var documentoId = checkbox.parentElement.querySelector('input[type="hidden"]').value;

                        // Agregar el ID del documento al arreglo
                        documentosSeleccionados.push(documentoId);
                    }
                });

                // Establecer el valor del campo oculto 'operacion'
                document.getElementById('operacion').value = operacion;

                alert(documentosSeleccionados)

                // Establecer el valor del campo oculto 'documentos_seleccionados'
                document.getElementById('values_files').value = documentosSeleccionados;

                // Enviar el formulario
                document.getElementById('operacionesForm').submit();
            }
        </script>



    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Obtén los elementos de los checkbox
        const checkboxes = document.querySelectorAll('.documentoCheckbox');

        // Agrega un controlador de eventos al botón "Encriptar"
        document.getElementById('encriptarBtn').addEventListener('click', function() {
            // Itera a través de los checkbox
            checkboxes.forEach(function(checkbox) {
                if (checkbox.checked) {
                    // Aquí puedes realizar la lógica para encriptar los documentos seleccionados
                    alert('Documento encriptado: ' + checkbox.parentElement.parentElement.querySelector('h4').textContent);
                }
            });
        });

        document.getElementById('encriptarBtn').addEventListener('click', function() {
            // Itera a través de los checkbox
            checkboxes.forEach(function(checkbox) {
                if (checkbox.checked) {
                    // Aquí puedes realizar la lógica para encriptar los documentos seleccionados
                    alert('Documento encriptado: ' + checkbox.parentElement.parentElement.querySelector('h4').textContent);
                }
            });
        });
    </script>
</body>

</html>