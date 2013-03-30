<?php

class modificar_actas_baja extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('modificar_actas_baja_model');
        $this->load->library('mensajes');
        $this->load->library('perms'); 
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()) {
            die($this->mensajes->sinPermisos());
        }         
        
        //Modulo solo visible 5 - Administradores O.C.I 
        if(!$this->perms->verificoPerfil5()) {
            die($this->mensajes->sinPermisos());
        }   
    }
    
    function index() {
        
        $_SESSION['fichas']     = array(); //inicializo el array de fichas a entregar
        $_SESSION['accesorios'] = array(); //inicializo el array de accesorios a entregar
        
        if(isset($_SESSION['nro_acta']) && !empty($_SESSION['nro_acta'])) {
            $nro_acta = $_SESSION['nro_acta'];
        }else {
            $nro_acta = 0;
        }
            
        //traigo todos los datos del acta en un array
        $datos_acta = $this->modificar_actas_baja_model->datosActa($nro_acta);
        
        /*
         * $datos_acta[] = $row->fecha_transaccion;          0
         * $datos_acta[] = $row->unidad_recibe;              1
         * $datos_acta[] = $row->representante_sma;          2
         * $datos_acta[] = $row->representante_unidad;       3
         * $datos_acta[] = $row->representante_supervision;  4
         * $datos_acta[] = $row->observaciones;              5
         */
        
        //cargo fecha
        $data['fecha'] = $datos_acta[0];
        
        //cargo las unidades
        $nombreunidad = $this->modificar_actas_baja_model->datosUnidad($datos_acta[1]);
        
        $data['unidades'] = "<option selected='selected' value='".$datos_acta[1]."'>".$nombreunidad."</option>";
        //fin cargo unidades      
        
        //cargo representante sma
        $data['representante_sma'] = $datos_acta[2];
        
        //cargo representante unidad
        $data['representante_unidad'] = $datos_acta[3];
        
        //cargo supervisor 
        $data['supervision'] = $datos_acta[4];
        
        //cargo observaciones
        $data['observaciones'] = $datos_acta[5];
        
        //cargo nro de series de armamentos que esten en deposito inicial
        $nro_series = $this->modificar_actas_baja_model->cargoNroSeries();
       
        $data['nro_series'] = "<option> </option>";
        
        foreach($nro_series as $val) {
            $data['nro_series'] .= "<option value='".$val."'>".$val."</option>";
        }
        //fin cargo nro de series de armamento en deposito inicial
        
        //cargo nro de series de armamentos que esten en deposito inicial de accesorios
        $nro_series_accesorios = $this->modificar_actas_baja_model->cargoNroSeriesAccesorios();
        
        $data['nro_series_accesorios'] = "<option> </option>";
        
        foreach($nro_series_accesorios as $val) {
            $data['nro_series_accesorios'] .= "<option value='".$val."'>".$val."</option>";
        }
        //fin cargo nro de series de armamento en deposito inicial        
        
        //Armar grilla de entrega de armamento
        
        $datos_fichas = $this->modificar_actas_baja_model->datosFichas($nro_acta);
        
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

            $_SESSION['fichas'][] = $nro_serie;
            $_SESSION['fichas'][] = $marca;
            $_SESSION['fichas'][] = $calibre;
            $_SESSION['fichas'][] = $modelo;

            $aux_nro_serie = '"'.$nro_serie.'"';
            $aux_marca     = '"'.$marca.'"';
            $aux_calibre   = '"'.$calibre.'"';
            $aux_modelo    = '"'.$modelo.'"';            

            $concat .= "<tr> 
                            <td style='text-align: center;'>".$nro_serie."</td> <td>".$marca."</td> <td>".$calibre."</td> <td>".$modelo."</td> <td><img style='cursor: pointer;' onclick='anularFicha(".$aux_nro_serie.",".$aux_marca.",".$aux_calibre.",".$aux_modelo.");' src='".  base_url()."images/delete.gif'/></td>
                       </tr>";            
            }
            
            $data['entrega_fichas'] = $concat;
            
        //Fin armo grilla de entrega de armamentos    
        
        //Armar grilla de entrega de accesorios
            
        $datos_accesorios = $this->modificar_actas_baja_model->datosAccesorios($nro_acta);
        
        /*
         * $datos_fichas[] = $row->nro_serie; 0
         * $datos_fichas[] = $row->marca;     1
         * $datos_fichas[] = $row->calibre;   2
         * $datos_fichas[] = $row->modelo;    3
         */

        $concat = "";            
            
        for($i=0; $i<count($datos_accesorios); $i=$i+5) {
            
            $nro_serie     = $datos_accesorios[$i];
            $marca         = $datos_accesorios[$i+1];
            $calibre       = $datos_accesorios[$i+2];
            $modelo        = $datos_accesorios[$i+3];   
            $nro_accesorio = $datos_accesorios[$i+4];  
            
            $_SESSION['accesorios'][] = $nro_serie;
            $_SESSION['accesorios'][] = $marca;
            $_SESSION['accesorios'][] = $calibre;
            $_SESSION['accesorios'][] = $modelo;
            $_SESSION['accesorios'][] = $nro_accesorio;

            $aux_nro_serie     = '"'.$nro_serie.'"';
            $aux_marca         = '"'.$marca.'"';
            $aux_calibre       = '"'.$calibre.'"';
            $aux_modelo        = '"'.$modelo.'"';
            $aux_nro_accesorio = '"'.$nro_accesorio.'"';

            $concat .= "<tr> 
                            <td style='text-align: center;'>".$nro_serie."</td> <td>".$marca."</td> <td>".$calibre."</td> <td>".$modelo."</td> <td>".$nro_accesorio."</td> <td><img style='cursor: pointer;' onclick='anularAccesorio(".$aux_nro_serie.",".$aux_marca.",".$aux_calibre.",".$aux_modelo.",".$aux_nro_accesorio.");' src='".  base_url()."images/delete.gif'/></td>
                       </tr>";

        }
        
        $data['entrega_accesorios'] = $concat;
        
        //Fin armo grilla de entrega de accesorios
            
        //cargo la vista    
        $this->load->view('modificar_actas_baja_view', $data);  
    }
    
    function cargoMarcas() {
        
        $nro_serie = $_POST['nro_serie'];
        
        $marcas = $this->modificar_actas_baja_model->cargoMarcas($nro_serie);
        
        $concat = "<option> </option>";
        
        foreach($marcas as $val) {
            $concat .= "<option value='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }
    
    function cargoCalibres() {
        
        $nro_serie = $_POST['nro_serie'];
        $marca     = $_POST['marca'];
        
        $calibres = $this->modificar_actas_baja_model->cargoCalibres($nro_serie, $marca);
        
        $concat = "<option> </option>";
        
        foreach($calibres as $val) {
            $concat .= "<option value='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }
    
     function cargoModelos() {
        
        $nro_serie = $_POST['nro_serie'];
        $marca     = $_POST['marca'];
        $calibre   = $_POST['calibre'];
        
        $modelos = $this->modificar_actas_baja_model->cargoModelos($nro_serie, $marca, $calibre);
        
        $concat = "<option> </option>";
        
        foreach($modelos as $val) {
            $concat .= "<option value='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }   
    
    function cargoMarcasAccesorios() {
        
        $nro_serie     = $_POST['nro_serie'];
        
        $marcas = $this->modificar_actas_baja_model->cargoMarcasAccesorios($nro_serie);
        
        $concat = "<option> </option>";
        
        foreach($marcas as $val) {
            $concat .= "<option value='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }
    
    function cargoCalibresAccesorios() {
        
        $nro_serie = $_POST['nro_serie'];
        $marca     = $_POST['marca'];
        
        $calibres = $this->modificar_actas_baja_model->cargoCalibresAccesorios($nro_serie, $marca);
       
        $concat = "<option> </option>";
        
        foreach($calibres as $val) {
            $concat .= "<option value='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }
    
     function cargoModelosAccesorios() {
        
        $nro_serie = $_POST['nro_serie'];
        $marca     = $_POST['marca'];
        $calibre   = $_POST['calibre'];
        
        $modelos = $this->modificar_actas_baja_model->cargoModelosAccesorios($nro_serie, $marca, $calibre);
      
        $concat = "<option> </option>";
        
        foreach($modelos as $val) {
            $concat .= "<option value='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    } 
    
     function cargoNroAccesorios() {
        
        $nro_serie = $_POST['nro_serie'];
        $marca     = $_POST['marca'];
        $calibre   = $_POST['calibre'];
        $modelo    = $_POST['modelo'];
        
        $modelos = $this->modificar_actas_baja_model->cargoNroAccesorios($nro_serie, $marca, $calibre, $modelo);
        
        $concat = "<option> </option>";
        
        foreach($modelos as $val) {
            $concat .= "<option value='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }     
    
    function cargoFichasFiltro() {
        
        $nro_serie = $_SESSION['seleccion_busqueda'];
        $marca     = $_SESSION['seleccion_busqueda1'];
        $calibre   = $_SESSION['seleccion_busqueda2'];
        $modelo    = $_SESSION['seleccion_busqueda3'];
       
        $unidad = $_POST['unidad'];
        
        if(empty($nro_serie)) {
            //cargo nro de series de armamentos que esten en deposito inicial
            $nro_series_array = $this->modificar_actas_baja_model->cargoNroSeries($unidad);

            $aux = '""';
            $nro_series  = "<option> </option>";

            foreach($nro_series_array as $val) {
                $aux = '"'.$val.'"';
                $nro_series .= "<option value='".$val."'>".$val."</option>";
            }
            //fin cargo nro de series de armamento en deposito inicial            
        }else {
            $nro_series  = "<option> </option>";
            $nro_series .= "<option selected='selected' value='".$nro_serie."'>".$nro_serie."</option>";
        }

        $marcas  = "<option> </option>";
        $marcas .= "<option selected='selected' value='".$marca."'>".$marca."</option>";
        
        $calibres  = "<option> </option>";
        $calibres .= "<option selected='selected' value='".$calibre."'>".$calibre."</option>";
        
        $modelos  = "<option> </option>";
        $modelos .= "<option selected='selected' value='".$modelo."'>".$modelo."</option>";        
        
        //retorno los datos
        $retorno = array();
        $retorno[] = $nro_series;
        $retorno[] = $marcas;
        $retorno[] = $calibres;
        $retorno[] = $modelos;
        
        echo json_encode($retorno);        
    }
    
    function cargoAccesoriosFiltro() {
        
        $nro_serie     = $_SESSION['seleccion_busqueda'];
        $marca         = $_SESSION['seleccion_busqueda1'];
        $calibre       = $_SESSION['seleccion_busqueda2'];
        $modelo        = $_SESSION['seleccion_busqueda3'];
        $nro_accesorio = $_SESSION['seleccion_busqueda4'];
        
        $unidad = $_POST['unidad'];
       
        if(empty($nro_serie)) {
            //cargo nro de series de armamentos que esten en deposito inicial
            $nro_series_array = $this->modificar_actas_baja_model->cargoNroSeries($unidad);

            $aux = '""';
            $nro_series  = "<option> </option>";

            foreach($nro_series_array as $val) {
                $aux = '"'.$val.'"';
                $nro_series .= "<option value='".$val."'>".$val."</option>";
            }
            //fin cargo nro de series de armamento en deposito inicial            
        }else {
            $nro_series  = "<option> </option>";
            $nro_series .= "<option selected='selected' value='".$nro_serie."'>".$nro_serie."</option>";
        }

        $marcas  = "<option> </option>";
        $marcas .= "<option selected='selected' value='".$marca."'>".$marca."</option>";
        
        $calibres  = "<option> </option>";
        $calibres .= "<option selected='selected' value='".$calibre."'>".$calibre."</option>";

        $modelos  = "<option> </option>";
        $modelos .= "<option selected='selected' value='".$modelo."'>".$modelo."</option>";
        
        $nro_accesorios  = "<option> </option>";
        $nro_accesorios .= "<option selected='selected' value='".$nro_accesorio."'>".$nro_accesorio."</option>";        
        
        //retorno los datos
        $retorno = array();
        $retorno[] = $nro_series;
        $retorno[] = $marcas;
        $retorno[] = $calibres;
        $retorno[] = $modelos;
        $retorno[] = $nro_accesorios;
        
        unset($_SESSION['unidad']);
        
        echo json_encode($retorno);        
    }    
    
    function agregarFicha() {
        
        $nro_serie  = $_POST['nro_serie'];
        $marca      = $_POST['marca'];
        $calibre    = $_POST['calibre'];
        $modelo     = $_POST['modelo'];
        
        $mensjError = array();
        $retorno = array();
        
        if(empty($nro_serie)) {
            $mensjError[] = 1;
        }
        
        if(empty($marca)) {
            $mensjError[] = 2;
        }
        
        if(empty($calibre)) {
            $mensjError[] = 3;
        }
        
        if(empty($modelo)) {
            $mensjError[] = 4;
        }
            
        //verifico que esa ficha exista en la base de datos en deposito inicial
        if(!$this->modificar_actas_baja_model->existeFicha($nro_serie, $marca, $calibre, $modelo)) {
            $mensjError[] = 5;
        }
        
        //verifico que el nro de catalogo no exista ya en el listado
        $encontre = false;
        $i = 0;
        
        while($i < count($_SESSION['fichas']) && !$encontre) {
            
            if($_SESSION['fichas'][$i] == $nro_serie && $_SESSION['fichas'][$i+1] == $marca
                    && $_SESSION['fichas'][$i+2] == $calibre && $_SESSION['fichas'][$i+3] == $modelo) {
                
                $encontre = true;
            }
            
            $i=$i+4; 
        }
        
        if($encontre) {
           $mensjError[] = 6; 
        }
        
        if(count($mensjError) > 0) {
            
            switch ($mensjError[0]) {
                
                case 1:
                    $retorno[] = $this->mensajes->errorVacio('nro serie');
                    break;
                
                case 2:
                    $retorno[] = $this->mensajes->errorVacio('marca');
                    break;
                
                case 3:
                    $retorno[] = $this->mensajes->errorVacio('calibre');
                    break;
                
                case 4:
                    $retorno[] = $this->mensajes->errorVacio('modelo');
                    break;
                
                case 5:
                    $retorno[] = $this->mensajes->errorExiste('ficha');
                    break;
                
                case 6:
                    $retorno[] = $this->mensajes->errorFichaExiste();
                    break;
            }
        }else {
            
            $retorno[] = 1; 
            
            $_SESSION['fichas'][] = $nro_serie;
            $_SESSION['fichas'][] = $marca;
            $_SESSION['fichas'][] = $calibre;
            $_SESSION['fichas'][] = $modelo;
            
            $aux_nro_serie = '"'.$nro_serie.'"';
            $aux_marca     = '"'.$marca.'"';
            $aux_calibre   = '"'.$calibre.'"';
            $aux_modelo    = '"'.$modelo.'"';
            
            $concat = "<tr> 
                            <td style='text-align: center;'>".$nro_serie."</td> <td>".$marca."</td> <td>".$calibre."</td> <td>".$modelo."</td> <td><img style='cursor: pointer;' onclick='anularFicha(".$aux_nro_serie.",".$aux_marca.",".$aux_calibre.",".$aux_modelo.");' src='".  base_url()."images/delete.gif'/></td>
                       </tr>";
            
            $retorno[] = $concat;
        }
        
        echo json_encode($retorno);        
    }
    
    function anularFicha() {
        
        $nro_serie = $_POST['nro_serie'];
        $marca     = $_POST['marca'];
        $calibre   = $_POST['calibre'];
        $modelo    = $_POST['modelo'];
        
        $retorno = array();
        $encontre = false;
        $i = 0;
        
        while($i < count($_SESSION['fichas']) && !$encontre) {
            
            if($_SESSION['fichas'][$i] == $nro_serie && $_SESSION['fichas'][$i+1] == $marca
                    && $_SESSION['fichas'][$i+2] == $calibre && $_SESSION['fichas'][$i+3] == $modelo) {
                
                $encontre = true;
                unset($_SESSION['fichas'][$i]);   //nro_serie
                unset($_SESSION['fichas'][$i+1]); //marca
                unset($_SESSION['fichas'][$i+2]); //calibre
                unset($_SESSION['fichas'][$i+3]); //modelo
                $_SESSION['fichas'] = array_values($_SESSION['fichas']); //reordeno el array
            }
            $i=$i+4;
        }
        
        if($encontre) {
            
            $concat = '';
            
            for($i=0; $i < count($_SESSION['fichas']); $i=$i+4) {

                $aux_nro_serie = '"'.$_SESSION['fichas'][$i].'"';
                $aux_marca     = '"'.$_SESSION['fichas'][$i+1].'"';
                $aux_calibre   = '"'.$_SESSION['fichas'][$i+2].'"';
                $aux_modelo    = '"'.$_SESSION['fichas'][$i+3].'"';

                $concat .= "<tr> 
                                <td style='text-align: center;'>".$_SESSION['fichas'][$i]."</td> <td>".$_SESSION['fichas'][$i+1]."</td> <td>".$_SESSION['fichas'][$i+2]."</td> <td>".$_SESSION['fichas'][$i+3]."</td> <td><img style='cursor: pointer;' onclick='anularFicha(".$aux_nro_serie.",".$aux_marca.",".$aux_calibre.",".$aux_modelo.");' src='".  base_url()."images/delete.gif'/></td>
                           </tr>";
            }
            if(count($_SESSION['fichas']) == 0) {
                $retorno[] = 0;
            }else {
                $retorno[] = 1;
                $retorno[] = $concat;
            }
        }else {
            $retorno[] = 0;
        }
        
        echo json_encode($retorno);        
    }    
    
    function agregarAccesorio() {
        
        $nro_serie     = $_POST['nro_serie'];
        $marca         = $_POST['marca'];
        $calibre       = $_POST['calibre'];
        $modelo        = $_POST['modelo'];
        $nro_accesorio = $_POST['nro_accesorio'];
        
        $mensjError = array();
        $retorno = array();
        
        if(empty($nro_serie)) {
            $mensjError[] = 1;
        }
        
        if(empty($marca)) {
            $mensjError[] = 2;
        }
        
        if(empty($calibre)) {
            $mensjError[] = 3;
        }
        
        if(empty($modelo)) {
            $mensjError[] = 4;
        }

        if(empty($nro_accesorio)) {
            $mensjError[] = 5;
        }          
        
        //verifico que esa ficha exista en la base de datos en deposito inicial
        if(!$this->modificar_actas_baja_model->existeAccesorio($nro_serie, $marca, $calibre, $modelo, $nro_accesorio)) {
            $mensjError[] = 6;
        }
        
        //verifico que el nro de catalogo no exista ya en el listado
        $encontre = false;
        $i = 0;
        
        while($i < count($_SESSION['accesorios']) && !$encontre) {
            
            if($_SESSION['accesorios'][$i] == $nro_serie && $_SESSION['accesorios'][$i+1] == $marca
                    && $_SESSION['accesorios'][$i+2] == $calibre && $_SESSION['accesorios'][$i+3] == $modelo && $_SESSION['accesorios'][$i+4] == $nro_accesorio) {
                
                $encontre = true;
            }
            
            $i=$i+5; 
        }
        
        if($encontre) {
           $mensjError[] = 7; 
        }
        
        if(count($mensjError) > 0) {
            
            switch ($mensjError[0]) {
                
                case 1:
                    $retorno[] = $this->mensajes->errorVacio('nro serie');
                    break;
                
                case 2:
                    $retorno[] = $this->mensajes->errorVacio('marca');
                    break;
                
                case 3:
                    $retorno[] = $this->mensajes->errorVacio('calibre');
                    break;
                
                case 4:
                    $retorno[] = $this->mensajes->errorVacio('modelo');
                    break;
                
                case 5:
                    $retorno[] = $this->mensajes->errorVacio('nro accesorio');
                    break;                
                
                case 6:
                    $retorno[] = $this->mensajes->errorExiste('accesorio');
                    break;
                
                case 7:
                    $retorno[] = $this->mensajes->errorAccesorioExiste();
                    break;
            }
        }else {
            
            $retorno[] = 1; 
            
            $_SESSION['accesorios'][] = $nro_serie;
            $_SESSION['accesorios'][] = $marca;
            $_SESSION['accesorios'][] = $calibre;
            $_SESSION['accesorios'][] = $modelo;
            $_SESSION['accesorios'][] = $nro_accesorio;
            
            $aux_nro_serie     = '"'.$nro_serie.'"';
            $aux_marca         = '"'.$marca.'"';
            $aux_calibre       = '"'.$calibre.'"';
            $aux_modelo        = '"'.$modelo.'"';
            $aux_nro_accesorio = '"'.$nro_accesorio.'"';
            
            $concat = "<tr> 
                            <td style='text-align: center;'>".$nro_serie."</td> <td>".$marca."</td> <td>".$calibre."</td> <td>".$modelo."</td> <td>".$nro_accesorio."</td> <td><img style='cursor: pointer;' onclick='anularAccesorio(".$aux_nro_serie.",".$aux_marca.",".$aux_calibre.",".$aux_modelo.",".$aux_nro_accesorio.");' src='".  base_url()."images/delete.gif'/></td>
                       </tr>";
            
            $retorno[] = $concat;
        }
        
        echo json_encode($retorno);        
    }
    
    function anularAccesorio() {
        
        $nro_serie     = $_POST['nro_serie'];
        $marca         = $_POST['marca'];
        $calibre       = $_POST['calibre'];
        $modelo        = $_POST['modelo'];
        $nro_accesorio = $_POST['nro_accesorio'];
        
        $retorno = array();
        $encontre = false;
        $i = 0;
        
        while($i < count($_SESSION['accesorios']) && !$encontre) {
            
            if($_SESSION['accesorios'][$i] == $nro_serie && $_SESSION['accesorios'][$i+1] == $marca
                    && $_SESSION['accesorios'][$i+2] == $calibre && $_SESSION['accesorios'][$i+3] == $modelo && $_SESSION['accesorios'][$i+4] == $nro_accesorio) {
                
                $encontre = true;
                unset($_SESSION['accesorios'][$i]);   //nro_serie
                unset($_SESSION['accesorios'][$i+1]); //marca
                unset($_SESSION['accesorios'][$i+2]); //calibre
                unset($_SESSION['accesorios'][$i+3]); //modelo
                unset($_SESSION['accesorios'][$i+4]); //nro_accesorio
                $_SESSION['accesorios'] = array_values($_SESSION['accesorios']); //reordeno el array
            }
            $i=$i+5;
        }
        
        if($encontre) {
            
            $concat = '';
            
            for($i=0; $i < count($_SESSION['accesorios']); $i=$i+5) {

                $aux_nro_serie     = '"'.$_SESSION['accesorios'][$i].'"';
                $aux_marca         = '"'.$_SESSION['accesorios'][$i+1].'"';
                $aux_calibre       = '"'.$_SESSION['accesorios'][$i+2].'"';
                $aux_modelo        = '"'.$_SESSION['accesorios'][$i+3].'"';
                $aux_nro_accesorio = '"'.$_SESSION['accesorios'][$i+4].'"';

                $concat .= "<tr> 
                                <td style='text-align: center;'>".$_SESSION['accesorios'][$i]."</td> <td>".$_SESSION['accesorios'][$i+1]."</td> <td>".$_SESSION['accesorios'][$i+2]."</td> <td>".$_SESSION['accesorios'][$i+3]."</td> <td>".$_SESSION['accesorios'][$i+4]."</td> <td><img style='cursor: pointer;' onclick='anularAccesorio(".$aux_nro_serie.",".$aux_marca.",".$aux_calibre.",".$aux_modelo.",".$aux_nro_accesorio.");' src='".  base_url()."images/delete.gif'/></td>
                           </tr>";
            }
            if(count($_SESSION['accesorios']) == 0) {
                $retorno[] = 0;
            }else {
                $retorno[] = 1;
                $retorno[] = $concat;
            }
        }else {
            $retorno[] = 0;
        }
        
        echo json_encode($retorno);        
    }    
    
    function validarDatos() {
        
        $fecha                = $_POST["fecha"];
        $unidad_entrega       = $_POST["unidad_entrega"];
        $representante_sma    = $_POST["representante_sma"];
        $representante_unidad = $_POST["representante_unidad"];
        $supervision          = $_POST["supervision"];
        $observaciones        = $_POST["observaciones"];
        
        $mensjError = array();
        $retorno = array();
        
        if(empty($fecha)) {
            $mensjError[] = 1;
        }
        
        if(empty($unidad_entrega)) {
            $mensjError[] = 2;
        }
        
        if(empty($representante_sma)) {
            $mensjError[] = 3;
        }
        
        if(empty($representante_unidad)) {
            $mensjError[] = 4;
        }        
        
        if(empty($supervision)) {
            $mensjError[] = 5;
        }            
        
        //verifico si hay armamento para entregar
        if(count($_SESSION['fichas']) == 0) {
            $mensjError[] = 6;
        }
        
        if(count($mensjError) > 0) {
            
            switch($mensjError[0]) {
                
                case 1:
                    $retorno[] = $this->mensajes->errorVacio('fecha');
                    break;
                
                case 2:
                    $retorno[] = $this->mensajes->errorVacio('unidad entrega');
                    break;
                
                case 3:
                    $retorno[] = $this->mensajes->errorVacio('representante sma');
                    break;
                
                case 4:
                    $retorno[] = $this->mensajes->errorVacio('representante unidad');
                    break;
                
                case 5:
                    $retorno[] = $this->mensajes->errorVacio('supervision');
                    break;
                
                case 6:
                    $retorno[] = $this->mensajes->errorVacio('fichas');
                    break;               
            }
        }else {
            
            $nro_acta = $_SESSION['nro_acta'];
            
            if($this->modificar_actas_baja_model->verificoEstadoActa($nro_acta) == 0) {
                $this->modificar_actas_baja_model->modificarActa_db($nro_acta, $fecha, $unidad_entrega, $representante_sma, $representante_unidad, $supervision, $observaciones);
                $retorno[] = 1;                
            }else {
                $retorno[] = "ERROR: El acta seleccionado se encuentra activa, ya no puede recibir modificaciones";
            }
        }
        
        echo json_encode($retorno);
    }
}

?>