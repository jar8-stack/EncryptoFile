<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Form</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap');

        body {
            margin: 0;
            padding: 0;
            background-size: cover;
            font-family: 'Open Sans', sans-serif;
        }

        .box {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 30rem;
            padding: 3.5rem;
            box-sizing: border-box;
            border: 1px solid #dadce0;
            -webkit-border-radius: 8px;
            border-radius: 8px;

        }

        .box h2 {
            margin: 0px 0 -0.125rem;
            padding: 0;
            text-align: center;
            color: #202124;
            font-size: 24px;
            font-weight: 400;
        }

        .box .logo {
            display: flex;
            flex-direction: row;
            justify-content: center;
            margin-bottom: 16px;

        }

        .box p {
            font-size: 16px;
            font-weight: 400;
            letter-spacing: 1px;
            line-height: 1.5;
            margin-bottom: 24px;
            text-align: center;
        }

        .box .inputBox {
            position: relative;
        }

        .box .inputBox input {
            width: 93%;
            padding: 1.3rem 10px;
            font-size: 1rem;
            letter-spacing: 0.062rem;
            margin-bottom: 1.875rem;
            border: 1px solid #ccc;
            background: transparent;
            border-radius: 4px;
        }

        .box .inputBox label {
            position: absolute;
            top: 0;
            left: 10px;
            padding: 0.625rem 0;
            font-size: 1rem;
            color: gray;
            pointer-events: none;
            transition: 0.5s;
        }

        .box .inputBox input:focus~label,
        .box .inputBox input:valid~label,
        .box .inputBox input:not([value=""])~label {
            top: -1.125rem;
            left: 10px;
            color: #1a73e8;
            font-size: 0.75rem;
            background-color: #fff;
            height: 10px;
            padding-left: 5px;
            padding-right: 5px;
        }

        .box .inputBox input:focus {
            outline: none;
            border: 2px solid #1a73e8;
        }

        .box input[type="submit"] {
            border: none;
            outline: none;
            color: #fff;
            background-color: #1a73e8;
            padding: 0.625rem 1.25rem;
            cursor: pointer;
            border-radius: 0.312rem;
            font-size: 1rem;
            float: right;
        }

        .box input[type="submit"]:hover {
            background-color: #287ae6;
            box-shadow: 0 1px 1px 0 rgba(66, 133, 244, 0.45), 0 1px 3px 1px rgba(66, 133, 244, 0.3);
        }

        /* Estilos adicionales para el botón de Google */
        #google-login-button {
            width: 100%;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 1rem;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #google-login-button img {
            margin-right: 1rem;
            height: 30px;
            width: auto;
        }
    </style>
</head>

<body>

    <div class="box">
        <div class="logo">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2f/Google_2015_logo.svg/2560px-Google_2015_logo.svg.png" alt="" height="50px" width="100px">
        </div>
        <h2>Sign In</h2>
        <p>Use your Google Account</p>

        <!-- Agrega tus scripts JavaScript -->
        <script>
            // Define la función handleCredentialResponse
            function handleCredentialResponse(response) {
                if (response.credential) {
                    // El inicio de sesión fue exitoso
                    console.log('Inicio de sesión exitoso:', response);

                    // Acciones adicionales después del inicio de sesión exitoso
                    getUserInfo(response.credential); // Llamamos a una función para obtener información del usuario
                } else {
                    // Hubo un problema con el inicio de sesión
                    console.error('Error en el inicio de sesión:', response);
                }
            }

            // Función para obtener información del usuario
            function getUserInfo(credential) {
                // Lógica para obtener información del usuario usando el token de credencial
                // Puedes enviar el token al servidor para realizar una verificación adicional o realizar otras acciones necesarias.
                // Por simplicidad, utilizaremos una solicitud AJAX aquí.
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '/EncryptoFile/index.php/login/handle_google_login', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4) {
                        if (xhr.status == 200) {
                            // La solicitud fue exitosa
                            console.log('Respuesta del servidor:', xhr.responseText);

                            // Verificar si la respuesta contiene un token
                            var response = JSON.parse(xhr.responseText);
                            if (response.token) {
                                // Redirigir a la página deseada
                                window.location.href = 'http://localhost/EncryptoFile/';
                            } else {
                                // Mostrar un mensaje de error si no se obtuvo un token
                                console.error('Error: No se obtuvo un token en la respuesta');
                            }
                        } else {
                            // Mostrar un mensaje de error si la solicitud no fue exitosa
                            console.error('Error en la solicitud. Código de estado:', xhr.status);
                        }
                    }
                };


                var params = 'credential=' + encodeURIComponent(credential);
                xhr.send(params);
            }
        </script>

        <!-- Agrega la biblioteca GSI -->
        <script src="https://accounts.google.com/gsi/client" async defer></script>

        <!-- Configuración del botón de inicio de sesión de Google -->
        <div id="g_id_onload" data-client_id="<?php echo $clientId; ?>" data-callback="handleCredentialResponse"></div>
        <div class="g_id_signin" data-type="standard"></div>


    </div>



</body>

</html>