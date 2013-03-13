<?php

class mb_compras_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }   
    
    function listadoCompras() {
        
        $query = $this->db->query("SELECT nro_interno, nro_compra, fecha, empresa_proveedora, pais_empresa, modalidad, cantidad_armas, precio
                                   FROM compras
                                   ORDER BY nro_interno");
        
        $compras = array();
        
        foreach($query->result() as $row) {
            $compras[]  = $row->nro_interno;
            $compras[]  = $row->nro_compra;
            $compras[]  = $row->fecha;
            $compras[]  = $row->empresa_proveedora;
            $compras[]  = $row->pais_empresa;
            $compras[]  = $row->modalidad;
            $compras[]  = $row->cantidad_armas;
            $compras[]  = $row->precio;
        }
        
        return $compras;
    }
    
    
}

?>
