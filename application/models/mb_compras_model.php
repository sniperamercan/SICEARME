<?php

class mb_compras_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    //para paginado
    function cantidadRegistros($condicion){
        $query = $this->db->query("SELECT * 
                                   FROM compras
                                   WHERE ".$condicion);
        
        return $query->num_rows();
    }

    function consulta_db($ini, $param, $condicion, $order){
        
        $result = array();

        $query = $this->db->query("SELECT nro_interno, nro_compra, fecha, empresa_proveedora, pais_empresa, descripcion, modalidad, cantidad_armas, precio
                                   FROM compras
                                   WHERE ".$condicion."
                                   ORDER BY ".$order."
                                   LIMIT ".$ini.",".$param);
        
        foreach($query->result() as $row){
            $result[] = $row->nro_interno;
            $result[] = $row->nro_compra;
            $result[] = $row->fecha;
            $result[] = $row->empresa_proveedora;
            $result[] = $row->pais_empresa;
            $result[] = $row->descripcion;
            $result[] = $row->modalidad;
            $result[] = $row->cantidad_armas;
            $result[] = $row->precio;
        }
        
        return $result;
    } 
    
    function tieneCatalogos($nro_interno) {
        
        $query = $this->db->query("SELECT * 
                                   FROM  compras_catalogos
                                   WHERE nro_interno_compra = ".$this->db->escape($nro_interno));
        
        return $query->num_rows();
    }
    
    function verCatalogos($nro_interno) {
        
        $query = $this->db->query("SELECT cc.nro_interno_catalogo, c.tipo_arma, c.marca, c.calibre, c.modelo, c.sistema, c.empresa, c.pais_origen
                                   FROM compras_catalogos cc
                                   INNER JOIN catalogos c ON cc.nro_interno_catalogo = c.nro_interno
                                   WHERE cc.nro_interno_compra = ".$this->db->escape($nro_interno));
        
        $retorno = array();
        
        foreach($query->result() as $row) {
            $retorno[] = $row->nro_interno_catalogo;
            $retorno[] = $row->tipo_arma;
            $retorno[] = $row->marca;
            $retorno[] = $row->calibre;
            $retorno[] = $row->modelo;
            $retorno[] = $row->sistema;
            $retorno[] = $row->empresa;
            $retorno[] = $row->pais_origen;
        }
        
        return $retorno;
    }
    
    function eliminarCompra($nro_compra) {
        
        $data_compra_where = array(
            'nro_interno' => $nro_compra
        );
        
        $data_compra_catalogo_where = array(
            'nro_interno_compra' => $nro_compra
        );        
        
        $data_db_logs = array(
            'tipo_movimiento' => 'delete',
            'tabla'           => 'compras',
            'clave_tabla'     => 'nro_interno = '.$nro_compra,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        
        
        $this->db->trans_start();
            $this->db->delete('compras_catalogos', $data_compra_catalogo_where);
            $this->db->delete('compras', $data_compra_where);
            $this->db->insert('db_logs', $data_db_logs); 
        $this->db->trans_complete(); 
    }
    
    function fichaAsociada($nro_compra) {
        
        $query = $this->db->query("SELECT *
                                   FROM fichas
                                   WHERE nro_interno_compra = ".$this->db->escape($nro_compra));
        
        return $query->num_rows();
    }
}

?>
