<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->driver('cache');
    }

    public function index() {
        $data = array();

        // Verificar si sesion_iniciada está en la cache
        $sesion_iniciada = $this->cache->file->get('sesion_iniciada');

        if ($sesion_iniciada) {
            // Si sesion_iniciada está en la cache, establecer una variable para indicar que la sesión está iniciada
            $data['sesion_iniciada'] = true;
        }

        $this->load->view('header_footer/header_view', $data);
        $this->load->view('home_view');
        $this->load->view('header_footer/footer_view');
    }
}
