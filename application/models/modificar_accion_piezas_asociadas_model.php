<?php

class modificar_accion_piezas_asociadas_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function cargoNroOrden($nro_accion) {
        
        $query = $this->db->query("SELECT DISTINCT nro_orden AS nro
                                   FROM detalles_ordenes_trabajo
                                   WHERE nro_accion = ".$this->db->escape($nro_accion));
        
        $row = $query->row();
        
        return $row->nro;
    }    

    function hayDatosAccion($nro_orden, $nro_accion) {
        
        $query = $this->db->query("SELECT *
                                   FROM cambio_piezas_asociadas_ordenes_trabajo
                                   WHERE nro_orden = ".$this->db->escape($nro_orden)."
                                   AND nro_accion = ".$this->db->escape($nro_accion));
        
        return $query->num_rows();
    }
    
    function cargoDatosAccion($nro_orden, $nro_accion) {
        
        $query = $this->db->query("SELECT nro_cambio, nro_pieza_nueva, nro_pieza_anterior
                                   FROM cambio_piezas_asociadas_ordenes_trabajo
                                   WHERE nro_orden = ".$this->db->escape($nro_orden)."
                                   AND nro_accion = ".$this->db->escape($nro_accion));
        
        $datos = array();
        
        foreach($query->result() as $row) {
            $datos[] = $row->nro_cambio;
            $datos[] = $row->nro_pieza_nueva;
            $datos[] = $row->nro_pieza_anterior;
        }
        
        return $datos;
    }
    
    function obtenerDatos($nro_cambio) {
        
        $query = $this->db->query("SELECT c.nro_orden, c.nro_accion, c.nro_pieza_anterior, c.nro_pieza_nueva, c.nro_parte, c.nombre_parte, o.nro_serie, o.marca, o.calibre, o.modelo, f.nro_interno_catalogo
                                   FROM cambio_piezas_asociadas_ordenes_trabajo c
                                   INNER JOIN ordenes_trabajo o ON c.nro_orden = o.nro_orden
                                   INNER JOIN fichas f ON o.nro_serie = f.nro_serie AND o.marca = f.marca AND o.calibre = f.calibre AND o.modelo = f.modelo
                                   WHERE c.nro_cambio = ".$this->db->escape($nro_cambio));
        
        $datos = array();
        
        $row = $query->row();
        
        $datos[] = $row->nro_orden;
        $datos[] = $row->nro_accion;
        $datos[] = $row->nro_pieza_anterior;
        $datos[] = $row->nro_pieza_nueva;
        $datos[] = $row->nro_parte;
        $datos[] = $row->nombre_parte;
        $datos[] = $row->nro_serie;
        $datos[] = $row->marca;
        $datos[] = $row->calibre;
        $datos[] = $row->modelo;
        $datos[] = $row->nro_interno_catalogo;
        
        return $datos;
    }
    
    function obtenerPiezaFicha($nro_serie, $marca, $calibre, $modelo) {
        
        $query = $this->db->query("SELECT nro_pieza
                                   FROM fichas_piezas
                                   WHERE nro_serie = ".$this->db->escape($nro_serie)." 
                                   AND marca = ".$this->db->escape($marca)." 
                                   AND calibre = ".$this->db->escape($calibre)." 
                                   AND modelo = ".$this->db->escape($modelo));
        
        $row = $query->row();
        
        return $row->nro_pieza;
    }    
    
    function obtenerDatosFicha($nro_orden) {
        
        $query = $this->db->query("SELECT f.nro_pieza
                                   FROM fichas_piezas f
                                   INNER JOIN ordenes_trabajo o ON f.nro_serie = o.nro_serie AND f.marca = o.marca AND f.calibre = o.calibre AND f.modelo = o.modelo
                                   WHERE o.nro_orden = ".$this->db->escape($nro_orden));
                                   
        
        $row = $query->row();
        
        return $row->nro_pieza;
    }    
    
    function eliminarAccionAsociada($nro_cambio, $nro_orden, $nro_accion, $nro_pieza_anterior, $nro_pieza_nueva, $nro_parte, $nombre_parte, $nro_serie, $marca, $calibre, $modelo, $nro_catalogo) {
        
        $this->db->trans_start();
        
            //le devuelvo la pieza al a ficha
            $data_ficha_pieza_set = array(
                'nro_pieza' => $nro_pieza_anterior
            );

            $data_ficha_pieza_where = array(
                'nro_serie' => $nro_serie,
                'marca'     => $marca,
                'calibre'   => $calibre,
                'modelo'    => $modelo
            );
            
            $this->db->update('fichas_piezas', $data_ficha_pieza_set, $data_ficha_pieza_where);
            
            $data_db_logs = array(
                'tipo_movimiento' => 'update',
                'tabla'           => 'fichas_piezas',
                'clave_tabla'     => 'nro_orden = '.$nro_orden.', nro_accion = '.$nro_accion,
                'usuario'         => base64_decode($_SESSION['usuario'])
            );        

            $this->db->insert('db_logs', $data_db_logs);             
            //fin le devuelvo la pieza a la ficha
            
            //retorno pieza al stock de almacen
            $data_stock = array(
                'nro_pieza'            => $nro_pieza_nueva,
                'nro_interno_catalogo' => $nro_catalogo,
                'nro_parte'            => $nro_parte,
                'nombre_parte'         => $nombre_parte
            );
            
            $this->db->insert('stock_repuestos_nro_pieza', $data_stock);
            
            $data_db_logs = array(
                'tipo_movimiento' => 'insert',
                'tabla'           => 'stock_repuestos_nro_pieza',
                'clave_tabla'     => 'nro_orden = '.$nro_orden.', nro_accion = '.$nro_accion,
                'usuario'         => base64_decode($_SESSION['usuario'])
            );        

            $this->db->insert('db_logs', $data_db_logs);             
            //fin retorno pieza al stock
            
            //doy de baja el cambio
            $data_accion_where = array(
                'nro_cambio' => $nro_cambio
            );
            
            $this->db->delete('cambio_piezas_asociadas_ordenes_trabajo', $data_accion_where);
            
            $data_db_logs = array(
                'tipo_movimiento' => 'delete',
                'tabla'           => 'cambio_piezas_asociadas_ordenes_trabajo',
                'clave_tabla'     => 'nro_cambio = '.$nro_cambio,
                'usuario'         => base64_decode($_SESSION['usuario'])
            );        

            $this->db->insert('db_logs', $data_db_logs);       
            //fin doy de baja el cambio
            
        $this->db->trans_complete();
        
    }
    
    function altaAccionPiezasAsociadas($nro_pieza_nueva, $nro_pieza_anterior, $nro_orden, $nro_accion, $nro_parte, $nombre_parte, $nro_catalogo) {
        
        $this->db->trans_start();
        
            $data_accion = array(
                'nro_orden'          => $nro_orden,
                'nro_accion'         => $nro_accion,
                'nro_pieza_anterior' => $nro_pieza_anterior,
                'nro_pieza_nueva'    => $nro_pieza_nueva,
                'nro_parte'          => $nro_parte,
                'nombre_parte'       => $nombre_parte
            );
        
            $this->db->insert('cambio_piezas_asociadas_ordenes_trabajo', $data_accion);
            
            $data_db_logs = array(
                'tipo_movimiento' => 'insert',
                'tabla'           => 'cambio_piezas_asociadas_ordenes_trabajo',
                'clave_tabla'     => 'nro_orden = '.$nro_orden.', nro_accion = '.$nro_accion,
                'usuario'         => base64_decode($_SESSION['usuario'])
            );        

            $this->db->insert('db_logs', $data_db_logs);            
            
            $data_stock_where = array(
                'nro_pieza'            => $nro_pieza_nueva,
                'nro_interno_catalogo' => $nro_catalogo,
                'nro_parte'            => $nro_parte,
                'nombre_parte'         => $nombre_parte
            );
            
            $this->db->delete('stock_repuestos_nro_pieza', $data_stock_where);

            $query = $this->db->query("SELECT nro_serie, marca, calibre, modelo
                                       FROM ordenes_trabajo
                                       WHERE nro_orden = ".$this->db->escape($nro_orden));
            
            $row = $query->row();
            
            $data_ficha_set = array(
                'nro_pieza' => $nro_pieza_nueva
            );
            
            $data_ficha_where = array(
                'nro_serie' => $row->nro_serie,
                'marca'     => $row->marca,
                'calibre'   => $row->calibre,
                'modelo'    => $row->modelo
            );
            
            $this->db->update('fichas_piezas', $data_ficha_set, $data_ficha_where);
            
            $data_db_logs = array(
                'tipo_movimiento' => 'update',
                'tabla'           => 'fichas_piezas',
                'clave_tabla'     => 'nro_pieza = '.$nro_pieza_nueva,
                'usuario'         => base64_decode($_SESSION['usuario'])
            );        

            $this->db->insert('db_logs', $data_db_logs);
        
        $this->db->trans_complete();
    }
    
}

?>
