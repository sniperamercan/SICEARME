<?php

class imprimir_ficha extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('imprimir_ficha_model');
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

        if(isset($_SESSION['imprimir_nro_serie']) && !empty($_SESSION['imprimir_nro_serie'])) {
            $nro_serie = $_SESSION['imprimir_nro_serie'];
            $marca     = $_SESSION['imprimir_marca'];
            $calibre   = $_SESSION['imprimir_calibre'];
            $modelo    = $_SESSION['imprimir_modelo'];
        }else {
            $nro_serie = "";
            $marca     = "";
            $calibre   = "";
            $modelo    = "";
        }
        
        $concat = '';
        
        $data['nro_serie'] = $nro_serie;
        $data['marca']     = $marca;
        $data['calibre']   = $calibre;
        $data['modelo']    = $modelo;
        
        $tipo_sistema = $this->imprimir_ficha_model->verTipoSistema($nro_serie, $marca, $calibre, $modelo);
        
        $data['tipo_arma'] = $tipo_sistema[0];
        $data['sistema']   = $tipo_sistema[1];
        
        
        if(!$this->imprimir_ficha_model->tieneAccesorios($nro_serie, $marca, $calibre, $modelo)) {
            $concat = "<tr> <td style='text-align: center;'></td> <td></td> <td></td> </tr>";
        }else {
            $accesorios = array();
            $accesorios = $this->imprimir_ficha_model->verAccesorios($nro_serie, $marca, $calibre, $modelo);
            /*
             * 
            $retorno[] = $row->nro_accesorio;
            $retorno[] = $row->tipo_accesorio;
            $retorno[] = $row->descripcion;
            */
            
            $j = 0;
            
            for($i=0; $i<count($accesorios); $i=$i+3) {
                if($j % 2 == 0){
                    $class = "";
                }else{
                    $class = "alt";
                } 
                $concat .= "<tr class='".$class."'> <td style='text-align: center;'>".$accesorios[$i]."</td> <td>".$accesorios[$i+1]."</td> <td>".$accesorios[$i+2]."</td> </tr>";
                $j++;
            }
        }
       
        $data['accesorios_ficha'] = $concat;
        
        $concat = "";
        
        if(!$this->imprimir_ficha_model->tienePiezas($nro_serie, $marca, $calibre, $modelo)) {
            $concat = "<tr> <td style='text-align: center;'></td> <td></td> <td></td> </tr>";
        }else {
            $piezas = array();
            $piazas = $this->imprimir_ficha_model->verPiezas($nro_serie, $marca, $calibre, $modelo);
            /*
             * 
            $retorno[] = $row->nro_accesorio;
            $retorno[] = $row->tipo_accesorio;
            $retorno[] = $row->descripcion;
            */
            
            $j = 0;
            
            for($i=0; $i<count($piazas); $i=$i+3) {
                if($j % 2 == 0){
                    $class = "";
                }else{
                    $class = "alt";
                } 
                $concat .= "<tr class='".$class."'> <td style='text-align: center;'>".$piazas[$i]."</td> <td>".$piazas[$i+1]."</td> <td>".$piazas[$i+2]."</td> </tr>";
                $j++;
            }
        }        
        
        $data['piezas_ficha'] = $concat;
        
        //cargo la vista
        $this->load->view("imprimir_ficha_view", $data);
    }
    
}

?>
