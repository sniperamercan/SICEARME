<?php

class mb_catalogos_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    //para paginado
    function cantidadRegistros($condicion){
        $query = $this->db->query("SELECT * 
                                   FROM catalogos
                                   WHERE ".$condicion);
        
        return $query->num_rows();
    }

    function consulta_db($ini, $param, $condicion, $order){
        
        $result = array();

        $query = $this->db->query("SELECT nro_interno, tipo_arma, marca, calibre, modelo, sistema, año_fabricacion, empresa, pais_origen, vencimiento
                                   FROM catalogos
                                   WHERE ".$condicion."
                                   ORDER BY ".$order."
                                   LIMIT ".$ini.",".$param);
        
        foreach($query->result() as $row){
            $result[] = $row->nro_interno;
            $result[] = $row->tipo_arma;
            $result[] = $row->marca;
            $result[] = $row->calibre;
            $result[] = $row->modelo;
            $result[] = $row->sistema;
            $result[] = $row->año_fabricacion;
            $result[] = $row->empresa;
            $result[] = $row->pais_origen;
            $result[] = $row->vencimiento;
        }
        
        return $result;
    }
    
    function catalogoAsociado($nro_catalogo) {
        
        $query = $this->db->query("SELECT *
                                   FROM compras_catalogos
                                   WHERE nro_interno_catalogo = ".$this->db->escape($nro_catalogo));
        
        return $query->num_rows();
    }
    
    function eliminarCatalogo($nro_catalogo) {
        
        $data_catalogo_where = array(
            'nro_interno' => $nro_catalogo
        );
        
        $data_db_logs = array(
            'tipo_movimiento' => 'delete',
            'tabla'           => 'catalogos',
            'clave_tabla'     => 'nro_interno = '.$nro_catalogo,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        
        
        $this->db->trans_start();
            $this->db->delete('catalogos', $data_catalogo_where);
            $this->db->insert('db_logs', $data_db_logs); 
        $this->db->trans_complete(); 
        
        //falta borrar toda la documentacion tecnica de archivos
    }
}

?>
