<?php

class correo extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('correo_model');
        $this->load->library('mensajes');
        $this->load->library('perms'); 
        $this->load->library('form_validation');
        
        if(!$this->perms->VerificoUsuario()){
            die($this->mensajes->sinPermisos());
        }         
    }
    
    function index() {
        
        unset($_SESSION['id_correo']);
        
        //$data['heading'] = $this->load->view('jss-css');
        $data['correo'] = '';
        
        if($this->correo_model->verificoCorreos() > 0){
            $correos = array();
            $correos = $this->correo_model->cargoCorreos_db();
            
            //armo tabla con correos
            for($i=0; $i<count($correos); $i=$i+5) {
                
                $color = '#E6E6E6';
                $font = 'normal';
                
                if($correos[$i] == 1) { //el correo no fue leido nunca
                    $color = '#A4A4A4';
                    $font = 'bold';
                }
                
                $data['correo'] .= "<tr style='background: ".$color."; font-weight: ".$font.";'> <td style='text-align: center;'> <img src='".base_url()."/images/delete.gif' onclick='eliminarCorreo(".$correos[$i+1].")' style='cursor: pointer; ' /> </td> <td>".$correos[$i+2]."</td> <td>".$correos[$i+3]."</td> <td>".$correos[$i+4]."</td> <td style='text-align: center;'> <img src='".base_url()."/images/eye.png' onclick='verCorreo(".$correos[$i+1].")' style='cursor: pointer; ' /> </td>";
            }
        }
        
        $this->load->view('correo_view', $data);
    }
    
    function cargarCorreos() {

        $correo = '';
        
        if($this->correo_model->verificoCorreos() > 0){
            $correos = array();
            $correos = $this->correo_model->cargoCorreos_db();
            
            //armo tabla con correos
            for($i=0; $i<count($correos); $i=$i+5) {
                
                $color = '#E6E6E6';
                $font = 'normal';
                
                if($correos[$i] == 1) { //el correo no fue leido nunca
                    $color = '#A4A4A4';
                    $font = 'bold';
                }
                
                $correo .= "<tr style='background: ".$color."; font-weight: ".$font.";'> <td style='text-align: center;'> <img src='".base_url()."/images/delete.gif' onclick='eliminarCorreo(".$correos[$i+1].")' style='cursor: pointer; ' /> </td> <td>".$correos[$i+2]."</td> <td>".$correos[$i+3]."</td> <td>".$correos[$i+4]."</td> <td style='text-align: center;'> <img src='".base_url()."/images/eye.png' onclick='verCorreo(".$correos[$i+1].")' style='cursor: pointer; ' /> </td>";
            }
        }
        
        echo $correo;
    }
    
    function eliminarCorreo($id_correo) {
        $retorno = $this->correo_model->eliminarCorreo_db($id_correo);
        echo $retorno;
    }
    
    function vaciarBandeja() {
        $this->correo_model->vaciarBandeja_db();
        echo 1;
    }
    
    function verCorreo() {
        $id_correo = $_POST['id_correo'];
        $_SESSION['id_correo'] = $id_correo;
    }
    
}

?>
