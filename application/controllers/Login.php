<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->driver('cache');
	}

	public function index()
	{
		$jsonConfigPath = APPPATH . 'config/google_client_secret.json';
		$jsonConfig = json_decode(file_get_contents($jsonConfigPath), true);

		$clientId = $jsonConfig['web']['client_id'];
		$data['clientId'] = $clientId;
		$this->load->view('login', $data);
	}


	public function google_login()
	{
		$jsonConfigPath = APPPATH . 'config/google_client_secret.json';
		$jsonConfig = json_decode(file_get_contents($jsonConfigPath), true);

		$clientId = $jsonConfig['web']['client_id'];
		$data['clientId'] = $clientId;

		$this->load->view('login_google', $data);
	}

	public function handle_google_login()
	{
		// Obtén el token de la solicitud POST
		$token = $this->input->post('credential');


		// Llama a la función para verificar el token
		$userInfo = $this->verifyGoogleToken($token);


		// Verificar si la verificación fue exitosa
		if ($userInfo) {
			// Realiza una consulta dependiendo de si el usuario ya existe en tu sistema o no
			$curlUrl = 'http://localhost:8080/api/usuarios';
			$curlData = array(
				"tipoRegistro" => "google",
				"correo" => $userInfo['email'],
				"contrasena" => "Secret1olo1",
				"nombreCompleto" => $userInfo['name'],
				"genero" => "Masculino",
				"telefono" => "1234567890",
				"nombreUsuario" => $userInfo['given_name'],
				"googleUserId" => $userInfo['kid']
			);

			// Realizar la consulta cURL
			$response = $this->executeCurlRequest($curlUrl, $curlData);

			// Decodificar la respuesta JSON
			$data = json_decode($response, true);

			// Verificar el código de respuesta HTTP de la primera consulta
			if (isset($data['error'])) {
				// Realizar la consulta de inicio de sesión
				$curlUrl = 'http://localhost:8080/api/login';
				$curlData = array(
					"correo" => $userInfo['email'],
					"contrasena" => "Secret1olo1"  // Considera cómo gestionar las contraseñas aquí
				);

				// Realizar la consulta cURL
				$response = $this->executeCurlRequest($curlUrl, $curlData);

				// Decodificar la respuesta JSON
				$data = json_decode($response, true);



				// Verificar si se obtuvo un token
				if (isset($data['token'])) {
					// Almacenar el token en la cache
					$this->load->driver('cache');
					$this->cache->file->save('sesion_iniciada', $data['token'], 86400); // Caducidad en segundos (24 horas)

				} else {
					// Mostrar un mensaje de error en un alert
					echo "<script>console.log('Error al iniciar sesión');</script>";
				}
				echo json_encode($data);

				$userPic = $userInfo['picture'];
				// Guardar la URL de la imagen en la caché
				$this->load->driver('cache');
				$this->cache->file->save('user_picture', $userPic, 86400);
				$this->cache->file->save('name', $userInfo['name'], 86400);
			} else {
				// Verificar si se obtuvo un token			
				if (isset($data['token'])) {
					$userPic = $userInfo['picture'];
					// Guardar la URL de la imagen en la caché
					$this->load->driver('cache');
					$this->cache->file->save('user_picture', $userPic, 86400);
					// Almacenar el token en la cache
					$this->load->driver('cache');
					$this->cache->file->save('sesion_iniciada', $data['token'], 86400); // Caducidad en segundos (24 horas)														
				} else {
					// Mostrar un mensaje de error en un alert
					echo "<script>console.log('Error al iniciar sesión');</script>";
				}
				echo json_encode($data);
			}
		}
	}

	// Función para realizar la consulta cURL
	private function executeCurlRequest($url, $data)
	{
		// Inicializar la sesión cURL
		$ch = curl_init($url);

		// Configurar las opciones de la sesión cURL
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

		// Ejecutar la llamada y obtener la respuesta
		$response = curl_exec($ch);

		// Cerrar la sesión cURL
		curl_close($ch);

		return $response;
	}

	public function verifyGoogleToken($token)
	{
		// URL de verificación de Google
		$verificationUrl = 'https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=' . $token;

		// Realiza una solicitud HTTP para verificar el token con Google
		$response = file_get_contents($verificationUrl);

		// Decodifica la respuesta JSON
		$tokenInfo = json_decode($response, true);

		// Verifica si la verificación fue exitosa
		if ($tokenInfo && !isset($tokenInfo['error'])) {
			// Devuelve la información del usuario
			return array(
				'email' => $tokenInfo['email'],
				'name' => $tokenInfo['name'],
				'given_name' => $tokenInfo['given_name'],
				'kid' => $tokenInfo['kid'],
				'picture' => $tokenInfo['picture'],
				// Otros campos que puedas necesitar
			);
		} else {
			// Hubo un problema con la verificación del token
			return false;
		}
	}




	public function signin()
	{
		// Obtener los datos del formulario
		$correo = $this->input->post('txtSignInEmail');
		$contrasena = $this->input->post('txtSignPassword');

		// Realizar la llamada cURL al endpoint de login
		$ch = curl_init('http://localhost:8080/api/login');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(
			'correo' => $correo,
			'contrasena' => $contrasena
		)));

		// Ejecutar la llamada y obtener la respuesta
		$response = curl_exec($ch);
		curl_close($ch);

		// Decodificar la respuesta JSON
		$data = json_decode($response, true);

		// Verificar si se obtuvo un token
		if (isset($data['token'])) {
			// Almacenar el token en la cache
			$this->load->driver('cache');
			$this->cache->file->save('sesion_iniciada', $data['token'], 86400); // Caducidad en segundos (24 horas)

			// Redireccionar a la página deseada
			redirect('file'); // Cambia 'dashboard' por la página a la que deseas redirigir después del login
		} else {
			// Mostrar un mensaje de error en un alert
			echo "<script>alert('Error al iniciar sesión');</script>";

			// Redireccionar a la página de inicio de sesión en caso de error
			redirect('login');
		}
	}

	public function cerrarSesion()
	{
		// Borrar la cache
		$this->cache->file->delete('sesion_iniciada');
		$this->cache->file->delete('user_picture');

		// Redireccionar al index del controlador Home
		redirect('home');
	}
}
