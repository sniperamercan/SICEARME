<?php

class accion_ordenes_trabajo_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function cargoNroOrdenes() {
        
        $query = $this->db->query("SELECT nro_orden
                                   FROM ordenes_trabajo
                                   WHERE estado_orden_trabajo = 0
                                   ORDER BY nro_orden");
        
        $retorno = array();
        
        foreach($query->result() as $row) {
            $retorno[] = $row->nro_orden;
        }
        
        return $retorno;
    }
    
    function cargoSecciones() {
        
        $query = $this->db->query("SELECT seccion
                                   FROM secciones
                                   ORDER BY seccion");
        
        $retorno = array();
        
        foreach($query->result() as $row) {
            $retorno[] = $row->seccion;
        }
        
        return $retorno;
    }   
    
    function cargoDatosArma($nro_orden) {
        
        $query = $this->db->query("SELECT o.nro_serie, o.marca, o.calibre, o.modelo, c.tipo_arma
                                   FROM ordenes_trabajo o
                                   INNER JOIN fichas f ON o.nro_serie = f.nro_serie 
                                   AND o.marca = f.marca
                                   AND o.calibre = f.calibre
                                   AND o.modelo = f.modelo
                                   INNER JOIN catalogos c ON f.nro_interno_catalogo = c.nro_interno
                                   WHERE nro_orden = ".$this->db->escape($nro_orden));
        
        $row = $query->row();
        
        $datos = array();
        $datos[] = $row->nro_serie;
        $datos[] = $row->marca;
        $datos[] = $row->calibre;
        $datos[] = $row->modelo;
        $datos[] = $row->tipo_arma;
        
        return $datos;
    }
    
    function cargoAcciones($nro_orden) {
        
        $query = $this->db->query("SELECT nro_accion, fecha, seccion, tipo_accion
                                   FROM detalles_ordenes_trabajo
                                   WHERE nro_orden = ".$this->db->escape($nro_orden)."
                                   ORDER BY nro_accion");
        
        $retorno = array();
        
        foreach($query->result() as $row) {
            $retorno[] = $row->nro_accion;
            $retorno[] = $row->fecha;
            $retorno[] = $row->seccion;
            $retorno[] = $row->tipo_accion;
        }
        
        return $retorno;
    }
    
    function cargoTipoAccion($nro_accion) {
        
        $query = $this->db->query("SELECT tipo_accion
                                   FROM detalles_ordenes_trabajo
                                   WHERE nro_accion = ".$this->db->escape($nro_accion));
        
        $row = $query->row();
        
        return $row->tipo_accion;
    }
    
    function verInformacionAccionSimple($nro_accion) {
        
        $query = $this->db->query("SELECT nro_orden, fecha, seccion, detalles, tipo_accion
                                   FROM detalles_ordenes_trabajo
                                   WHERE nro_accion = ".$this->db->escape($nro_accion));
        
        $row = $query->row();
        
        $retorno = array();
        
        $retorno[] = $row->nro_orden;
        $retorno[] = $row->fecha;
        $retorno[] = $row->seccion;
        $retorno[] = $row->detalles;
        $retorno[] = $row->tipo_accion;
                
        return $retorno;
    }
    
    function verInformacionAccionSecundaria($nro_orden, $nro_accion) {
        
        $query = $this->db->query("SELECT nro_cambio, nro_parte, nombre_parte, cantidad
                                   FROM cambio_piezas_no_asociadas_ordenes_trabajo
                                   WHERE nro_orden = ".$this->db->escape($nro_orden)." 
                                   AND nro_accion = ".$this->db->escape($nro_accion));
        
        $retorno = array();
        
        foreach($query->result() as $row) {
            $retorno[] = $row->nro_cambio;
            $retorno[] = $row->nro_parte;
            $retorno[] = $row->nombre_parte;
            $retorno[] = $row->cantidad;
        }
                
        return $retorno;        
    }
    
    function verInformacionAccionAsociada($nro_orden, $nro_accion) {
        
        $query = $this->db->query("SELECT nro_cambio, nro_pieza_anterior, nro_pieza_nueva, nro_parte, nombre_parte
                                   FROM cambio_piezas_asociadas_ordenes_trabajo
                                   WHERE nro_orden = ".$this->db->escape($nro_orden)." 
                                   AND nro_accion = ".$this->db->escape($nro_accion));
        
        $retorno = array();
        
        foreach($query->result() as $row) {
            $retorno[] = $row->nro_cambio;
            $retorno[] = $row->nro_pieza_anterior;
            $retorno[] = $row->nro_pieza_nueva;
            $retorno[] = $row->nro_parte;
            $retorno[] = $row->nombre_parte;
        }
                
        return $retorno; 
        
    }
    
    function eliminarAccionSimple($nro_accion) {
        
        $this->db->trans_start();

            $data_accion_where = array(
                'nro_accion' => $nro_accion
            );

            $this->db->delete('detalles_ordenes_trabajo', $data_accion_where);

            $data_db_logs = array(
                'tipo_movimiento' => 'delete',
                'tabla'           => 'detalles_ordenes_trabajo',
                'clave_tabla'     => 'nro_accion = '.$nro_accion,
                'usuario'         => base64_decode($_SESSION['usuario'])
            );        

            $this->db->insert('db_logs', $data_db_logs);        
        
        $this->db->trans_complete();
    }
    
    function eliminarAccionSecundaria($nro_accion) {
        
        $this->db->trans_start();
        
            //obtener informacion de piezas usadas
            $piezas_usadas = $this->piezasSecundariasUsadasAccion($nro_accion);

            //actualizo la nueva cantidad
            for($i=0; $i<count($piezas_usadas); $i=$i+3) {

                $data_stock_where = array(
                    'nro_parte'    => $piezas_usadas[$i],
                    'nombre_parte' => $piezas_usadas[$i+1],
                );

                $cantidad_actual = $this->obtenerCantidadActual($piezas_usadas[$i], $piezas_usadas[$i+1]);
                $cantidad_total = $cantidad_actual + $piezas_usadas[$i+2];

                $data_stock_set = array(
                    'cantidad' => $cantidad_total
                );

                $this->db->update("stock_repuestos", $data_stock_set, $data_stock_where);
            }

            //elmino los registros de cambio de piezas secundarias y el detalle de la orden
            $data_accion_where = array(
                'nro_accion' => $nro_accion
            );

            $this->db->delete("cambio_piezas_no_asociadas_ordenes_trabajo", $data_accion_where);
            $this->db->delete("detalles_ordenes_trabajo", $data_accion_where);
        
        $this->db->trans_complete();        
                
    }
    
    function obtenerCantidadActual($nro_parte, $nombre_parte) {
        
        $query = $this->db->query("SELECT cantidad
                                   FROM stock_repuestos
                                   WHERE nro_parte = ".$this->db->escape($nro_parte)." 
                                   AND nombre_parte = ".$this->db->escape($nombre_parte));
        
        $row = $query->row();
        
        return $row->cantidad;
    }
    
    function piezasSecundariasUsadasAccion($nro_accion) {
        
        $query = $this->db->query("SELECT nro_parte, nombre_parte, cantidad
                                   FROM cambio_piezas_no_asociadas_ordenes_trabajo
                                   WHERE nro_accion = ".$this->db->escape($nro_accion));
        
        $datos = array();
        
        foreach($query->result() as $row) {
            
            $datos[] = $row->nro_parte;
            $datos[] = $row->nombre_parte;
            $datos[] = $row->cantidad;
        }
        
        return $datos;
    }
    
    function eliminarAccionAsociada($nro_accion) {
        
        $this->db->trans_start();
        
            //obtener informacion de piezas usadas
            $piezas_usadas = $this->piezasAsociadasUsadasAccion($nro_accion);

            //actualizo la nueva cantidad  - nro_pieza_anterior, nro_pieza_nueva, nro_parte, nombre_parte
            for($i=0; $i<count($piezas_usadas); $i=$i+4) {

                $data_stock = array(
                    'nro_pieza'            => $piezas_usadas[$i+1],
                    'nro_interno_catalogo' => $this->obtenerNroCatalogo($nro_accion),
                    'nro_parte'            => $piezas_usadas[$i+2],
                    'nombre_parte'         => $piezas_usadas[$i+3]
                );
                
                $this->db->insert("stock_repuestos_nro_pieza", $data_stock);
                
                $datos_arma = $this->obtenerDatosArma($nro_accion);
                
                $data_ficha_where = array(
                    'nro_serie' => $datos_arma[0],
                    'marca'     => $datos_arma[1],
                    'calibre'   => $datos_arma[2],
                    'modelo'    => $datos_arma[3]
                );
                
                $data_ficha_set = array(
                    'nro_pieza' => $piezas_usadas[$i]
                );
                
                $this->db->update("fichas_piezas", $data_ficha_set, $data_ficha_where);
            }

            //elmino los registros de cambio de piezas secundarias y el detalle de la orden
            $data_accion_where = array(
                'nro_accion' => $nro_accion
            );

            $this->db->delete("cambio_piezas_asociadas_ordenes_trabajo", $data_accion_where);
            $this->db->delete("detalles_ordenes_trabajo", $data_accion_where);
        
        $this->db->trans_complete();        
        
    }
    
    function obtenerDatosArma($nro_accion) {
        
        $query = $this->db->query("SELECT o.nro_serie, o.marca, o.calibre, o.modelo
                                   FROM ordenes_trabajo o
                                   INNER JOIN detalles_ordenes_trabajo d ON o.nro_orden = d.nro_orden
                                   WHERE d.nro_accion = ".$this->db->escape($nro_accion));
        
        $datos_arma = array();
        
        $row = $query->row();
        
        $datos_arma[] = $row->nro_serie;
        $datos_arma[] = $row->marca;
        $datos_arma[] = $row->calibre;
        $datos_arma[] = $row->modelo;
        
        return $datos_arma;
    }
    
    function obtenerNroCatalogo($nro_accion) {
        
        $query = $this->db->query("SELECT f.nro_interno_catalogo
                                   FROM fichas f
                                   INNER JOIN ordenes_trabajo o ON o.nro_serie = f.nro_serie AND o.marca = f.marca AND o.calibre = f.calibre AND o.modelo = f.modelo
                                   INNER JOIN detalles_ordenes_trabajo d ON o.nro_orden = d.nro_orden
                                   WHERE d.nro_accion = ".$this->db->escape($nro_accion));
        
        $row = $query->row();
        
        return $row->nro_interno_catalogo;        
        
    }
    
    function piezasAsociadasUsadasAccion($nro_accion) {
        
        $query = $this->db->query("SELECT nro_pieza_anterior, nro_pieza_nueva, nro_parte, nombre_parte
                                   FROM cambio_piezas_asociadas_ordenes_trabajo
                                   WHERE nro_accion = ".$this->db->escape($nro_accion));
        
        $datos = array();
        
        foreach($query->result() as $row) {
            
            $datos[] = $row->nro_pieza_anterior;
            $datos[] = $row->nro_pieza_nueva;
            $datos[] = $row->nro_parte;
            $datos[] = $row->nombre_parte;
        }
        
        return $datos;
    }
    
    function altaAccionSimple($fecha, $nro_orden, $seccion, $observaciones, $tipo_accion) {
        
        $data_accion_simple = array(
            'nro_orden'   => $nro_orden,
            'fecha'       => $fecha,
            'seccion'     => $seccion,
            'detalles'    => $observaciones,
            'tipo_accion' => $tipo_accion //0 - accion simple 1- accion piezas secundarias 2- accion piezas asociadas.
        );
        
        $this->db->insert('detalles_ordenes_trabajo', $data_accion_simple);
        
        $query = $this->db->query("SELECT last_insert_id() as nro_accion");

        $row = $query->row();

        $nro_accion = $row->nro_accion;           
 
        $data_db_logs = array(
            'tipo_movimiento' => 'insert',
            'tabla'           => 'detalles_ordenes_trabajo',
            'clave_tabla'     => 'nro_orden = '.$nro_orden,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        
            
        $this->db->insert('db_logs', $data_db_logs);
        
        return $nro_accion;
    }
}

?>
