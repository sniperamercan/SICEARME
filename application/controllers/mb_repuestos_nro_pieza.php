<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Segunda Iteracion
* Clase - mb_repuestos_nro_pieza
*/

class mb_repuestos_nro_pieza extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('mb_repuestos_nro_pieza_model');
        $this->load->library('pagination');
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
        $_SESSION['nro_pieza']    = '';
        $_SESSION['nro_parte']    = '';
        $_SESSION['nombre_parte'] = '';
        $_SESSION['nro_catalogo'] = '';
        $this->load->view('mb_repuestos_nro_pieza_view');  
    }
    
    //cantReg = cantidad de registros x pagina
    function consulta($param="",$cantReg=30) {   
     
        //Inicio, armo condiciones where para sql
        if( isset($_POST['nro_pieza']) && isset($_POST['nro_catalogo']) 
                && isset($_POST['nro_parte']) && isset($_POST['nombre_parte'])) { 
            
            $condicion = "";
            $and = 0;
 
            if(!empty($_POST['nro_pieza'])){
                $aux = "%".$_POST['nro_pieza']."%";
                $condicion .= " nro_pieza LIKE ".$this->db->escape($aux);
                $and = 1; //agrego AND en proximo filtro
            }          
            
            if(!empty($_POST['nro_catalogo'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $aux = "%".$_POST['nro_catalogo']."%";
                $condicion .= " nro_interno_catalogo LIKE ".$this->db->escape($aux);
                $and = 1; //agrego AND en proximo filtro
            }  
            
            if(!empty($_POST['nro_parte'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $aux = "%".$_POST['nro_arte']."%";
                $condicion .= " nro_parte LIKE ".$this->db->escape($aux);
                $and = 1; //agrego AND en proximo filtro
            }
            
            if(!empty($_POST['nombre_parte'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $aux = "%".$_POST['nombre_parte']."%";
                $condicion .= " nombre_parte LIKE ".$this->db->escape($aux);
                $and = 1; //agrego AND en proximo filtro
            }
                    
            $_SESSION['condicion'] = $condicion;
        }
        
        if(isset($_SESSION['condicion']) && !empty($_SESSION['condicion'])){
            $condicion = $_SESSION['condicion'];      
        }else{
            $condicion = 1;
        }
        //Fin, armo condiciones where para sql
        
        //Verifico el order si esta seteado si no por defecto de esta consulta
        if(isset($_SESSION['order'])){
            $order = $_SESSION['order'][0]." ".$_SESSION['order'][1];
        }else{
            $order = "nro_pieza";
        }
        //Fin verifico order        
        
        $result = array();
        
        if($param == ""){
            $param = 0;
        }            
        
        $concat = "";
        
        $result = $this->mb_repuestos_nro_pieza_model->consulta_db($param, $cantReg, $condicion, $order);
          
        $j=0;
        
        for($i=0;$i<count($result);$i=$i+8) {
            
            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            }                        
            
            $aux_nro_pieza    = '"'.$result[$i].'"';
            $aux_nro_parte    = '"'.$result[$i+1].'"';
            $aux_nombre_parte = '"'.$result[$i+2].'"';
            $aux_nro_catalogo = '"'.$result[$i+3].'"';
            /* 
             * lo que contiene el array adentro 
            $result[] = $row->nro_pieza; 0
            $result[] = $row->nro_parte; 1
            $result[] = $row->nombre_parte; 2
            $result[] = $row->nro_interno_catalogo; 3
            $result[] = $row->tipo_arma; 4
            $result[] = $row->marca; 5
            $result[] = $row->calibre; 6
            $result[] = $row->modelo; 7
            */
            $concat .= "
                <tr class='".$class."'> 
                    <td  style='text-align: center;'> ".$result[$i]." </td>
                    <td> ".$result[$i+1]." </td>
                    <td> ".$result[$i+2]." </td>
                    <td style='text-align: center;'> ".$result[$i+3]." </td>
                    <td> ".$result[$i+4]." </td>
                    <td> ".$result[$i+5]." </td>
                    <td> ".$result[$i+6]." </td>
                    <td> ".$result[$i+7]." </td>
                    <td onclick='editar(".$aux_nro_pieza.",".$aux_nro_parte.",".$aux_nombre_parte.",".$aux_nro_catalogo.");' style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/edit.png' /> </td>
                    <td onclick='eliminar(".$aux_nro_pieza.",".$aux_nro_parte.",".$aux_nombre_parte.",".$aux_nro_catalogo.");' style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/delete.gif' /> </td>
                </tr>
            ";
            
            $j++;
        }                  
        
        $config['base_url'] = site_url("mb_repuestos_nro_pieza/consulta");
        $config['total_rows'] = $this->mb_repuestos_nro_pieza_model->cantidadRegistros($condicion);
        $config['per_page'] = $cantReg;
        $config['first_link'] = 'Primera';
        $config['last_link'] = 'Ultima';

        $this->pagination->initialize($config);

        $paginado = '<center>';
        
        $paginado .= "<p style='font-size: 13px;'>";
        
        $paginado .= $this->pagination->create_links();
        
        $paginado .= "</p>";
        
        $paginado .= '</center>';
        
        //Retorno de datos json
        $retorno = array();
        $retorno[] = $concat;
        $retorno[] = $paginado;
        
        echo json_encode($retorno);
    }    
       
    function orderBy() {     
        
        $order = $_POST['order'];  
        
        switch($order){
            
            case 0:
                $_SESSION['order'][0] = 'nro_pieza';
                break;
            
            case 1:
                $_SESSION['order'][0] = 'nro_parte';
                break;
            
            case 2:
                $_SESSION['order'][0] = 'nombre_parte';
                break;
            
            case 3:
                $_SESSION['order'][0] = 'nro_interno_catalogo';
                break;
            
        }
       
        if(!isset($_SESSION['order'][1])){
            $_SESSION['order'][1] = "ASC";
        }else if($_SESSION['order'][1] == "DESC"){
            $_SESSION['order'][1] = "ASC";
        }else if($_SESSION['order'][1] == "ASC"){
            $_SESSION['order'][1] = "DESC";
        }        
    }
    
    function editar() {
        $_SESSION['nro_pieza']    = $_POST['nro_pieza'];
        $_SESSION['nro_parte']    = $_POST['nro_parte'];
        $_SESSION['nombre_parte'] = $_POST['nombre_catalogo'];
        $_SESSION['nro_catalogo'] = $_POST['nro_catalogo'];
    }
    
    function eliminar() {
        $_SESSION['nro_pieza']    = $_POST['nro_pieza'];
        $_SESSION['nro_parte']    = $_POST['nro_parte'];
        $_SESSION['nombre_parte'] = $_POST['nombre_catalogo'];
        $_SESSION['nro_catalogo'] = $_POST['nro_catalogo'];
    } 
    
}

?>