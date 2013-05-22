<?php

class resumen_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function administradorSistema() {
        
        $query = $this->db->query("SELECT logusuario, logfecha, loghora, logip
                                   FROM logs_ingresos
                                   ORDER BY logfecha, loghora DESC
                                   LIMIT 0, 10");
        
        $result = array();
        
        foreach($query->result() as $row) {
            $result[] = $row->logusuario;
            $result[] = $row->logfecha;
            $result[] = $row->loghora;
            $result[] = $row->logip;
        }
        
        return $result;     
    }
    
    function usuarioAlmacen() {
     
        $query = $this->db->query("SELECT nro_parte, nombre_parte, nro_interno_catalogo, cantidad
                                   FROM stock_repuestos
                                   ORDER BY cantidad DESC
                                   LIMIT 0, 15");
        
        $result = array();
        
        foreach($query->result() as $row) {
            $result[] = $row->nro_parte;
            $result[] = $row->nombre_parte;
            $result[] = $row->nro_interno_catalogo;
            $result[] = $row->cantidad;
        }
        
        return $result;           
        
    }
    
    function usuarioOCI() {
 
        $query = $this->db->query("SELECT nro_interno, nro_compra, fecha, empresa_proveedora, pais_empresa, modalidad, cantidad_armas, precio
                                   FROM compras
                                   ORDER BY nro_interno DESC
                                   LIMIT 0, 10");
        
        $result = array();
        
        foreach($query->result() as $row) {
            $result[] = $row->nro_interno;
            $result[] = $row->nro_compra;
            $result[] = $row->fecha;
            $result[] = $row->empresa_proveedora;
            $result[] = $row->pais_empresa;
            $result[] = $row->modalidad;
            $result[] = $row->cantidad_armas;
            $result[] = $row->precio;
        }
        
        return $result;  
    }
    
    function usuarioAbastecimiento() {
        
        $query = $this->db->query("SELECT nro_acta, nro_serie, marca, calibre, modelo
                                   FROM actas_alta_entrega_armamento
                                   ORDER BY nro_acta DESC
                                   LIMIT 0, 10");
        
        $result = array();
        
        foreach($query->result() as $row) {
            $result[] = $row->nro_acta;
            $result[] = $row->nro_serie;
            $result[] = $row->marca;
            $result[] = $row->calibre;
            $result[] = $row->modelo;
        }
        
        return $result;  
    }
    
    function usuarioTaller() {
        
        $query = $this->db->query("SELECT nro_orden, fecha, nro_serie, marca, calibre, modelo, nombreunidad
                                   FROM ordenes_trabajo o
                                   INNER JOIN unidades u ON o.idunidad = u.idunidad
                                   WHERE estado_orden_trabajo = 0
                                   ORDER BY nro_orden");
        
        $result = array();
        
        foreach($query->result() as $row) {
            $result[] = $row->nro_orden;
            $result[] = $row->fecha;
            $result[] = $row->nro_serie;
            $result[] = $row->marca;
            $result[] = $row->calibre;
            $result[] = $row->modelo;
            $result[] = $row->nombreunidad;
        }
        
        return $result;
    }
    
    function armoGraficas1_db() {
        
        $query = $this->db->query("SELECT producto ,SUM(cantidad_cajas) AS suma
                                   FROM produccion
                                   GROUP BY producto
                                   ORDER BY suma DESC LIMIT 5");
        
        $result = array();
        
        foreach($query->result() as $row) {
            $result[] = $row->producto;
            $result[] = $row->suma;
        }
        
        return $result;
    }

    function armoGraficas2_db() {
        
        $query = $this->db->query("SELECT producto ,SUM(cant_entregar) AS suma
                                   FROM salida_producto_terminado
                                   GROUP BY producto
                                   ORDER BY suma DESC LIMIT 5");
        
        $result = array();
        
        foreach($query->result() as $row) {
            $result[] = $row->producto;
            $result[] = $row->suma;
        }
        
        return $result;
    }
    
    function armoGraficas3_db() {
        
        $query = $this->db->query("SELECT producto ,SUM(cant_entregar) AS suma
                                   FROM salida_producto_terminado_unidades
                                   GROUP BY producto
                                   ORDER BY suma DESC LIMIT 5");
        
        $result = array();
        
        foreach($query->result() as $row) {
            $result[] = $row->producto;
            $result[] = $row->suma;
        }
        
        return $result;
    }    
    
}

?>
