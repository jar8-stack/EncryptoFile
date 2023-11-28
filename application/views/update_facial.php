<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Insertar image de perfil</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body>

    <div class="container mt-5">
        <h2 class="mb-4">Cargar foto de perfil</h2>
        <form id="loginForm" action="<?= site_url('login/updateFacial') ?>" method="post">
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required >
            </div>

            <div class="form-group">
                <label for="profileImage">Imagen de Perfil</label>
                <video id="video" width="100%" height="auto" autoplay></video>
                <button type="button" class="btn btn-secondary mt-2" onclick="captureImage()">Capturar Foto</button>
                <canvas id="canvas" style="display:none;"></canvas>
                <input type="hidden" id="base64String" name="base64String">
                <img id="capturedImage" style="display:none;" class="mt-2" alt="Captured Image">
            </div>

            <button type="submit" class="btn btn-primary">Cambiar foto de perfil</button>
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
            var base64StringInput = document.getElementById('base64String');

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

            var imageDataUrl = canvas.toDataURL('image/png');
            capturedImage.src = imageDataUrl;
            capturedImage.style.display = 'block';

            // Guardar los datos de la imagen en el campo oculto
            base64StringInput.value = imageDataUrl.split(',')[1];            
        }
    </script>

</body>

</html>
