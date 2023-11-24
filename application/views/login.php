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
                                        <div style="display: block"><a href="javascript:void(0);" id="passRestorelink">¿Has olvidado la contraseña?</a></div>
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


                                    <div class="accountLinks">
                                        <ul>

                                            <li>
                                                <a href="<?php echo site_url('login/google_login'); ?>" class="popup google">
                                                    <span class="icon"></span>

                                                    <span class="provider-text">Google</span>

                                                </a>
                                            </li>


                                        </ul>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="clearFix"></div>
                </div>
            </div>