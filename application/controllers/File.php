<?php
defined('BASEPATH') or exit('No direct script access allowed');

class File extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    public function index()
    {
        // Cargar el driver de cache
        $this->load->driver('cache');

        // Intentar obtener el sesion_iniciada desde la cache
        $sesion_iniciada = $this->cache->file->get('sesion_iniciada');

        // Verificar si el sesion_iniciada se encontró en la cache
        if ($sesion_iniciada !== false) {
            $data = array();

            // Agregar el sesion_iniciada al arreglo de datos
            $data['sesion_iniciada'] = $sesion_iniciada;

            // Realizar la consulta para obtener los documentos
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://localhost:8080/api/obtener_documentos',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: ' . $sesion_iniciada,
                    'Cookie: connect.sid=s%3Act_1BqYSUWR_HeEdFJUCQvTxXBqnD4Xl.fp7PgUPL5cvlOp2DXy2AGGZwwgVyk3Mss5Byf5y940c'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            // Decodificar la respuesta JSON
            $data['documentos'] = json_decode($response, true);

            // Cargar las vistas con los datos
            $this->load->view('header_footer/header_view', $data);
            $this->load->view('file_view', $data);
            $this->load->view('header_footer/footer_view');
        } else {
            // Mostrar un mensaje de error en un alert
            echo "<script>alert('Error al iniciar sesión');</script>";

            // Redireccionar a la página de inicio de sesión en caso de error
            redirect('home');
        }
    }

    public function subir_archivo()
    {
        // Cargar el driver de cache
        $this->load->driver('cache');

        // Obtener el sesion_iniciada desde la cache
        $sesion_iniciada = $this->cache->file->get('sesion_iniciada');

        // Verificar si el sesion_iniciada se encontró en la cache
        if ($sesion_iniciada !== false) {
            // Configuración para la carga de archivos
            $config['upload_path'] = './uploads';
            $config['allowed_types'] = '*'; // Ajusta los tipos de archivos permitidos según tus necesidades
            $config['max_size'] = 1000000; // Tamaño máximo en kilobytes

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('archivo')) {
                // Archivo subido exitosamente, procesa la información y guarda en la base de datos si es necesario
                $file_info = $this->upload->data();
                // Obtener la extensión del archivo y convertirla a mayúsculas
                $extension = strtoupper(pathinfo($file_info['file_name'], PATHINFO_EXTENSION));

                // Datos para la solicitud al endpoint
                $datos = array(
                    "NombreDocumento" => $file_info['file_name'],
                    "TipoDocumento" => $extension,
                    "DatosDocumento" => base64_encode(file_get_contents($file_info['full_path'])),
                    "FechaCarga" => date('Y-m-d H:i:s')
                );

                // Realizar la solicitud al endpoint
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'http://localhost:8080/api/documentos',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode($datos),
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: ' . $sesion_iniciada,
                        'Content-Type: application/json',
                        'Cookie: connect.sid=s%3AfXR3y1Bb2d9V3eUVh5XI7JmK0AZialdD.3v6rFoXU6cCroQmyPAHY%2B44tic7RYRamlxdETKcDl5g'
                    ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);


                $this->index();
            } else {
                // Error en la carga del archivo
                echo $this->upload->display_errors();
            }
        } else {
            // El usuario no tiene sesión iniciada, maneja el error según tus necesidades
            echo "Error al iniciar sesión.";
        }
    }

    public function ejecutar_operacion()
    {
        // Obtener la operación seleccionada desde la solicitud POST
        $operacion = $this->input->post('operacion');

        // Verificar la operación y ejecutar la función correspondiente
        switch ($operacion) {
            case 'encriptar':
                $this->encriptar_documentos();
                break;
            case 'desencriptar':
                $this->desencriptar_documentos();
                break;
            case 'descargar':
                $this->descargar_documentos();
                break;
            default:
                // Manejar un caso por defecto o mostrar un error
                break;
        }
    }

    public function descargar_documentos()
    {
        $documentos_seleccionados = $this->input->post('values_files');

        // Usar explode para convertir la cadena en un array
        $array_resultante = explode(',', $documentos_seleccionados);

        // Cargar el driver de cache
        $this->load->driver('cache');

        // Obtener el sesion_iniciada desde la cache
        $sesion_iniciada = $this->cache->file->get('sesion_iniciada');

        // Verificar si el sesion_iniciada se encontró en la cache
        if ($sesion_iniciada !== false) {
            // Realizar la solicitud al endpoint de encriptación para cada documento seleccionado
            foreach ($array_resultante as $documento_id) {
                // Obtener información del documento
                $documento = $this->obtener_informacion_documento($documento_id);                

                // Supongamos que $documento['DatosDocumento']['data'] es el arreglo de bytes
                $arreglo_bytes = $documento['DatosDocumento']['data'];

                // Convierte el arreglo de bytes a base64
                $base64_data = base64_encode(implode('', $arreglo_bytes));

                // Configura las cabeceras HTTP
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . $documento['NombreDocumento'] . '.dlse"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');

                // Devuelve la cadena base64 como parte del contenido de la respuesta HTTP
                echo $base64_data;
            }
        } else {
            // El usuario no tiene sesión iniciada, maneja el error según tus necesidades
            echo "Error al iniciar sesión.";
        }
    }


    public function encriptar_documentos()
    {
        $documentos_seleccionados = $this->input->post('values_files');

        // Usar explode para convertir la cadena en un array
        $array_resultante = explode(',', $documentos_seleccionados);

        // Cargar el driver de cache
        $this->load->driver('cache');

        // Obtener el sesion_iniciada desde la cache
        $sesion_iniciada = $this->cache->file->get('sesion_iniciada');

        // Verificar si el sesion_iniciada se encontró en la cache
        if ($sesion_iniciada !== false) {
            // Realizar la solicitud al endpoint de encriptación para cada documento seleccionado
            foreach ($array_resultante as $documento_id) {
                // Obtener información del documento
                $documento = $this->obtener_informacion_documento($documento_id);

                // Supongamos que $documento['DatosDocumento']['data'] es el arreglo de bytes
                $arreglo_bytes = $documento['DatosDocumento']['data'];

                // Convertir el arreglo de bytes a una cadena
                $cadena_bytes = implode('', array_map('chr', $arreglo_bytes));

                // Convertir la cadena a formato Base64
                $base64_string = base64_encode($cadena_bytes);

                // Ahora $base64_string contiene la representación en formato Base64 de los bytes


                if ($documento !== false) {
                    // Datos para la solicitud al endpoint de encriptación
                    $datos_encriptacion = array(
                        "nombre_documento" => $documento['NombreDocumento'],
                        "documento" => $base64_string,
                        "claveDeEncriptacion" => "TuClaveSecreta",
                        "FechaCarga" => date('Y-m-d H:i:s')
                    );

                    // Realizar la solicitud al endpoint de encriptación
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'http://localhost:8080/api/encriptar',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => json_encode($datos_encriptacion),
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: ' . $sesion_iniciada,
                            'Content-Type: application/json',
                            'Cookie: connect.sid=s%3AfXR3y1Bb2d9V3eUVh5XI7JmK0AZialdD.3v6rFoXU6cCroQmyPAHY%2B44tic7RYRamlxdETKcDl5g'
                        ),
                    ));

                    $response = curl_exec($curl);

                    curl_close($curl);

                    // Manejar la respuesta del servidor según sea necesario                    
                    $this->index();
                }
            }
        } else {
            // El usuario no tiene sesión iniciada, maneja el error según tus necesidades
            echo "Error al iniciar sesión.";
        }
    }


    private function obtener_informacion_documento($ID)
    {
        // Cargar el driver de cache
        $this->load->driver('cache');

        // Obtener el sesion_iniciada desde la cache
        $sesion_iniciada = $this->cache->file->get('sesion_iniciada');

        // Agregar el sesion_iniciada al arreglo de datos
        $data['sesion_iniciada'] = $sesion_iniciada;

        // Realizar la consulta para obtener los documentos
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://localhost:8080/api/obtener_documentos',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $sesion_iniciada,
                'Cookie: connect.sid=s%3Act_1BqYSUWR_HeEdFJUCQvTxXBqnD4Xl.fp7PgUPL5cvlOp2DXy2AGGZwwgVyk3Mss5Byf5y940c'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        // Decodificar la respuesta JSON
        $documentos = json_decode($response, true);

        // Buscar el documento con el ID proporcionado
        foreach ($documentos as $documento) {
            if ($documento['ID'] == $ID) {
                // Devolver los datos del documento encontrado                
                return $documento;
            }
        }

        // Si no se encuentra el documento, devolver false
        return false;
    }


    public function desencriptar_documentos()
    {
        $documentos_seleccionados = $this->input->post('values_files');

        // Usar explode para convertir la cadena en un array
        $array_resultante = explode(',', $documentos_seleccionados);

        // Cargar el driver de cache
        $this->load->driver('cache');

        // Obtener el sesion_iniciada desde la cache
        $sesion_iniciada = $this->cache->file->get('sesion_iniciada');

        // Verificar si el sesion_iniciada se encontró en la cache
        if ($sesion_iniciada !== false) {
            // Realizar la solicitud al endpoint de desencriptación para cada documento seleccionado            
            foreach ($array_resultante as $documento_id) {
                // Obtener información del documento
                $documento = $this->obtener_informacion_documento($documento_id);

                if ($documento !== false) {
                    // Datos para la solicitud al endpoint de desencriptación
                    $datos_desencriptacion = array(
                        "documentoId" => $documento['ID'],
                        "claveDeEncriptacion" => "TuClaveSecreta"
                    );

                    // Realizar la solicitud al endpoint de desencriptación
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'http://localhost:8080/api/desencriptar',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => json_encode($datos_desencriptacion),
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: ' . $sesion_iniciada,
                            'Content-Type: application/json',
                            'Cookie: connect.sid=s%3AfXR3y1Bb2d9V3eUVh5XI7JmK0AZialdD.3v6rFoXU6cCroQmyPAHY%2B44tic7RYRamlxdETKcDl5g'
                        ),
                    ));

                    $response = curl_exec($curl);

                    // Decodificar el JSON resultante
                    $json_data = json_decode($response, true);

                    // Almacenar los datos desencriptados en el array
                    $datos_desencriptados[] = $json_data['datosDesencriptados'];


                    // Descargar archivos
                    $numeral = 0;
                    foreach ($datos_desencriptados as $datos) {
                        // Decodificar base64
                        $decoded_data = base64_decode($datos);

                        // Determinar el tipo de archivo (puedes ajustar esto según tus necesidades)
                        $tipo_archivo = "pdf"; // ejemplo: PDF
                        $extension = '.' . $tipo_archivo;

                        // Nombre del archivo
                        $nombre_archivo =   $documento['NombreDocumento'] . $extension;

                        // Ruta del archivo
                        $ruta_archivo = "./downloads/";

                        // Guardar el archivo con la extensión correcta
                        echo file_put_contents($ruta_archivo . $nombre_archivo, $decoded_data);

                        $numeral++;
                    }

                    curl_close($curl);
                    $this->index();
                }
            }
        } else {
            // El usuario no tiene sesión iniciada, maneja el error según tus necesidades
            echo "Error al iniciar sesión.";
        }
    }
}