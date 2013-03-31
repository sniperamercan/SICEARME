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
                                   FROM fichas
                                   WHERE nro_interno_catalogo = ".$this->db->escape($nro_catalogo));
        
        return $query->num_rows();
    }
    
    function comprasAsociadas($nro_catalogo) {
        
        $query = $this->db->query("SELECT nro_interno_compra
                                   FROM compras_catalogos
                                   WHERE nro_interno_compra = ".$this->db->escape($nro_catalogo));
        
        $retorno = array();
        
        foreach($query->result() as $row) {
            $retorno[] = $row->nro_interno_compra;
        }
        
        return $retorno;
    }
    
    function obtenerPrecioCatalogo($nro_compra, $nro_catalogo) {
        
        $query = $this->db->query("SELECT precio
                                   FROM compras_catalogos
                                   WHERE nro_interno_compra = ".$this->db->escape($nro_compra)."
                                   AND nro_interno_catalogo = ".$this->db->escape($nro_catalogo));
        
        $row = $query->row();
        
        return $row->precio;
    }
    
    function obtenerCantArmasCatalogo($nro_compra, $nro_catalogo) {
        
        $query = $this->db->query("SELECT cantidad_armas
                                   FROM compras_catalogos
                                   WHERE nro_interno_compra = ".$this->db->escape($nro_compra)."
                                   AND nro_interno_catalogo = ".$this->db->escape($nro_catalogo));
        
        $row = $query->row();
        
        return $row->cantidad_armas;
    }    
    
    function existeCompraAsociada($nro_compra, $nro_catalogo) {
        
        $query = $this->db->query("SELECT *
                                   FROM compras_catalogos
                                   WHERE nro_interno_compra = ".$this->db->escape($nro_compra)."
                                   AND nro_interno_catalogo = ".$this->db->escape($nro_catalogo));
        
        return $query->num_rows();
    }
    
    function obtenerPrecioCompra($nro_compra) {
        
        $query = $this->db->query("SELECT precio
                                   FROM compras
                                   WHERE nro_interno = ".$this->db->escape($nro_compra));
        
        $row = $query->row();
        
        return $row->precio;        
    }
    
    function obtenerCantArmasCompra($nro_compra) {
        
        $query = $this->db->query("SELECT cantidad_armas
                                   FROM compras
                                   WHERE nro_interno = ".$this->db->escape($nro_compra));
                                  
        
        $row = $query->row();
        
        return $row->cantidad_armas;
    }     
    
    function eliminarCatalogo($nro_catalogo) {
        
        //modificar el precio y la cantidad de armas de la compra
        $compras = $this->comprasAsociadas($nro_catalogo);
        
        foreach($compras as $nro_compra) {
            
            if($this->existeCompraAsociada($nro_compra, $nro_catalogo)) {
                
                $precio         = $this->obtenerPrecioCompra($nro_compra) - $this->obtenerPrecioCatalogo($nro_compra, $nro_catalogo);
                $cantidad_armas = $this->obtenerCantArmasCompra($nro_compra) - $this->obtenerCantArmasCatalogo($nro_compra, $nro_catalogo);
                
                $data_where_compra = array(
                    'nro_interno' => $nro_compra
                );

                $data_set_compra = array(
                    'precio'         => $precio,
                    'cantidad_armas' => $cantidad_armas
                    
                );

                $this->db->update("compras", $data_where_compra, $data_set_compra);   
            }
        }
        
        $data_catalogo_where = array(
            'nro_interno' => $nro_catalogo
        );
        
        $data_catalogo_compra_where = array(
            'nro_interno_catalogo' => $nro_catalogo
        );        
        
        $data_db_logs = array(
            'tipo_movimiento' => 'delete',
            'tabla'           => 'catalogos',
            'clave_tabla'     => 'nro_interno = '.$nro_catalogo,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        
        
        $this->db->trans_start();
            $this->db->delete('compras_catalogos', $data_catalogo_compra_where);
            $this->db->delete('catalogos', $data_catalogo_where);
            $this->db->insert('db_logs', $data_db_logs); 
        $this->db->trans_complete(); 
        
        //falta borrar toda la documentacion tecnica de archivos
    }
}

?>
