<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Registro extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->driver('cache');
    }

    public function index()
    {
        $this->load->view('registro');
    }

    public function registrar_usuario()
    {
        // Obtener datos del formulario
        $tipoRegistro = $this->input->post('tipoRegistro');
        $correo = $this->input->post('email');
        $contrasena = $this->input->post('password');
        $imagenPerfil = $this->input->post('imagenPerfil');
        $nombreCompleto = $this->input->post('nombreCompleto');
        $fechaNacimiento = $this->input->post('fechaNacimiento');
        $genero = $this->input->post('genero');
        $telefono = $this->input->post('telefono');
        $nombreUsuario = $this->input->post('nombreUsuario');
        $googleUserId = $this->input->post('googleUserId');

        // Eliminar el prefijo
        $base64String = str_replace("data:image/png;base64,", "", $imagenPerfil);

        // Crear arreglo de datos para la consulta cURL
        $postData = array(
            'tipoRegistro' => 'normal',
            'correo' => $correo,
            'contrasena' => $contrasena,
            'imagenPerfil' => $base64String,
            'nombreCompleto' => $nombreCompleto,
            'fechaNacimiento' => $fechaNacimiento,
            'genero' => $genero,
            'telefono' => $telefono,
            'nombreUsuario' => $nombreUsuario,
            'googleUserId' => $googleUserId
        );
        

        // Realizar la consulta cURL
        $ch = curl_init('http://localhost:8080/api/usuarios');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

        // Ejecutar la llamada y obtener la respuesta
        $response = curl_exec($ch);

        // Decodificar la respuesta JSON
        $data = json_decode($response, true);

        if (isset($data['token'])) {                                    
            // Almacenar el token en la cache
            $this->load->driver('cache');
            $this->cache->file->save('sesion_iniciada', $data['token'], 86400); // Caducidad en segundos (24 horas)		

            // Realizar la consulta para obtener datos del usuario
			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => 'http://localhost:8080/api/find_user',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_HTTPHEADER => array(
					'Authorization: Bearer ' . $data['token'],
					'Cookie: connect.sid=' . $data['token'],
				),
			));

			$response = curl_exec($curl);

			curl_close($curl);

			// Guardar otros datos en el caché
			$userData = json_decode($response, true);
			$reconocimientoFacialData = $userData['user']['ReconocimientoFacialData']['data'];
			$nombreCompleto = $userData['user']['NombreCompleto'];

			// Almacenar en el caché
			$this->cache->file->save('user_picture_normal', $reconocimientoFacialData, 86400);
			$this->cache->file->save('name_normal', $nombreCompleto, 86400);

            redirect('file'); 

        } else {
            // Mostrar un mensaje de error en un alert
            echo "<script>console.log('Error al registrar usuario');</script>";
        }
        curl_close($ch);
        
    }
}
