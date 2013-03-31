<?php

class imprimir_acta_alta extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('imprimir_acta_alta_model');
        $this->load->library('perms');
        $this->load->library('pagination');   
        $this->load->library('mensajes');
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()){
            die($this->mensajes->sinPermisos());
        }
        
        //Modulo solo visible para el peril 4 y 5 - Usuarios Abastecimiento y Administradores Abastecimiento
        if(!$this->perms->verificoPerfil4() && !$this->perms->verificoPerfil5()) {
            die($this->mensajes->sinPermisos());
        }        
    }
    
    function index() {

        if(isset($_SESSION['nro_acta']) && !empty($_SESSION['nro_acta'])) {
            $nro_acta = $_SESSION['nro_acta'];
        }else {
            $nro_acta = 0;
        }
            
        //traigo todos los datos del acta en un array
        $datos_acta = $this->imprimir_acta_alta_model->datosActa($nro_acta);
        
        /*
         * $datos_acta[] = $row->fecha_transaccion;          0
         * $datos_acta[] = $row->unidad_recibe;              1
         * $datos_acta[] = $row->representante_sma;          2
         * $datos_acta[] = $row->representante_unidad;       3
         * $datos_acta[] = $row->representante_supervision;  4
         * $datos_acta[] = $row->observaciones;              5
         */
        
        //cargo nro_acta
        $data['nro_acta'] = $nro_acta;
        
        //cargo fecha
        $data['fecha'] = $datos_acta[0];
        
        //cargo las unidades
        $data['unidad_recibe'] = $this->imprimir_acta_alta_model->cargoNombreUnidad($datos_acta[1]);
        
        //cargo representante sma
        $data['representante_sma'] = $datos_acta[2];
        
        //cargo representante unidad
        $data['representante_unidad'] = $datos_acta[3];
        
        //cargo supervisor 
        $data['supervision'] = $datos_acta[4];
        
        //cargo observaciones
        $data['observaciones'] = $datos_acta[5];
        
        //Armar grilla de entrega de armamento
        
        $datos_fichas = $this->imprimir_acta_alta_model->datosFichas($nro_acta);
        
        /*
         * $datos_fichas[] = $row->nro_serie; 0
         * $datos_fichas[] = $row->marca;     1
         * $datos_fichas[] = $row->calibre;   2
         * $datos_fichas[] = $row->modelo;    3
         */

        $concat = "";
        
        for($i=0; $i<count($datos_fichas); $i=$i+4) {
        
            $nro_serie = $datos_fichas[$i];
            $marca     = $datos_fichas[$i+1];
            $calibre   = $datos_fichas[$i+2];
            $modelo    = $datos_fichas[$i+3];

            $concat .= "<tr> 
                            <td style='text-align: center;'>".$nro_serie."</td> <td>".$marca."</td> <td>".$calibre."</td> <td>".$modelo."</td> 
                       </tr>";            
            }
            
        $data['entrega_fichas'] = $concat;
            
        //Fin armo grilla de entrega de armamentos    
        
        //Armar grilla de entrega de accesorios
            
        $datos_accesorios = $this->imprimir_acta_alta_model->datosAccesorios($nro_acta);
        
        /*
         * $datos_fichas[] = $row->nro_serie;      0
         * $datos_fichas[] = $row->marca;          1
         * $datos_fichas[] = $row->calibre;        2
         * $datos_fichas[] = $row->modelo;         3
         * $datos_fichas[] = $row->nro_accesorio;  4
         */

        $concat = "";            
            
        for($i=0; $i<count($datos_accesorios); $i=$i+5) {
            
            $nro_serie     = $datos_accesorios[$i];
            $marca         = $datos_accesorios[$i+1];
            $calibre       = $datos_accesorios[$i+2];
            $modelo        = $datos_accesorios[$i+3];   
            $nro_accesorio = $datos_accesorios[$i+4];  

            $concat .= "<tr> 
                            <td style='text-align: center;'>".$nro_serie."</td> <td>".$marca."</td> <td>".$calibre."</td> <td>".$modelo."</td> <td>".$nro_accesorio."</td> 
                       </tr>";

        }
        
        $data['entrega_accesorios'] = $concat;
        
        //Fin armo grilla de entrega de accesorios
        
        //cargo la vista
        $this->load->view("imprimir_acta_alta_view", $data);
    }
    
}

?>
