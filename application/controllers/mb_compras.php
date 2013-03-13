<?php

class mb_compras extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('mb_compras_model');
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
        
        $compras = $this->mb_compras_model->listadoCompras();
        
        $concat = "";
        
        $concat .= '<div class="datagrid">';
        
        $concat .= '<table>';
        
        $concat .= '<thead>';
        
        $concat .= "
            <tr>      
                <th style='text-align: center;'> Nro interno     </th>
                <th style='text-align: center;'> Nro compra     </th>
                <th style='text-align: center;'> Fecha          </th>
                <th style='text-align: center;'> Empresa        </th>
                <th style='text-align: center;'> Pais           </th>
                <th style='text-align: center;'> Modalidad      </th>
                <th style='text-align: center;'> Cantidad armas </th>
                <th style='text-align: center;'> Precio         </th>
                <th style='text-align: center;'> Ver catalogos  </th>
                <th style='text-align: center;'> Modificar      </th>
                <th style='text-align: center;'> Anular         </th>
            </tr>   
        ";
        
        $concat .= '</thead>';
        
        $concat .= '<tbody>';
        
        $j=0;
        
        for($i=0;$i<count($compras); $i=$i+8) {
        
            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            }             
            
            $concat .= "
                <tr class='".$class."'> 
                    <td> ".$compras[$i]."   </td>
                    <td> ".$compras[$i+1]." </td>
                    <td> ".$compras[$i+2]." </td>
                    <td> ".$compras[$i+3]." </td>
                    <td> ".$compras[$i+4]." </td>
                    <td> ".$compras[$i+5]." </td>
                    <td> ".$compras[$i+6]." </td>
                    <td> ".$compras[$i+7]." </td>
                    <td style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/eye.png' /> </td>
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
        
        $this->load->view("mb_compras_view", $data);
    }
}

?>
