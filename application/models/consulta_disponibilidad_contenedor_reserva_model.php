<?php

class consulta_disponibilidad_contenedor_reserva_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function cargoDepositos() {
        
        $query = $this->db->query("SELECT deposito
                                   FROM depositos_reserva
                                   ORDER BY deposito");
        
        $result = array();
        
        foreach($query->result() as $row) {
            $result[] = $row->deposito;
        }
        
        return $result;
    }
    
    function datosArma($nro_catalogo) {
        
        $query = $this->db->query("SELECT tipo_arma, marca, calibre, modelo, sistema
                                   FROM catalogos 
                                   WHERE nro_interno = ".$this->db->escape($nro_catalogo));
        
        $result = array();
        
        $row = $query->row();
        
        $result[] = $row->tipo_arma;
        $result[] = $row->marca;
        $result[] = $row->calibre;
        $result[] = $row->modelo;
        $result[] = $row->sistema;
        
        return $result;
    }
    
    //para paginado
    function cantidadRegistros($condicion) {
        $query = $this->db->query("SELECT s.nro_serie
                                   FROM stock_reserva s
                                   INNER JOIN fichas f ON s.nro_serie = f.nro_serie AND s.marca = f.marca AND s.calibre = f.calibre AND s.modelo = f.modelo
                                   INNER JOIN catalogos c ON f.nro_interno_catalogo = c.nro_interno
                                   WHERE ".$condicion);
        
        return $query->num_rows();
    }

    function consulta_db($ini, $param, $condicion, $order) {
        
        $result = array();

        $query = $this->db->query("SELECT s.nro_serie
                                   FROM stock_reserva s
                                   INNER JOIN fichas f ON s.nro_serie = f.nro_serie AND s.marca = f.marca AND s.calibre = f.calibre AND s.modelo = f.modelo
                                   INNER JOIN catalogos c ON f.nro_interno_catalogo = c.nro_interno
                                   WHERE ".$condicion);
                                   //LIMIT ".$ini.",".$param);
        
        foreach($query->result() as $row){
            $result[] = $row->nro_serie;
        }
        
        return $result;
    } 
    
    function nombreUnidad($unidad) {
        
        $query = $this->db->query("SELECT nombreunidad
                                   FROM unidades
                                   WHERE idunidad = ".$this->db->escape($unidad));
        
        $row = $query->row();
        
        return $row->nombreunidad;
    }
}

?>
