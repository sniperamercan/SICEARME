<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Tercera Iteracion
* Clase - alta_inventario_reserva
*/

class alta_inventario_reserva extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('alta_inventario_reserva_model');
        $this->load->library('mensajes');
        $this->load->library('perms'); 
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()) {
            die($this->mensajes->sinPermisos());
        }         
        
        //Modulo solo visible para el peril 8 - Usuario Reserva
        if(!$this->perms->verificoPerfil8()) {
            die($this->mensajes->sinPermisos());
        }
    }
    
    function index() {
        
        //Cargo nro de series de armamentos que esten en deposito inicial
        if($this->alta_inventario_reserva_model->hayDepositos()) {
            $depositos = $this->alta_inventario_reserva_model->cargoDepositos();
        }else{
            $depositos = array();
        }
        
        $aux = '""';
        
        $data['depositos'] = "<option> </option>";
        
        foreach($depositos as $val) {
            $aux = '"'.$val.'"';
            $data['depositos'] .= "<option value='".$val."'>".$val."</option>";
        }
        //Fin cargo nro de series de armamento en deposito inicial
        
        $this->load->view('alta_inventario_reserva_view', $data); 
    }
    
    function cargoDepositos() {
        
        $depositos = $this->alta_inventario_reserva_model->cargoDepositos();
        
        $concat = "<option> </option>";
        
        foreach($depositos as $val) {
            if($val == $_SESSION['alta_deposito']) {
                $concat .= "<option selected='selected' value='".$val."'>".$val."</option>";
                $_SESSION['alta_deposito'] = "";
            }else {
                $concat .= "<option value='".$val."'>".$val."</option>";
            }
        }
        
        echo $concat;
    }    
    
    function cargoDatos() {
        
        $nro_serie = $_POST['nro_serie'];
        $marca     = $_POST['marca'];
        $calibre   = $_POST['calibre'];
        $modelo    = $_POST['modelo'];
        
        $datos = $this->alta_inventario_reserva_model->cargoDatos($nro_serie, $marca, $calibre, $modelo);
        
        /*
         * $datos[0] - tipo_arma 1 
         * $datos[1] - sistema   2
         */
        
        for($i=0; $i<count($datos); $i=$i+2) {
            $tipo_sistema[] = $datos[$i];
            $tipo_sistema[] = $datos[$i+1];
        }
        
        if($this->alta_inventario_reserva_model->hayStockReserva($nro_serie, $marca, $calibre, $modelo)) {
            $deposito = $this->alta_inventario_reserva_model->cargoStockReserva($nro_serie, $marca, $calibre, $modelo);
        }else {
            $deposito = "SIN DEPOSITO";
        }
        
        $tipo_sistema[] = $deposito;
        
        echo json_encode($tipo_sistema);
    }
    
    function cargoFichasFiltro() {
        
        $nro_serie = $_SESSION['seleccion_busqueda'];
        $marca     = $_SESSION['seleccion_busqueda1'];
        $calibre   = $_SESSION['seleccion_busqueda2'];
        $modelo    = $_SESSION['seleccion_busqueda3'];
        
        //Retorno los datos
        $retorno = array();
        $retorno[] = $nro_serie;
        $retorno[] = $marca;
        $retorno[] = $calibre;
        $retorno[] = $modelo;
        
        $datos = $this->alta_inventario_reserva_model->cargoDatos($nro_serie, $marca, $calibre, $modelo);
        
        $retorno[] = $datos[0]; //tipo_arma
        $retorno[] = $datos[1]; //sistema
        
        if($this->alta_inventario_reserva_model->hayStockReserva($nro_serie, $marca, $calibre, $modelo)) {
            $deposito = $this->alta_inventario_reserva_model->cargoStockReserva($nro_serie, $marca, $calibre, $modelo);
        }else {
            $deposito = "SIN DEPOSITO";
        }
        
        $retorno[] = $deposito;
        
        echo json_encode($retorno);        
    }    
    
    function validarDatos() {
        
        $patterns = array();
        
        $patterns[] = '/"/';
        $patterns[] = "/'/";        

        $patterns[] = '/{/';
        
        $patterns[] = '/}/';
        
        $patterns[] = '/|/';
      
        $nro_serie      = $_POST["nro_serie"];
        $marca          = $_POST["marca"];
        $calibre        = $_POST["calibre"];
        $modelo         = $_POST["modelo"];
        $deposito_nuevo = $_POST["deposito_nuevo"];
      
        
        $mensja_error = array();
        $retorno = array();
        
        if(empty($nro_serie)) {
            $mensja_error[] = 1;
        }
        
        if(empty($marca)) {
            $mensja_error[] = 2;
        }        
        
        if(empty($calibre)) {
            $mensja_error[] = 3;
        }        

        if(empty($modelo)) {
            $mensja_error[] = 4;
        }        

        if(empty($deposito_nuevo)) {
            $mensja_error[] = 5;
        }        
        
        if(count($mensja_error) > 0) {
            
            switch($mensja_error[0]) {
                
                case 1:
                    echo $this->mensajes->errorVacio('nro serie');
                    break;
                
                case 2:
                    echo $this->mensajes->errorVacio('marca');
                    break;
                
                case 3:
                    echo $this->mensajes->errorVacio('calibre');
                    break;
                
                case 4:
                    echo $this->mensajes->errorVacio('modelo');
                    break;
                
                case 5:
                    echo $this->mensajes->errorVacio('deposito nuevo');
                    break;
            }
        }else {
            if($this->alta_inventario_reserva_model->hayStockReserva($nro_serie, $marca, $calibre, $modelo)) {
                $this->alta_inventario_reserva_model->modificarInventario($nro_serie, $marca, $calibre, $modelo, $deposito_nuevo);
            }else {
                $this->alta_inventario_reserva_model->altaInventario($nro_serie, $marca, $calibre, $modelo, $deposito_nuevo);
            }
            echo 1;
        }
    }
}

?>