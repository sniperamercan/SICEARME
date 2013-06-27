<?php

class accion_mutacion_armamento_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function cargoTipoPieza($nro_orden, $nro_pieza) {
        
        $query = $this->db->query("SELECT f.tipo_pieza
                                   FROM fichas_piezas f
                                   INNER JOIN ordenes_trabajo o ON o.nro_serie = f.nro_serie AND o.marca = f.marca AND o.calibre = f.calibre AND o.modelo = f.modelo
                                   WHERE o.nro_orden = ".$this->db->escape($nro_orden)."
                                   AND f.nro_pieza = ".$this->db->escape($nro_pieza));
        
        $row = $query->row();
        
        return $row->tipo_pieza;
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
        
        $query = $this->db->query("SELECT f.nro_serie, f.marca, f.calibre, f.modelo, f.nro_interno_compra, f.nro_interno_catalogo
                                   FROM ordenes_trabajo o
                                   INNER JOIN fichas f ON o.nro_serie = f.nro_serie 
                                   AND o.marca = f.marca
                                   AND o.calibre = f.calibre
                                   AND o.modelo = f.modelo
                                   WHERE o.nro_orden = ".$this->db->escape($nro_orden));
        
        $row = $query->row();
        
        $datos = array();
        $datos[] = $row->nro_serie;
        $datos[] = $row->marca;
        $datos[] = $row->calibre;
        $datos[] = $row->modelo;
        $datos[] = $row->nro_interno_compra;
        $datos[] = $row->nro_interno_catalogo;
        
        return $datos;
    }
    
    function hayAccesoriosArma($nro_serie, $marca, $calibre, $modelo) {
        
       $query = $this->db->query("SELECT nro_accesorio
                                   FROM fichas_accesorios
                                   WHERE nro_serie = ".$this->db->escape($nro_serie)."
                                   AND marca = ".$this->db->escape($marca)."
                                   AND calibre = ".$this->db->escape($calibre)."
                                   AND modelo = ".$this->db->escape($modelo));  
       
       return $query->num_rows();
    }
    
    function cargoAccesoriosArma($nro_serie, $marca, $calibre, $modelo) {
        
        $query = $this->db->query("SELECT nro_accesorio
                                   FROM fichas_accesorios
                                   WHERE nro_serie = ".$this->db->escape($nro_serie)."
                                   AND marca = ".$this->db->escape($marca)."
                                   AND calibre = ".$this->db->escape($calibre)."
                                   AND modelo = ".$this->db->escape($modelo));
        
        $datos = array();
        
        foreach($query->result() as $row) {
            $datos[] = $row->nro_accesorio;
        }
        
        return $datos;
    }
    
    function cargoDatosAccesorio($nro_serie, $marca, $calibre, $modelo, $nro_accesorio) {
        
        $query = $this->db->query("SELECT tipo_accesorio, descripcion
                                   FROM fichas_accesorios
                                   WHERE nro_serie = ".$this->db->escape($nro_serie)."
                                   AND marca = ".$this->db->escape($marca)."
                                   AND calibre = ".$this->db->escape($calibre)."
                                   AND modelo = ".$this->db->escape($modelo)." 
                                   AND nro_accesorio = ".$this->db->escape($nro_accesorio));
        
        $datos = array();
        
        $row = $query->row();
        
        $datos[] = $row->tipo_accesorio;
        $datos[] = $row->descripcion;
        
        return $datos;        
        
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
    
    //datos_accesorios es un array que contine todos los accesorios de la ficha del armamento
    function accionMutacionArmamento($fecha, $nro_orden, $seccion, $observaciones, $nro_pieza_nueva, $nro_serie, $marca, $calibre, $modelo, $nro_interno_compra, $nro_interno_catalogo, $datos_accesorios) {
        
        $this->db->trans_start();
        
            //Crear una ficha con los datos nuevos
            $data_ficha_nueva = array(
                'nro_serie' => $nro_pieza_nueva,
                'marca'     => $marca,
                'modelo'    => $modelo,
                'calibre'   => $calibre,
                'nro_interno_compra'   => $nro_interno_compra,
                'nro_interno_catalogo' => $nro_interno_catalogo,
                'usuario_alta'  => base64_decode($_SESSION['usuario']),
                'usuario_edita' => base64_decode($_SESSION['usuario'])
            );
            
            $this->db->insert("fichas", $data_ficha_nueva);
            
            //actualizo la ficha anterior con el campo sufrio_mutacion = 1
            $data_ficha_mutada_where = array(
                'nro_serie' => $nro_serie,
                'marca'     => $marca,
                'modelo'    => $modelo,
                'calibre'   => $calibre
            );
            
            $data_ficha_mutada_set = array(
                'sufrio_mutacion' => 1
            );
            
            $this->db->update("fichas", $data_ficha_mutada_set, $data_ficha_mutada_where);
            
            //actualizo la tabla stock con los datos nuevos de la ficha
            $data_stock_unidades_where = array(
                'nro_serie' => $nro_serie,
                'marca'     => $marca,
                'modelo'    => $modelo,
                'calibre'   => $calibre
            );
            
            $data_stock_unidades_set = array(
                'nro_serie' => $nro_pieza_nueva
            );
            
            $this->db->update("stock_unidades", $data_stock_unidades_set, $data_stock_unidades_where);            
        
            //actualizo las piezas de esa ficha
            $this->db->update("fichas_piezas", $data_stock_unidades_set, $data_stock_unidades_where);  
            
            //actualizo los accesorios si tiene
            foreach($datos_accesorios as $nro_accesorio) {
                
                $datos_accesorio = $this->cargoDatosAccesorio($nro_serie, $marca, $calibre, $modelo, $nro_accesorio);
                
                /*
                    $datos[] = $row->tipo_accesorio; 0 
                    $datos[] = $row->descripcion;    1
                 */
                
                //Crear accesorios ficha con los datos nuevos
                $data_ficha_acc_nueva = array(
                    'nro_serie' => $nro_pieza_nueva,
                    'marca'     => $marca,
                    'modelo'    => $modelo,
                    'calibre'   => $calibre,
                    'nro_accesorio'  => $nro_accesorio,
                    'tipo_accesorio' => $datos_accesorio[0],
                    'descripcion'    => $datos_accesorio[1],
                );

                $this->db->insert("fichas_accesorios", $data_ficha_acc_nueva);                
                
                //actualizo la tabla stock con los datos nuevos de la ficha
                $data_stock_unidades_acc_where = array(
                    'nro_serie'     => $nro_serie,
                    'marca'         => $marca,
                    'modelo'        => $modelo,
                    'calibre'       => $calibre,
                    'nro_accesorio' => $nro_accesorio
                );

                $data_stock_unidades_acc_set = array(
                    'nro_serie' => $nro_pieza_nueva
                );                
                
                $this->db->update("stock_unidades_accesorios", $data_stock_unidades_acc_set, $data_stock_unidades_acc_where);           
            }
            
            //ingreso los datos de la mutacion
            $data_accion_mutacion = array(
                'nro_serie'       => $nro_serie,
                'marca'           => $marca,
                'modelo'          => $modelo,
                'calibre'         => $calibre,
                'nro_serie_nuevo' => $nro_pieza_nueva
            );
            
            $this->db->insert("mutaciones_armamentos", $data_accion_mutacion);      
            
            //log de la mutacion
            $data_accion_mutacion_log = array(
                'nro_orden'       => $nro_orden,
                'nro_serie'       => $nro_serie,
                'marca'           => $marca,
                'modelo'          => $modelo,
                'calibre'         => $calibre,
                'nro_serie_nuevo' => $nro_pieza_nueva,
                'fecha_accion'    => $fecha,
                'seccion'         => $seccion,
                'observaciones'   => $observaciones,
                'nro_catalogo'    => $nro_interno_catalogo,
                'usuario'         => base64_decode($_SESSION['usuario'])
            );
            
            $this->db->insert("mutaciones_armamentos_logs", $data_accion_mutacion_log);             
            
        $this->db->trans_complete();
        
    }
}

?>
