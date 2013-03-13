<?php

class listado_catalogos extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('listado_catalogos_model');
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
        
        $catalogos = $this->listado_catalogos_model->listadoCatalogos();
        
        $concat = "";
        
        $concat .= '<div class="datagrid">';
        
        $concat .= '<table>';
        
        $concat .= '<thead>';
        
        $concat .= "
            <tr>      
                <th style='text-align: center;'> Nro interno    </th>
                <th style='text-align: center;'> Fecha          </th>
                <th style='text-align: center;'> Cantidad armas </th>
                <th style='text-align: center;'> Tipo arma      </th>
                <th style='text-align: center;'> Marca          </th>
                <th style='text-align: center;'> Modelo         </th>
                <th style='text-align: center;'> Calibre        </th>
                <th style='text-align: center;'> Sistema        </th>
                <th style='text-align: center;'> Fabricacion    </th>
                <th style='text-align: center;'> Pais           </th>
                <th style='text-align: center;'> Garantina      </th>
            </tr>   
        ";
        
        $concat .= '</thead>';
        
        $concat .= '<tbody>';
        
        $j=0;
        
        for($i=0;$i<count($catalogos); $i=$i+11) {
        
            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            }             
            
            $concat .= "
                <tr class='".$class."'> 
                    <td> ".$catalogos[$i]."   </td>
                    <td> ".$catalogos[$i+1]." </td>
                    <td> ".$catalogos[$i+2]." </td>
                    <td> ".$catalogos[$i+3]." </td>
                    <td> ".$catalogos[$i+4]." </td>
                    <td> ".$catalogos[$i+5]." </td>
                    <td> ".$catalogos[$i+6]." </td>
                    <td> ".$catalogos[$i+7]." </td>
                    <td> ".$catalogos[$i+8]." </td>
                    <td> ".$catalogos[$i+9]." </td>
                    <td> ".$catalogos[$i+10]." </td>
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
        
        $this->load->view("listado_catalogos_view", $data);
    }
}

?>
