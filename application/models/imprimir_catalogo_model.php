<?php

class imprimir_catalogo_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }    
    
    function datosCatalogo($nro_catalogo) {
        
        $query = $this->db->query("SELECT tipo_arma, marca, calibre, modelo, sistema, año_fabricacion, empresa, pais_origen, vencimiento
                                   FROM catalogos
                                   WHERE nro_interno = ".$this->db->escape($nro_catalogo));
        
        $retorno = array();
        
        $row = $query->row();
        
        $retorno[] = $row->tipo_arma;
        $retorno[] = $row->marca;
        $retorno[] = $row->calibre;
        $retorno[] = $row->modelo;
        $retorno[] = $row->sistema;
        $retorno[] = $row->año_fabricacion;
        $retorno[] = $row->empresa;
        $retorno[] = $row->pais_origen;
        $retorno[] = $row->vencimiento;
        
        return $retorno;
    }
}

?>
