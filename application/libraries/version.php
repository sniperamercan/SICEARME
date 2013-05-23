<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * @author UDE_PG_CALIT
 * @copyright 2013
 * @Project SICEARME
 * version.php 
 */

//CONTIENE LA VERSION DEL SISTEMA

Class Version extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function getVersion() {
        return 'Ultima actualización - '.$this->getFechaVersion().' <br /> <img src="'.base_url().'images/html5.png" /> <img src="'.base_url().'images/apple.png" /> ';
    }
    
    function getFechaVersion() {
        
        $query = $this->db->query("SELECT MAX(fecha) AS fecha
                                   FROM versiones");
        
        $row = $query->row();
        
        return $row->fecha;
    }
    
    public function getVer() {
        
        $query = $this->db->query("SELECT MAX(version) AS version
                                   FROM versiones");
        
        $row = $query->row();
        
        return $row->version;
    }

    public function getInfo() {
        return 'SICEARME (Sistema centralizado de Armas del Ejército) - <b>'.$this->getVer().'</b>';
    } 
    
    //si o si se necesita en el html a usar, tener la css de las grillas correspondientes. datagrid
    public function getDescripcion() {
        
        $concat = '<div class="datagrid">
                       <table>
                           <thead>
                               <tr>
                                   <th> Fecha       </th>
                                   <th> Información </th>
                               </tr>
                           </thead>
                   ';
        
        $concat .= '<tbody>';
        
        $query = $this->db->query("SELECT fecha, descripcion, critica
                                   FROM descripcion_version
                                   WHERE version = ".$this->db->escape($this->getVer())."
                                   ORDER BY fecha DESC");
        
        foreach($query->result() as $row) {
            if($row->critica){
                $concat .= '<tr style="background-color: #F5A9A9;"> <td>'.$row->fecha.'</td> <td> *'.$row->descripcion.' </td> </tr>';
            }else{
                $concat .= '<tr> <td>'.$row->fecha.'</td> <td> *'.$row->descripcion.' </td> </tr>';
            }
        }
        
        $concat .= '</tbody> 

                    <tfoot>
                        <tr><td colspan="2"><div id="paging"> <font style="color: #1C1C1C; font-size: 13px; margin-left: 3px;"> Versión de sistema - '.$this->getVer().' </font> </div></td></tr>
                    </tfoot>      
                    </table>    
                    </div>
                    ';
        
        return $concat;
        
    }
    
    function copiarVersion() {
        /*
        $conexion = mysql_connect("200.40.47.58:3306", "root", "theuniverse");
        
        mysql_select_db("PESMA", $conexion);
        
        $query = $this->db->query("SELECT version, fecha, descripcion, critica
                                   FROM descripcion_version
                                   WHERE version = ".$this->db->escape($this->getVer()));
        
        foreach($query->result() as $row) {
            
            $result = mysql_query("SELECT *
                                    FROM descripcion_version
                                    WHERE version = ".$this->db->escape($row->version)."
                                    AND fecha = ".$this->db->escape($row->fecha)."
                                    AND descripcion = ".$this->db->escape($row->descripcion)."
                                    AND critica = ".$this->db->escape($row->critica), $conexion);
            
            if(!mysql_num_rows($result)) {
                mysql_query("INSERT INTO descripcion_version (version, fecha, descripcion, critica) 
                             VALUES (".$this->db->escape($row->version).",".$this->db->escape($row->fecha).",".$this->db->escape($row->descripcion).",".$this->db->escape($row->critica).")", $conexion);
            }
            
        }
        
        mysql_close($conexion);*/
    }

}


?>