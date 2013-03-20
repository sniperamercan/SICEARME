<?php

class alta_fichas_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function cargoNroCompras() {
        
        $query = $this->db->query("SELECT nro_interno
                                   FROM compras
                                   ORDER BY nro_interno");
        
        $nro_compras = array();
        
        foreach($query->result() as $row) {
            $nro_compras[] = $row->nro_interno;
        }
        
        return $nro_compras;
    }
    
    function cargoNroCatalogos($nro_compra) {
        
        $query = $this->db->query("SELECT nro_interno_catalogo
                                   FROM compras_catalogos
                                   WHERE nro_interno_compra = ".$this->db->escape($nro_compra)."
                                   ORDER BY nro_interno_catalogo");
        
        $nro_catalogos = array();
        
        foreach($query->result() as $row) {
            $nro_catalogos[] = $row->nro_interno_catalogo;
        }
        
        return $nro_catalogos;
    }    
    
    function cargoInformacion($nro_catalogo) {
        
        $query = $this->db->query("SELECT marca, calibre, modelo
                                   FROM catalogos
                                   WHERE nro_interno = ".$this->db->escape($nro_catalogo));
        
        $row = $query->row();
        
        $retorno = array();
        $retorno[] = $row->marca;
        $retorno[] = $row->calibre;
        $retorno[] = $row->modelo;
        
        return $retorno;
    }
    
    function existeFicha($nro_serie, $marca, $calibre, $modelo) {
        
        $query = $this->db->query("SELECT *
                                   FROM fichas
                                   WHERE nro_serie = ".$this->db->escape($nro_serie)."
                                   AND marca       = ".$this->db->escape($marca)."
                                   AND calibre     = ".$this->db->escape($calibre)."
                                   AND modelo      = ".$this->db->escape($modelo));
        
        return $query->num_rows();
    }
    
    function existeNroCompra($nro_compra) {
        
        $query = $this->db->query("SELECT *
                                   FROM compras_catalogos
                                   WHERE nro_interno_compra = ".$this->db->escape($nro_compra));
        
        return $query->num_rows();
    }
    
    function existeNroCatalogo($nro_catalogo) {
        
        $query = $this->db->query("SELECT *
                                   FROM compras_catalogos
                                   WHERE nro_interno_catalogo = ".$this->db->escape($nro_catalogo));
        
        return $query->num_rows();        
    }
    
    function agregarFicha($nro_serie, $marca, $calibre, $modelo, $nro_compra, $nro_catalogo) {
        
        $data_ficha = array(
            'nro_serie'           => $nro_serie,
            'marca'               => $marca,
            'modelo'              => $modelo,
            'calibre'             => $calibre,
            'nro_interno_compra'  => $nro_compra,
            'nro_interno_calibre' => $nro_catalogo,
            'usuario_alta'        => base64_decode($_SESSION['usuario']),
            'usuario_edita'       => base64_decode($_SESSION['usuario'])
        );
        
        $data_db_logs = array(
            'tipo_movimiento' => 'insert',
            'tabla'           => 'fichas',
            'clave_tabla'     => 'nro_serie = '.$usuario.' & marca = '.$marca.' & modelo = '.$modelo.' & calibre = '.$calibre,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        

        $this->db->trans_start();
            $this->db->insert('fichas', $data_ficha);
            $this->db->insert('db_logs', $data_db_logs);
        $this->db->trans_complete();            
    }
}

?>