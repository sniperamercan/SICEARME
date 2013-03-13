<?php

class listado_fichas extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('listado_fichas_model');
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
        
        $fichas = $this->listado_fichas_model->listadoFichas();
        
        $concat = "";
        
        $concat .= '<div class="datagrid">';
        
        $concat .= '<table>';
        
        $concat .= '<thead>';
        
        $concat .= "
            <tr>      
                <th style='text-align: center;'> Nro serie      </th>
                <th style='text-align: center;'> Marca          </th>
                <th style='text-align: center;'> Modelo         </th>
                <th style='text-align: center;'> Calibre        </th>
                <th style='text-align: center;'> Nro compra     </th>
                <th style='text-align: center;'> Nro catalogo   </th>
                <th style='text-align: center;'> Ubicacion      </th>
                <th style='text-align: center;'> Ver accesorios </th>
                <th style='text-align: center;'> Ver piezas     </th>
            </tr>   
        ";
        
        $concat .= '</thead>';
        
        $concat .= '<tbody>';
        
        $j=0;
        
        for($i=0;$i<count($fichas); $i=$i+7) {
        
            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            }             
            
            $concat .= "
                <tr class='".$class."'> 
                    <td> ".$fichas[$i]."   </td>
                    <td> ".$fichas[$i+1]." </td>
                    <td> ".$fichas[$i+2]." </td>
                    <td> ".$fichas[$i+3]." </td>
                    <td> ".$fichas[$i+4]." </td>
                    <td> ".$fichas[$i+5]." </td>
                    <td> ".$fichas[$i+6]." </td>
                    <td style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/eye.png' /> </td>
                    <td style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/eye.png' /> </td>
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
        
        $this->load->view("listado_fichas_view", $data);
    }
}

?>
