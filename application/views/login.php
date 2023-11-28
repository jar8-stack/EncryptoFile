<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es-ES" lang="es-ES" prefix="og: http://ogp.me/ns#">

<head>
    <title>
        Acceder a su portal | ONLYOFFICE
    </title>

    <link type='text/css' href='https://static-www.onlyoffice.com/v9.5.0/css/reset.min.css?ver=www.95' rel='stylesheet' />
    <link type='text/css' href='https://static-www.onlyoffice.com/v9.5.0/css/common.min.css?ver=www.95' rel='stylesheet' />
    <link type='text/css' href='https://static-www.onlyoffice.com/v9.5.0/css/navigation.min.css?ver=www.95' rel='stylesheet' />
    <link type='text/css' href='https://static-www.onlyoffice.com/v9.5.0/css/language-selector.min.css?ver=www.95' rel='stylesheet' />
    <link type='text/css' href='https://static-www.onlyoffice.com/v9.5.0/css/buttons.min.css?ver=www.95' rel='stylesheet' />
    <link type='text/css' href='https://static-www.onlyoffice.com/v9.5.0/css/forms.min.css?ver=www.95' rel='stylesheet' />
    <link type='text/css' href='https://static-www.onlyoffice.com/v9.5.0/css/phonecontroller.min.css?ver=www.95' rel='stylesheet' />
    <link type='text/css' href='https://static-www.onlyoffice.com/v9.5.0/css/teamlab.min.css?ver=www.95' rel='stylesheet' />
    <link type='text/css' href='https://static-www.onlyoffice.com/v9.5.0/css/screentour.min.css?ver=www.95' rel='stylesheet' />
    <link type='text/css' href='https://static-www.onlyoffice.com/v9.5.0/css/tl-combobox.min.css?ver=www.95' rel='stylesheet' />
    <link type='text/css' href='https://static-www.onlyoffice.com/v9.5.0/css/styled-selector.min.css?ver=www.95' rel='stylesheet' />
    <link type='text/css' href='https://static-www.onlyoffice.com/v9.5.0/css/jquery_style.min.css?ver=www.95' rel='stylesheet' />
    <link type='text/css' href='https://static-www.onlyoffice.com/v9.5.0/css/jquery-ui-1.8.14.custom.min.css?ver=www.95' rel='stylesheet' />
    <link type='text/css' href='https://static-www.onlyoffice.com/v9.5.0/css/mobile.min.css?ver=www.95' rel='stylesheet' />
    <link type='text/css' href='https://static-www.onlyoffice.com/v9.5.0/css/pushy.min.css?ver=www.95' rel='stylesheet' />
    <link type='text/css' href='https://static-www.onlyoffice.com/v9.5.0/css/signin.min.css?ver=www.95' rel='stylesheet' />
</head>

<body id="signinpage" data-bodyid="signin">
    </header>
    <article id="target">
        <div class="contentConteiner">
            <div class="InnerPage signinpage">
                <div class="innerblue">
                    <div class="description">
                        <div class="signinpageform">
                            <div class="dataForm SignInPanel">

                                <div class="signinwithsocial">
                                    <div id="divSigninError" class="dataFormError">Error</div>
                                    <div id="divSigninProgress" class="Progress">Por favor espere...</div>

                                    <!-- Modificación: Llamada al controlador Login para consumir el endpoint de login -->
                                    <form id="signInForm" action="<?php echo base_url('index.php/login/signin'); ?>" method="post">
                                        <h3>Inicia sesión en tu oficina en la nube de ONLYOFFICE</h3>
                                        <div class="dataItem">
                                            <div class="input-wrapper">
                                                <input type="email" tabindex="1" id="txtSignInEmail" name="txtSignInEmail" maxlength="255" required />
                                                <label class="dataLabel" for="txtSignInEmail">E-mail*</label>
                                            </div>
                                            <div id="divSigninEmailError" class="dataFormError">Error</div>
                                        </div>
                                        <div class="dataItem">
                                            <div class="input-wrapper">
                                                <input type="password" tabindex="2" id="txtSignPassword" name="txtSignPassword" required data-min="8" maxlength="120" data-hashsize="256" data-hashiterations="100000" data-hashsalt="1e912b1b2ce20b91bb9db717e214feb1771045bd9fea31727e59f514964b944f" />
                                                <label class="dataLabel" for="txtSignPassword">Contraseña*</label>
                                            </div>
                                        </div>
                                        <input id="signIn" type="button" value="Entrar" onclick="SignIn();" tabindex="3" class="button red" />
                                    </form>

                                    <div class="text right-container">
                                        <div style="display: block"><a href="<?php echo base_url('index.php/registro'); ?>" id="passRestorelink">No estas registrado?</a></div>
                                    </div>
                                </div>

                                <script>
                                    function SignIn() {
                                        // Llama al método signin del controlador
                                        document.getElementById('signInForm').submit();
                                    }
                                </script>



                                <div class="text social">
                                    <span>Entrar con</span>
                                    <center>
                                        <div class="box">
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
                                                                    window.location.href = 'http://localhost/EncryptoFile/index.php/file';
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
                                    </center>
                                    <div class="text right-container">
                                        <div style="display: block"><a href="<?php echo base_url('index.php/login/login_facial_view'); ?>" id="passRestorelink">Ingresar con reconocimiento facial</a></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="clearFix"></div>
                    </div>
                </div>