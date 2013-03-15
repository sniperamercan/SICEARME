<?php

class mb_actas_alta extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('mb_actas_alta_model');
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
        
        $actas = $this->mb_actas_alta_model->listadoFichas();
        
        $concat = "";
        
        $concat .= '<div class="datagrid">';
        
        $concat .= '<table>';
        
        $concat .= '<thead>';
        
        $concat .= "
            <tr>      
                <th style='text-align: center;'> Nro acta                 </th>
                <th style='text-align: center;'> Fecha                    </th>
                <th style='text-align: center;'> Unidad entrega           </th>
                <th style='text-align: center;'> Unidad recibe            </th>
                <th style='text-align: center;'> Estado                   </th>
                <th style='text-align: center;'> Ver observaciones        </th>
                <th style='text-align: center;'> Ver Armamento entregado  </th>
                <th style='text-align: center;'> Ver Accesorios entregado </th>
                <th style='text-align: center;'> Cambiar estado           </th>
                <th style='text-align: center;'> Editar                   </th>
                <th style='text-align: center;'> Anular                   </th>
            </tr>   
        ";
        
        $concat .= '</thead>';
        
        $concat .= '<tbody>';
        
        $j=0;
        
        for($i=0;$i<count($actas); $i=$i+5) {
        
            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            }             
            
            $concat .= "
                <tr class='".$class."'> 
                    <td> ".$actas[$i]."   </td>
                    <td> ".$actas[$i+1]." </td>
                    <td> ".$actas[$i+2]." </td>
                    <td> ".$actas[$i+3]." </td>
                    <td> ".$actas[$i+4]." </td>
                    <td style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/eye.png' /> </td>
                    <td style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/eye.png' /> </td>
                    <td style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/eye.png' /> </td>                        
                    <td style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/refresh.png' /> </td>
                    <td style='text-align: center; cursor: pointer;' onclick='editarDatos();'> <img src='".base_url()."images/edit.png' /> </td>
                    <td style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/delete.gif' /> </td>
                </tr>
            ";
            
            $j++;
        }                  
        
        $concat .= '</tbody>';
        
        $concat .= '
            <tfoot>
                <tr> <td colspan="11"> <div id="paging"> <br /> </div> </td> </tr>
            </tfoot>
        ';
        
        $concat .= '</table>';      
        
        $concat .= '</div>';   
        
        $data['listado'] = $concat;
        
        $this->load->view("mb_actas_alta_view", $data);
    }
}

?>
