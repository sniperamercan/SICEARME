<?php

class imprimir_catalogo extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('imprimir_catalogo_model');
        $this->load->library('perms');
        $this->load->library('pagination');   
        $this->load->library('mensajes');
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()){
            die($this->mensajes->sinPermisos());
        }
        
        //Modulo solo visible para el peril 2 y 3 - Usuarios O.C.I y Administradores O.C.I 
        if(!$this->perms->verificoPerfil2() && !$this->perms->verificoPerfil3()) {
            die($this->mensajes->sinPermisos());
        }        
    }
    
    function index() {

        if(isset($_SESSION['imprimir_nro_catalogo']) && !empty($_SESSION['imprimir_nro_catalogo'])) {
            $nro_catalogo = $_SESSION['imprimir_nro_catalogo'];
        }else {
            $nro_catalogo = 0;
        }
            
        //traigo todos los datos del acta en un array
        $datos_catalogo = $this->imprimir_catalogo_model->datosCatalogo($nro_catalogo);
        
        /*
            $retorno[] = $row->tipo_arma;       1
            $retorno[] = $row->marca;           2
            $retorno[] = $row->calibre;         3
            $retorno[] = $row->modelo;          4
            $retorno[] = $row->sistema;         5
            $retorno[] = $row->año_fabricacion; 6
            $retorno[] = $row->empresa;         7
            $retorno[] = $row->pais_origen;     8
            $retorno[] = $row->vencimiento;     9
         */
        
        $data['nro_catalogo']    = $nro_catalogo;
        $data['tipo_arma']       = $datos_catalogo[0];
        $data['marca']           = $datos_catalogo[1];
        $data['calibre']         = $datos_catalogo[2];
        $data['modelo']          = $datos_catalogo[3];
        $data['sistema']         = $datos_catalogo[4];
        $data['año_fabricacion'] = $datos_catalogo[5];
        $data['empresa']         = $datos_catalogo[6];
        $data['pais_origen']     = $datos_catalogo[7];
        $data['vencimiento']     = $datos_catalogo[8];
            
        
        //cargo la vista
        $this->load->view("imprimir_catalogo_view", $data);
    }
    
}

?>
