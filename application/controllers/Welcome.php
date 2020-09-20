<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct() {
        parent::__construct();

        $librerias = array();
        $helper = array();
        $modelos = array();
        $this->load->library($librerias);
        $this->load->helper($helper);
        $this->load->model($modelos);

    }
	
	public function index()
	{
		$data['titulo'] = 'Codeigniter - Twig';
		$data['body'] = 'Bienvenido a CI 3.1.11';

        $data = array_merge($data);
        $this->twig->display('welcome_message', $data);
	}
}
