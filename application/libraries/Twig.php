<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Twig {
    private $config;
    private $functions;
    private $functions_safe;
    private $twig;
    public function __construct() {
        // configuración predeterminada
        $this->config = [
            'paths' => [VIEWPATH],
            'cache' => APPPATH . 'twig_cache',
            'debug' => ENVIRONMENT !== 'production'
        ];
        // funciones
        $this->functions = [
            'base_url',
            'site_url',
            'current_url',
            'uri_string'
        ];
        // funciones seguras
        $this->functions_safe = [
            'form_open',
            'form_close',
            'form_error',
            'set_value',
            'set_select',
            'set_checkbox',
            'set_radio',
            'validation_errors'
        ];
        $this->init();
    }
    
    /**
     * Crea el objeto Twig y agrega ayudantes CodeIgniter a Twig
     */
    private function init() {
        $this->createTwig();
        $this->addFunctions();
    }  
    /**
     * Crea el objeto twig
     */
    private function createTwig() {
        $loader = new \Twig_Loader_Filesystem($this->config['paths']);
        $twig = new \Twig_Environment($loader, [
            'cache' => $this->config['cache'],
            'debug' => $this->config['debug']
        ]);
        if ($this->config['debug']) {
            $twig->addExtension(new \Twig_Extension_Debug());
        }
        $this->twig = $twig;
    }
    
    /**
     * Agregue funciones globales para usar en plantillas
     */
    private function addFunctions() {     
        foreach ($this->functions as $function) {
            if (function_exists($function)) {
                $this->twig->addFunction(new \Twig_Function($function, $function));
            }
        }
        foreach ($this->functions_safe as $function) {
            if (function_exists($function)) {
                $this->twig->addFunction(new \Twig_Function($function, $function, ['is_safe' => ['html']]));
            }
        }
    }    
    /**
     * Agregar variables globales para usar en plantillas
     */
    public function addGlobal($name, $value) {
        $this->twig->addGlobal($name, $value);
    }
    
    /**
     * Agregue una función para usar en plantillas
     */
    public function addFunction($name, $function) {
        $this->twig->addFunction(new \Twig_Function($name, $function));
    }
    /**
     * Renderice y genere una plantilla con parámetros opcionales pasados ​​a ella
     */
    public function display($view, array $params = []) {
        $CI = &get_instance();
        $CI->output->set_output($this->render($view, $params));
    }
    /**
     * Renderice una plantilla renderizada con parámetros opcionales pasados ​​a ella
     */
    public function render($view, array $params = []) {
        $view = $view . '.twig';
        return $this->twig->render($view, $params);
    }
    /**
     * Devuelve la instancia de twig
     */
    public function getTwig() {
        return $this->twig;
    }
}