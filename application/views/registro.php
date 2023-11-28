<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Registro de Usuario con Webcam</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body>

    <div class="container mt-5">
        <h2 class="mb-4">Registro de Usuario con Webcam</h2>
        <form action="<?= site_url('/registro/registrar_usuario') ?>" method="post">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nombreUsuario">Nombre de Usuario</label>
                    <input type="text" class="form-control" id="nombreUsuario" name="nombreUsuario" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="password">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="profileImage">Imagen de Perfil</label>
                    <video id="video" width="100%" height="auto" autoplay></video>
                    <button type="button" class="btn btn-secondary mt-2" onclick="captureImage()">Capturar Foto</button>
                    <canvas id="canvas" style="display:none;"></canvas>
                    <input type="hidden" id="profileImageData" name="imagenPerfil">
                    <img id="capturedImage" style="display:none;" class="mt-2" alt="Captured Image">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nombreCompleto">Nombre Completo</label>
                    <input type="text" class="form-control" id="nombreCompleto" name="nombreCompleto" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="fechaNacimiento">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="genero">Género</label>
                    <select class="form-control" id="genero" name="genero" required>
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" class="form-control" id="telefono" name="telefono" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Registrarse</button>
            <div style="display: block"><a href="<?php echo base_url('index.php/login'); ?>" id="passRestorelink">Volver al inicio de sesion</a></div>

        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script>
        // Acceder a la cámara web y mostrar la transmisión de video
        navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then(function(stream) {
                var video = document.getElementById('video');
                video.srcObject = stream;
                video.play();
            })
            .catch(function(error) {
                console.error('Error al acceder a la cámara web:', error);
            });

        // Capturar una foto desde el video
        function captureImage() {
            var video = document.getElementById('video');
            var canvas = document.getElementById('canvas');
            var capturedImage = document.getElementById('capturedImage');
            var profileImageDataInput = document.getElementById('profileImageData');

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

            var imageDataUrl = canvas.toDataURL('image/png');
            capturedImage.src = imageDataUrl;
            capturedImage.style.display = 'block';

            // Guardar los datos de la imagen en el campo oculto
            profileImageDataInput.value = imageDataUrl;
        }
    </script>

</body>

</html>