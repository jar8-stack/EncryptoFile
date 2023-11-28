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

        // Crear arreglo de datos para la consulta cURL
        $postData = array(
            'tipoRegistro' => 'normal',
            'correo' => $correo,
            'contrasena' => $contrasena,
            'imagenPerfil' => $imagenPerfil,
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

            redirect('file'); 

        } else {
            // Mostrar un mensaje de error en un alert
            echo "<script>console.log('Error al registrar usuario');</script>";
        }
        curl_close($ch);
        
    }
}
