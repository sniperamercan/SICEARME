<?php

class modificar_fichas_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function cargoCompraCatalogo($nro_serie, $marca, $calibre, $modelo) {
        
        $query = $this->db->query("SELECT nro_interno_compra, nro_interno_catalogo
                                   FROM fichas
                                   WHERE nro_serie = ".$this->db->escape($nro_serie)."
                                   AND marca       = ".$this->db->escape($marca)."
                                   AND calibre     = ".$this->db->escape($calibre)."
                                   AND modelo      = ".$this->db->escape($modelo));
        
        $retorno = array();
        
        $row = $query->row();
        
        $retorno[] = $row->nro_interno_compra;
        $retorno[] = $row->nro_interno_catalogo;
        
        return $retorno;
    }
    
    function existeAccesoriosFicha($nro_serie, $marca, $calibre, $modelo) {
        
        $query = $this->db->query("SELECT *
                                   FROM fichas_accesorios
                                   WHERE nro_serie = ".$this->db->escape($nro_serie)."
                                   AND marca       = ".$this->db->escape($marca)."
                                   AND calibre     = ".$this->db->escape($calibre)."
                                   AND modelo      = ".$this->db->escape($modelo));
        
        return $query->num_rows();
    }
    
    function existePiezasFicha($nro_serie, $marca, $calibre, $modelo) {

        $query = $this->db->query("SELECT *
                                   FROM fichas_piezas
                                   WHERE nro_serie = ".$this->db->escape($nro_serie)."
                                   AND marca       = ".$this->db->escape($marca)."
                                   AND calibre     = ".$this->db->escape($calibre)."
                                   AND modelo      = ".$this->db->escape($modelo));
        
        return $query->num_rows();        
        
    }
    
    function cargoAccesoriosFicha($nro_serie, $marca, $calibre, $modelo) {
     
        $query = $this->db->query("SELECT nro_accesorio, tipo_accesorio, descripcion
                                   FROM fichas_accesorios
                                   WHERE nro_serie = ".$this->db->escape($nro_serie)."
                                   AND marca       = ".$this->db->escape($marca)."
                                   AND calibre     = ".$this->db->escape($calibre)."
                                   AND modelo      = ".$this->db->escape($modelo)); 
        
        $retorno = array();
        
        foreach($query->result() as $row) {
            $retorno[] = $row->nro_accesorio;
            $retorno[] = $row->tipo_accesorio;
            $retorno[] = $row->descripcion;
        }
        
        return $retorno;
    }
    
    function cargoPiezasFicha($nro_serie, $marca, $calibre, $modelo) {
        
        $query = $this->db->query("SELECT nro_pieza, tipo_pieza, descripcion
                                   FROM fichas_piezas
                                   WHERE nro_serie = ".$this->db->escape($nro_serie)."
                                   AND marca       = ".$this->db->escape($marca)."
                                   AND calibre     = ".$this->db->escape($calibre)."
                                   AND modelo      = ".$this->db->escape($modelo));  
        
        $retorno = array();
        
        foreach($query->result() as $row) {
            $retorno[] = $row->nro_pieza;
            $retorno[] = $row->tipo_pieza;
            $retorno[] = $row->descripcion;
        }
        
        return $retorno;    
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
    
    function cargoAccesorios() {
        
        $query = $this->db->query("SELECT tipo_accesorio
                                   FROM tipos_accesorios
                                   ORDER BY tipo_accesorio");
        
        $tipos_accesorios = array();
        
        foreach($query->result() as $row) {
            $tipos_accesorios[] = $row->tipo_accesorio;
        }
        
        return $tipos_accesorios;        
    }
    
    function cargoPiezas() {
        
        $query = $this->db->query("SELECT tipo_pieza
                                   FROM tipos_piezas
                                   ORDER BY tipo_pieza");
        
        $tipos_piezas = array();
        
        foreach($query->result() as $row) {
            $tipos_piezas[] = $row->tipo_pieza;
        }
        
        return $tipos_piezas;        
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
    
    function datosExtras($nro_catalogo) {
        
        $query = $this->db->query("SELECT tipo_arma, sistema
                                   FROM catalogos
                                   WHERE nro_interno = ".$this->db->escape($nro_catalogo));
        
        $row = $query->row();
        
        $retorno = array();
        $retorno[] = $row->tipo_arma;
        $retorno[] = $row->sistema;
        
        return $retorno;        
    }
    
    function existeAccesorio($nro_serie, $marca, $calibre, $modelo, $nro_accesorio) {
        
        $query = $this->db->query("SELECT *
                                   FROM fichas_accesorios
                                   WHERE nro_serie   = ".$this->db->escape($nro_serie)."
                                   AND marca         = ".$this->db->escape($marca)."
                                   AND calibre       = ".$this->db->escape($calibre)."
                                   AND modelo        = ".$this->db->escape($modelo)."
                                   AND nro_accesorio = ".$this->db->escape($nro_accesorio));
        
        return $query->num_rows();        
    }
    
    function existePieza($nro_serie, $marca, $calibre, $modelo, $nro_pieza) {
        
        $query = $this->db->query("SELECT *
                                   FROM fichas_piezas
                                   WHERE nro_serie   = ".$this->db->escape($nro_serie)."
                                   AND marca         = ".$this->db->escape($marca)."
                                   AND calibre       = ".$this->db->escape($calibre)."
                                   AND modelo        = ".$this->db->escape($modelo)."
                                   AND nro_pieza     = ".$this->db->escape($nro_pieza));
        
        return $query->num_rows();        
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
    
    function modificarFicha($nro_serie, $marca, $calibre, $modelo, $nro_compra, $nro_catalogo, $nro_serie_ant, $marca_ant, $calibre_ant, $modelo_ant) {
        
        //Borro todos los accesorios que puedo
        $query = $this->db->query("SELECT nro_accesorio
                                   FROM fichas_accesorios
                                   WHERE nro_serie = ".$this->db->escape($nro_serie_ant)."
                                   AND marca       = ".$this->db->escape($marca_ant)."
                                   AND calibre     = ".$this->db->escape($calibre_ant)."
                                   AND modelo      = ".$this->db->escape($modelo_ant));

        $array_accesorios = array();

        foreach($query->result() as $row) {
            $nro_accesorio = $row->nro_accesorio;
            if($this->existeHistorialAccesorio($nro_serie_ant, $marca_ant, $calibre_ant, $modelo_ant, $nro_accesorio) == 0) {
                $array_accesorios[] = $row->nro_accesorio;
            }
        }

        foreach($array_accesorios as $nro_accesorio) {
            
            $data_where = array(
                'nro_serie'       => $nro_serie_ant,
                'marca'           => $marca_ant,
                'modelo'          => $modelo_ant,
                'calibre'         => $calibre_ant,
                'nro_accesorio'   => $nro_accesorio
            );
            
            $this->db->delete("stock_unidades_accesorios", $data_where);
            $this->db->delete("fichas_accesorios", $data_where);
        }
        
        $data_where = array(
            'nro_serie'       => $nro_serie_ant,
            'marca'           => $marca_ant,
            'modelo'          => $modelo_ant,
            'calibre'         => $calibre_ant
        );        

        $this->db->delete("stock_unidades", $data_where);
        
        $this->db->delete("fichas_piezas", $data_where);
        
        //elimino la ficha del sistema         
        $this->db->delete("fichas", $data_where);
        
        //vuelvo a crear la ficha con los datos modificados
        $data_ficha = array(
            'nro_serie'            => $nro_serie,
            'marca'                => $marca,
            'modelo'               => $modelo,
            'calibre'              => $calibre,
            'nro_interno_compra'   => $nro_compra,
            'nro_interno_catalogo' => $nro_catalogo,
            'usuario_alta'         => base64_decode($_SESSION['usuario']),
            'usuario_edita'        => base64_decode($_SESSION['usuario'])
        );
        
        $this->db->insert("fichas", $data_ficha);
        
        //ese armemento lo doy de alta al stock de la unidad deposito inicial
        $data_stock_unidad = array(
            'nro_serie'       => $nro_serie,
            'marca'           => $marca,
            'modelo'          => $modelo,
            'calibre'         => $calibre,
            'idunidad'        => 98 //Unidad - Deposito inicial
        );   

        $this->db->insert('stock_unidades', $data_stock_unidad);        
        
        $data_db_logs = array(
            'tipo_movimiento' => 'update',
            'tabla'           => 'fichas',
            'clave_tabla'     => 'nro_serie = '.$nro_serie.', marca = '.$marca.', calibre = '.$calibre.', modelo = '.$modelo,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        

        $this->db->insert('db_logs', $data_db_logs);        
        
        for($i=0; $i < count($_SESSION['accesorios']); $i=$i+3) {

            $nro_accesorio = $_SESSION['accesorios'][$i];

            if(!$this->existeAccesorio($nro_serie, $marca, $calibre, $modelo, $nro_accesorio)) {

                $data_ficha_accesorio = array(
                    'nro_serie'       => $nro_serie,
                    'marca'           => $marca,
                    'modelo'          => $modelo,
                    'calibre'         => $calibre,
                    'nro_accesorio'   => $_SESSION['accesorios'][$i],
                    'tipo_accesorio'  => $_SESSION['accesorios'][$i+1],
                    'descripcion'     => $_SESSION['accesorios'][$i+2]
                );

                $this->db->insert('fichas_accesorios', $data_ficha_accesorio); 

                //doy de alta los accesorios al stock de deposito inicial
                $data_stock_accesorio = array(
                    'nro_serie'       => $nro_serie,
                    'marca'           => $marca,
                    'calibre'         => $calibre,
                    'modelo'          => $modelo,
                    'nro_accesorio'   => $_SESSION['accesorios'][$i],
                    'idunidad'        => 98 //Unidad - Deposito inicial,
                );

                $this->db->insert('stock_unidades_accesorios', $data_stock_accesorio);  
            }

        }

        for($i=0; $i < count($_SESSION['piezas']); $i=$i+3) {

            $nro_pieza = $_SESSION['piezas'][$i];

            if(!$this->existePieza($nro_serie, $marca, $calibre, $modelo, $nro_pieza)) {

                $data_ficha_pieza = array(
                    'nro_serie'       => $nro_serie,
                    'marca'           => $marca,
                    'modelo'          => $modelo,
                    'calibre'         => $calibre,
                    'nro_pieza'       => $_SESSION['piezas'][$i],
                    'tipo_pieza'      => $_SESSION['piezas'][$i+1],
                    'descripcion'     => $_SESSION['piezas'][$i+2]
                );

                $this->db->insert('fichas_piezas', $data_ficha_pieza);
            }
        }         
    }
    
    function existeEntregasAccesorio($nro_serie, $marca, $calibre, $modelo) {
        
        $query = $this->db->query("SELECT *
                                   FROM actas_alta_entrega_accesorios 
                                   WHERE nro_serie = ".$this->db->escape($nro_serie)."
                                   AND marca       = ".$this->db->escape($marca)."
                                   AND calibre     = ".$this->db->escape($calibre)."
                                   AND modelo      = ".$this->db->escape($modelo));
        
        return $query->num_rows();
    }
    
    function existeHistorialAccesorio($nro_serie, $marca, $calibre, $modelo, $nro_accesorio) {
        
        $cont = 0;
        
        $query = $this->db->query("SELECT *
                                   FROM actas_alta_entrega_accesorios
                                   WHERE nro_serie   = ".$this->db->escape($nro_serie)."
                                   AND marca         = ".$this->db->escape($marca)."
                                   AND calibre       = ".$this->db->escape($calibre)."
                                   AND nro_accesorio = ".$this->db->escape($nro_accesorio)."
                                   AND modelo        = ".$this->db->escape($modelo));  
        
        $cont = $cont + $query->num_rows(); 
        
        $query = $this->db->query("SELECT *
                                   FROM actas_baja_devolucion_accesorios
                                   WHERE nro_serie   = ".$this->db->escape($nro_serie)."
                                   AND marca         = ".$this->db->escape($marca)."
                                   AND calibre       = ".$this->db->escape($calibre)."
                                   AND nro_accesorio = ".$this->db->escape($nro_accesorio)."
                                   AND modelo        = ".$this->db->escape($modelo));   
        
        $cont = $cont + $query->num_rows();
        
        return $cont;
    }
    
    function existeHistorialFicha($nro_serie, $marca, $calibre, $modelo) {
        
        $cont = 0;
        
        $query = $this->db->query("SELECT *
                                   FROM actas_alta_entrega_armamento
                                   WHERE nro_serie   = ".$this->db->escape($nro_serie)."
                                   AND marca         = ".$this->db->escape($marca)."
                                   AND calibre       = ".$this->db->escape($calibre)."
                                   AND modelo        = ".$this->db->escape($modelo));
        
        $cont = $cont + $query->num_rows();
        
        
        $query = $this->db->query("SELECT *
                                   FROM actas_baja_devolucion_armamento
                                   WHERE nro_serie   = ".$this->db->escape($nro_serie)."
                                   AND marca         = ".$this->db->escape($marca)."
                                   AND calibre       = ".$this->db->escape($calibre)."
                                   AND modelo        = ".$this->db->escape($modelo)); 
        
        $cont = $cont + $query->num_rows();
        
        return $cont;
    }    
}

?>