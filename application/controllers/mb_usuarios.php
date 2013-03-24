<?php

class mb_usuarios extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('mb_usuarios_model');
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
        
        $_SESSION['editar_usuario'] = '';
        
        $usuarios = $this->mb_usuarios_model->listadoUsuarios();
        
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
                <th style='text-align: center;'> Inactivar/Activar </th>
                <th style='text-align: center;'> Vaciar clave </th>
                <th style='text-align: center;'> Editar       </th>
                <th style='text-align: center;'> Eliminar     </th>
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
                    <td style='text-align: center; cursor: pointer;' onclick='verPermisos(".$aux_usuario.");'>     <img src='".base_url()."images/eye.png' /> </td>
                    <td style='text-align: center; cursor: pointer;' onclick='cambiarEstado(".$aux_usuario.");'>   <img src='".base_url()."images/refresh.png' /> </td>
                    <td style='text-align: center; cursor: pointer;' onclick='vaciarClave(".$aux_usuario.");'>     <img src='".base_url()."images/vaciar.png' /> </td>
                    <td style='text-align: center; cursor: pointer;' onclick='editarUsuario(".$aux_usuario.");'>   <img src='".base_url()."images/edit.png' /> </td>
                    <td style='text-align: center; cursor: pointer;' onclick='eliminarUsuario(".$aux_usuario.");'> <img src='".base_url()."images/delete.gif' /> </td>
                </tr>
            ";
            
            $j++;
        }                  
        
        $concat .= '</tbody>';
        
        $concat .= '
            <tfoot>
                <tr> <td colspan="9"> <div id="paging"> <br /> </div> </td> </tr>
            </tfoot>
        ';
        
        $concat .= '</table>';      
        
        $concat .= '</div>';   
        
        $data['listado'] = $concat;
        
        $this->load->view("mb_usuarios_view", $data);
    }
    
    function setearUsuario() {
        $_SESSION['editar_usuario'] = $_POST['usuario'];
    }
    
    function cambiarEstado() {
        
        $usuario = $_POST['usuario'];
        
        $estado = $this->mb_usuarios_model->obtenerEstado($usuario);
        
        if($estado == 0) {
            $estado = 1;
        }else {
            $estado = 0;
        }
        
        $this->mb_usuarios_model->cambiarEstado($usuario, $estado);
        
        echo $this->mensajes->estadoUsuarioCambiado($usuario);
    }
    
    function vaciarClave() {
        
        $usuario = $_POST['usuario'];
        
        $this->mb_usuarios_model->vaciarClave($usuario);
        
        echo $this->mensajes->vaciarClave($usuario);        
        
    }
    
    function eliminarUsuario() {
        
        $usuario = $_POST['usuario'];
        
        //controlo que el usuario no tenga registros de logs en el sistema
        if(!$this->mb_usuarios_model->registroIngresos($usuario)) {
            $this->mb_usuarios_model->eliminarUsuario($usuario);
            echo $this->mensajes->usuarioEliminado($usuario);
        }else {
            echo "El usuario - ".$usuario." no se puede eliminar del sistema, ya que tiene registros en el mismo";
        }
    }
    
    function verPermisos() {
        
        $usuario = $_POST['usuario'];
        
        if(!$this->mb_usuarios_model->tienePermisos($usuario)) {
            echo "El usuario - ".$usuario." no tiene ningun permiso asignado al sistema";
        }else {
            $permisos = array();
            $permisos = $this->mb_usuarios_model->verPermisos($usuario);
            $concat = "<p style='font-weight: bold;'> Permisos del usuario - ".$usuario." </p><div class='datagrid'><table><thead> <th> Perfil </th> <th> Descripcion </th> </thead>";
            
            $j = 0;
            
            for($i=0; $i<count($permisos); $i=$i+2) {
                if($j % 2 == 0){
                    $class = "";
                }else{
                    $class = "alt";
                } 
                $concat .= "<tbody> <tr class='".$class."'> <td>".$permisos[$i]."</td> <td>".$permisos[$i+1]."</td> </tr> </tbody>";
            
                $j++;
            }
            
            $concat .= "</table></div>";
            
            echo $concat;
        }
    }
}

?>
