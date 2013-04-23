<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Primera Iteracion
* Clase - ajuste_cantidad_stock
*/

class ajuste_cantidad_stock extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('ajuste_cantidad_stock_model');
        $this->load->library('mensajes');
        $this->load->library('perms'); 
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()) {
            die($this->mensajes->sinPermisos());
        }         
        
        //Modulo solo visible para el peril 6 y 7 - Usuarios taller de armamento y Administradores taller de armamento 
        if(!$this->perms->verificoPerfil6() && !$this->perms->verificoPerfil7()) {
            die($this->mensajes->sinPermisos());
        }
    }
    
    function index() {
        $_SESSION['ajust_cantidad_stock'] = '';
        $this->load->view('ajuste_cantidad_stock_view');  
    }
    
    function validarDatos() {
        
        $patterns = array();
        $patterns[] = '/"/';
        $patterns[] = "/'/";
        
        $nro_parte = preg_replace($patterns, '', $_POST["nro_parte"]);
        $cantidad = preg_replace($patterns, '', $_POST["cantidad"]);
        
        $mensjError = array();
        
        if(empty($cantidad)) {
            $mensjError[] = 1;  
        }
        
        
        if($this->ajuste_cantidad_stock_model->cantidadActual($nro_parte)>=$cantidad) {
            $mensjError[] = 2;
        }
        
        if(count($mensjError) > 0) {
            
            switch($mensjError[0]) {
                
                case 1:
                    echo $this->mensajes->errorVacio('cantidad');
                    break;
                
                //Falta agregar errorMayor, osea si la cantidad ingresada es mayor a la actual.
                case 2:
                    echo $this->mensajes->errorMayor('cantidad');
                    break;
            }
        }else {
            $this->ajuste_cantidad_stock_model->ajusteStock($cantidad);
            $_SESSION['ajuste_cantidad_stock'] = $cantidad;
            echo 1;
        }
     }
}

?>