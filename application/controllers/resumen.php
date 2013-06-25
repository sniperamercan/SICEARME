<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Tercera Iteracion
* Clase - resumen
*/

class resumen extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('resumen_model');
        $this->load->library('perms'); 
        $this->load->library('mensajes'); 
        $this->load->library('version');
        
        if(!$this->perms->VerificoUsuario()){
            die($this->mensajes->sinPermisos());
        } 
    }
    
    function index() {
        
        $data['descripcion_version'] = $this->version->getDescripcion();
        $data['version']             = $this->version->getVer();
        
        $concat = "";
        
        //Resumen para - Administradores del sistema
        if($this->perms->verificoPerfil1()) {
            $concat .= $this->administradorSistema();
        }
        
        //Resumen para - Usuario Taller de armamento, Administrador Taller de armamento, Usuario Reserva
        if($this->perms->verificoPerfil6() || $this->perms->verificoPerfil7() || $this->perms->verificoPerfil8()) {
            $concat .= $this->usuarioTaller();
            $concat .= $this->usuarioAlmacen();
        }
        
        //Resumen para - Usuario O.C.I, Administrador O.C.I, Usuario Abastecimiento, Administrador Abastecimiento
        if($this->perms->verificoPerfil2() || $this->perms->verificoPerfil3() || $this->perms->verificoPerfil4() || $this->perms->verificoPerfil5()) {
            $concat .= $this->usuarioOCI();
            $concat .= $this->usuarioAbastecimiento();    
        }
        
        $data['resumen'] = $concat;
        
        $this->load->view('resumen_view', $data);
    }
    
    function administradorSistema() {
        
        $result = $this->resumen_model->administradorSistema();

        /*
         *  $result[] = $row->logusuario;    0
            $result[] = $row->logfecha;      1
            $result[] = $row->loghora;       2
            $result[] = $row->logip;         3
         */

        $concat = "<p class='subtituloform'> Resumen Administradores </p>";
        $tbody  = "";
        $j=0;

        for($i=0;$i<count($result);$i=$i+4) {

            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            }                  

            $tbody .= "
                <tr class='".$class."'> 
                    <td> ".$result[$i]." </td>
                    <td style='text-align: center;'> ".$result[$i+1]." </td>
                    <td style='text-align: center;'> ".$result[$i+2]." </td>
                    <td style='text-align: center;'> ".$result[$i+3]." </td>
                </tr>
            ";

            $j++;
        }


        $concat .= "
            <table>

                <thead style='text-align: center; cursor: pointer;'>
                    <tr>      
                        <th> Usuario      </th>
                        <th> Fecha        </th>
                        <th> Hora         </th>
                        <th> Dirección IP </th>
                    </tr>
                </thead>

                <tbody> 

                    ".$tbody."

                </tbody>   

                <tfoot>
                    <tr> <td colspan='4'> <div id='paging'> <br /> </div> </td> </tr>
                </tfoot>                

            </table>    
        ";
        
        return $concat;
    }
    
    function usuarioTaller() {
        
        $result = $this->resumen_model->usuarioTaller();

        /*
         *  $result[] = $row->nro_orden;    0
            $result[] = $row->fecha;        1
            $result[] = $row->nro_serie;    2
            $result[] = $row->marca;        3
            $result[] = $row->calibre;      4
            $result[] = $row->modelo;       5
            $result[] = $row->nombreunidad; 6
         */

        $concat = "<p class='subtituloform'> Resumen Taller </p>";
        $tbody  = "";
        $j=0;

        for($i=0;$i<count($result);$i=$i+7) {

            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            }                  

            $tbody .= "
                <tr class='".$class."'> 
                    <td style='text-align: center;'> ".$result[$i]." </td>
                    <td style='text-align: center;'> ".$result[$i+1]." </td>
                    <td> ".$result[$i+2]." </td>
                    <td> ".$result[$i+3]." </td>
                    <td> ".$result[$i+4]." </td>
                    <td> ".$result[$i+5]." </td>
                    <td> ".$result[$i+6]." </td>
                </tr>
            ";

            $j++;
        }


        $concat .= "
            <table>

                <thead style='text-align: center; cursor: pointer;'>
                    <tr>      
                        <th> Nº orden   </th>
                        <th> Fecha      </th>
                        <th> Nº serie   </th>
                        <th> Marca      </th>
                        <th> Calibre    </th>
                        <th> Modelo     </th>
                        <th> Unidad     </th>
                    </tr>
                </thead>

                <tbody> 

                    ".$tbody."

                </tbody>   

                <tfoot>
                    <tr> <td colspan='7'> <div id='paging'> <br /> </div> </td> </tr>
                </tfoot>                

            </table>
        ";
        
        return $concat;
    }
    
    function usuarioAlmacen() {
       
        $result = $this->resumen_model->usuarioAlmacen();

        /*
         *  $result[] = $row->nro_parte;               0
            $result[] = $row->nombre_parte;            1
            $result[] = $row->nro_interno_catalogo;    2
            $result[] = $row->cantidad;                3
         */

        $concat = "<p class='subtituloform'> Resumen Almacén </p>";
        $tbody  = "";
        $j=0;

        for($i=0;$i<count($result);$i=$i+4) {

            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            }                  

            $tbody .= "
                <tr class='".$class."'> 
                    <td style='text-align: center;'> ".$result[$i]." </td>
                    <td> ".$result[$i+1]." </td>
                    <td style='text-align: center;'> ".$result[$i+2]." </td>
                    <td style='text-align: center;'> ".$result[$i+3]." </td>
                 </tr>
            ";

            $j++;
        }


        $concat .= "
            <table>

                <thead style='text-align: center; cursor: pointer;'>
                    <tr>      
                        <th> Nº parte       </th>
                        <th> Nombre parte   </th>
                        <th> Nº cat.        </th>
                        <th> Cantidad       </th>
                    </tr>
                </thead>

                <tbody> 

                    ".$tbody."

                </tbody>   

                <tfoot>
                    <tr> <td colspan='4'> <div id='paging'> <br /> </div> </td> </tr>
                </tfoot>                

            </table>    
        ";
        
        return $concat;
    }
    
    function usuarioOCI() {
        
        $result = $this->resumen_model->usuarioOCI();

        /*
            $result[] = $row->nro_interno;         0
            $result[] = $row->nro_compra;          1
            $result[] = $row->fecha;               2
            $result[] = $row->empresa_proveedora;  3
            $result[] = $row->pais_empresa;        4
            $result[] = $row->modalidad;           5
            $result[] = $row->cantidad_armas;      6
            $result[] = $row->precio;              7
         */

        $concat = "<p class='subtituloform'> Resumen O.C.I. </p>";
        $tbody  = "";
        $j=0;

        for($i=0;$i<count($result);$i=$i+8) {

            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            }                  

            $tbody .= "
                <tr class='".$class."'> 
                    <td style='text-align: center;'> ".$result[$i]." </td>
                    <td style='text-align: center;'> ".$result[$i+1]." </td>
                    <td style='text-align: center;'> ".$result[$i+2]." </td>
                    <td> ".$result[$i+3]." </td>
                    <td> ".$result[$i+4]." </td>
                    <td> ".$result[$i+5]." </td>
                    <td style='text-align: center;'> ".$result[$i+6]." </td>
                    <td style='text-align: center;'> ".$result[$i+7]." </td>
                 </tr>
            ";

            $j++;
        }


        $concat .= "
            <table>

                <thead style='text-align: center; cursor: pointer;'>
                    <tr>      
                        <th> Nº          </th>
                        <th> Nº compra   </th>
                        <th> Fecha       </th>
                        <th> Empresa     </th>
                        <th> Pais        </th>
                        <th> Modelo      </th>
                        <th> Cant.       </th>
                        <th> Precio      </th>
                    </tr>
                </thead>

                <tbody> 

                    ".$tbody."

                </tbody>   

                <tfoot>
                    <tr> <td colspan='8'> <div id='paging'> <br /> </div> </td> </tr>
                </tfoot>                

            </table>  
        ";
        
        return $concat;
    }
    
    function usuarioAbastecimiento() {
        
        $result = $this->resumen_model->usuarioAbastecimiento();

        /*
            $result[] = $row->nro_acta;   0
            $result[] = $row->nro_serie;  1
            $result[] = $row->marca;      2
            $result[] = $row->calibre;    3
            $result[] = $row->modelo;     4
         */

        $concat = "<p class='subtituloform'> Resumen Abastecimiento </p>";
        $tbody  = "";
        $j=0;

        for($i=0;$i<count($result);$i=$i+5) {

            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            }                  

            $tbody .= "
                <tr class='".$class."'> 
                    <td style='text-align: center;'> ".$result[$i]." </td>
                    <td> ".$result[$i+1]." </td>
                    <td> ".$result[$i+2]." </td>
                    <td> ".$result[$i+3]." </td>
                    <td> ".$result[$i+4]." </td>
                 </tr>
            ";

            $j++;
        }


        $concat .= "
            <table>

                <thead style='text-align: center; cursor: pointer;'>
                    <tr>      
                        <th> Nº acta   </th>
                        <th> Nº serie  </th>
                        <th> Marca     </th>
                        <th> Calibre   </th>
                        <th> Modelo    </th>
                    </tr>
                </thead>

                <tbody> 

                    ".$tbody."

                </tbody>   

                <tfoot>
                    <tr> <td colspan='5'> <div id='paging'> <br /> </div> </td> </tr>
                </tfoot>                

            </table>   
        ";
        
        return $concat;
    }
    
    function armoGraficas1() {
        
        $graficas = array();
        $graficas = $this->resumen_model->armoGraficas1_db();
        echo json_encode($graficas);
    }
    
    function armoGraficas2() {
        
        $graficas = array();
        $graficas = $this->resumen_model->armoGraficas2_db();
        echo json_encode($graficas);
    }
    
    function armoGraficas3() {
        
        $graficas = array();
        $graficas = $this->resumen_model->armoGraficas3_db();
        echo json_encode($graficas);
    }    
    
}

?>
