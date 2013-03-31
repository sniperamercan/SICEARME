<?php

class listado_usuarios extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('listado_usuarios_model');
        $this->load->library('mensajes');
        $this->load->library('perms'); 
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()) {
            die($this->mensajes->sinPermisos());
        }         
        
        //Modulo solo visible para el peril 1 - Administradores del sistema 
        if(!$this->perms->verificoPerfil1()) {
            die($this->mensajes->sinPermisos());
        }
    }
    
    function index() {
        
        $usuarios = $this->listado_usuarios_model->listadoUsuarios();
        
        $concat = "";
        
        $concat .= '<div class="datagrid">';
        
        $concat .= '<table>';
        
        $concat .= '<thead>';
        
        $concat .= "
            <tr>      
                <th style='text-align: center;'> Usuario      </th>
                <th style='text-align: center;'> Nombre       </th>
                <th style='text-align: center;'> Apellido     </th>
                <th style='text-align: center;'> Estado       </th>
                <th style='text-align: center;'> Permisos     </th>
            </tr>   
        ";
        
        $concat .= '</thead>';
        
        $concat .= '<tbody>';
        
        $j=0;
        
        for($i=0;$i<count($usuarios); $i=$i+4) {
        
            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            }        
            
            //estado del usuario
            if($usuarios[$i+3]==0) {
                $estado = 'Inactivo';
            }else {
                $estado = 'Activo';
            }            
            
            $aux_usuario = '"'.$usuarios[$i].'"';
            
            $concat .= "
                <tr class='".$class."'> 
                    <td> ".$usuarios[$i]."   </td>
                    <td> ".$usuarios[$i+1]." </td>
                    <td> ".$usuarios[$i+2]." </td>
                    <td style='text-align: center;'> ".$estado." </td>
                    <td style='text-align: center; cursor: pointer;' onclick='verPermisos(".$aux_usuario.");'> <img src='".base_url()."images/eye.png' /> </td>
                </tr>
            ";
            
            $j++;
        }                  
        
        $concat .= '</tbody>';
        
        $concat .= '
            <tfoot>
                <tr> <td colspan="5"> <div id="paging"> <br /> </div> </td> </tr>
            </tfoot>
        ';
        
        $concat .= '</table>';      
        
        $concat .= '</div>';   
        
        $data['listado'] = $concat;
        
        $this->load->view("listado_usuarios_view", $data);
    }
    
    function verPermisos() {
        
        $usuario = $_POST['usuario'];
        
        if(!$this->listado_usuarios_model->tienePermisos($usuario)) {
            echo "El usuario - ".$usuario." no tiene ningun permiso asignado al sistema";
        }else {
            $permisos = array();
            $permisos = $this->listado_usuarios_model->verPermisos($usuario);
            $concat = "<p style='font-weight: bold;'> Permisos del usuario - ".$usuario." </p><table style='border: 1px solid black; border-collapse: collapse;'><thhead> <th style='background-color: #8C8C8C; color: white;'> Perfil </th> <th style='background-color: #8C8C8C; color: white;'> Descripcion </th> </thead>";
            
            $j = 0;
            
            for($i=0; $i<count($permisos); $i=$i+2) {
                if($j % 2 == 0){
                    $back_color = '#F2FBEF';
                }else {
                    $back_color = '#E6F8E0';
                }
                $concat .= "<tbody> <tr style='background-color: ".$back_color."'> <td style='border: 1px solid black; border-collapse: collapse;'>".$permisos[$i]."</td> <td style='border: 1px solid black; border-collapse: collapse;'>".$permisos[$i+1]."</td> </tr> </tbody>";
            
                $j++;
            }
            
            $concat .= "</table>";
            
            echo $concat;
        }
    }
}

?>
