<?php

class mb_fichas extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('mb_fichas_model');
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
        
        $usuarios = $this->mb_fichas_model->listadoUsuarios();
        
        $concat = "";
        
        $concat .= '<div class="datagrid">';
        
        $concat .= '<table>';
        
        $concat .= '<thead>';
        
        $concat .= "
            <tr>      
                <th style='text-align: center;'> Usuario      </th>
                <th style='text-align: center;'> Nombre       </th>
                <th style='text-align: center;'> Apellido     </th>
                <th style='text-align: center;'> Permisos     </th>
                <th style='text-align: center;'> Vaciar clave </th>
                <th style='text-align: center;'> Editar       </th>
                <th style='text-align: center;'> Eliminar     </th>
            </tr>   
        ";
        
        $concat .= '</thead>';
        
        $concat .= '<tbody>';
        
        $j=0;
        
        for($i=0;$i<count($usuarios); $i=$i+3) {
        
            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            }             
            
            $concat .= "
                <tr class='".$class."'> 
                    <td> ".$usuarios[$i]."   </td>
                    <td> ".$usuarios[$i+1]." </td>
                    <td> ".$usuarios[$i+2]." </td>
                    <td style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/eye.png' /> </td>
                    <td style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/vaciar.png' /> </td>
                    <td style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/edit.png' /> </td>
                    <td style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/delete.gif' /> </td>
                </tr>
            ";
            
            $j++;
        }                  
        
        $concat .= '</tbody>';
        
        $concat .= '
            <tfoot>
                <tr> <td colspan="7"> <div id="paging"> <br /> </div> </td> </tr>
            </tfoot>
        ';
        
        $concat .= '</table>';      
        
        $concat .= '</div>';   
        
        $data['listado'] = $concat;
        
        $this->load->view("mb_fichas_view", $data);
    }
}

?>
