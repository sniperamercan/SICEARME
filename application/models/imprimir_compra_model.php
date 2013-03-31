<?php

class imprimir_compra_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }    
    
    function datosCompra($nro_compra) {
        
        $query = $this->db->query("SELECT nro_compra, fecha, empresa_proveedora, pais_empresa, descripcion, modalidad, cantidad_armas, precio
                                   FROM compras
                                   WHERE nro_interno = ".$this->db->escape($nro_compra));
        
        $retorno = array();
        
        $row = $query->row();
        
        $retorno[] = $row->nro_compra;
        $retorno[] = $row->fecha;
        $retorno[] = $row->empresa_proveedora;
        $retorno[] = $row->pais_empresa;
        $retorno[] = $row->descripcion;
        $retorno[] = $row->modalidad;
        $retorno[] = $row->cantidad_armas;
        $retorno[] = $row->precio;
        
        return $retorno;
    }
    
    function datosCatalogos($nro_compra) {
        
        $query = $this->db->query("SELECT cc.nro_interno_catalogo, c.tipo_arma, c.marca, c.calibre, c.modelo, c.sistema, cc.cantidad_armas, cc.precio
                                   FROM compras_catalogos cc
                                   INNER JOIN catalogos c ON cc.nro_interno_catalogo = c.nro_interno
                                   WHERE cc.nro_interno_compra = ".$this->db->escape($nro_compra));
        
        $retorno = array();
        
        foreach($query->result() as $row) {
            $retorno[] = $row->nro_interno_catalogo;
            $retorno[] = $row->tipo_arma;
            $retorno[] = $row->marca;
            $retorno[] = $row->calibre;
            $retorno[] = $row->modelo;
            $retorno[] = $row->sistema;
            $retorno[] = $row->cantidad_armas;
            $retorno[] = $row->precio;
        }
        
        return $retorno;
    }
}

?>
