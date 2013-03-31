<?php

class modificar_compras_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function datosGenerales($nro_compra) {
        
        $query = $this->db->query("SELECT nro_compra, fecha, empresa_proveedora, pais_empresa, descripcion, modalidad
                                   FROM compras
                                   WHERE nro_interno = ".$this->db->escape($nro_compra));
        
        $row = $query->row();
        
        $retorno = array();
        
        $retorno[] = $row->nro_compra;
        $retorno[] = $row->fecha;
        $retorno[] = $row->empresa_proveedora;
        $retorno[] = $row->pais_empresa;
        $retorno[] = $row->descripcion;
        $retorno[] = $row->modalidad;
        
        return $retorno;
    }
    
    function cargoCatalogosCompra($nro_compra) {
        
        $query = $this->db->query("SELECT cc.nro_interno_catalogo, cc.cantidad_armas, cc.precio, ca.tipo_arma, ca.marca, ca.calibre, ca.modelo, ca.sistema
                                   FROM compras_catalogos cc
                                   INNER JOIN catalogos ca ON cc.nro_interno_catalogo = ca.nro_interno
                                   WHERE cc.nro_interno_compra = ".$this->db->escape($nro_compra));
        
        $retorno = array();
        
        foreach($query->result() as $row) {
            $retorno[] = $row->nro_interno_catalogo;
            $retorno[] = $row->cantidad_armas;
            $retorno[] = $row->precio;
            $retorno[] = $row->tipo_arma;
            $retorno[] = $row->marca;
            $retorno[] = $row->calibre;
            $retorno[] = $row->modelo;
            $retorno[] = $row->sistema;
        }
        
        return $retorno;
    }
    
    function fichasAsocias($nro_compra, $nro_catalago) {
        
        $query = $this->db->query("SELECT *
                                   FROM fichas
                                   WHERE nro_interno_compra = ".$this->db->escape($nro_compra)."
                                   AND nro_interno_catalogo = ".$this->db->escape($nro_catalago));
        
        return $query->num_rows();
    }
    
    function cargoPaises() {
        
        $query = $this->db->query("SELECT nombre
                                   FROM paises
                                   ORDER BY nombre");
        
        $paises = array();
        
        foreach($query->result() as $row) {
            $paises[] = $row->nombre;
        }
        
        return $paises;
    }
    
    function cargoCatalogos() {
        
        $query = $this->db->query("SELECT nro_interno
                                   FROM catalogos
                                   ORDER BY nro_interno");
        
        $catalogos = array();
        
        foreach($query->result() as $row) {
            $catalogos[] = $row->nro_interno;
        }
        
        return $catalogos;
    }    
    
    function cargoEmpresas() {
        
        $query = $this->db->query("SELECT empresa
                                   FROM empresas
                                   ORDER BY empresa");
        
        $empresas = array();
        
        foreach($query->result() as $row) {
            $empresas[] = $row->empresa;
        }
        
        return $empresas;
    }     
    
    function existeCatalogo($nro_catalogo) {
        
        $query = $this->db->query("SELECT *
                                   FROM catalogos
                                   WHERE nro_interno = ".$this->db->escape($nro_catalogo));
        
        return $query->num_rows();
    }
    
    function datosCatalogo($nro_catalogo) {
        
        $query = $this->db->query("SELECT tipo_arma, marca, calibre, modelo, sistema
                                   FROM catalogos
                                   WHERE nro_interno = ".$this->db->escape($nro_catalogo));
        
        $catalogo = array();
        
        $row = $query->row();
        
        $catalogo['tipo_arma'] = $row->tipo_arma;
        $catalogo['marca']     = $row->marca;
        $catalogo['calibre']   = $row->calibre;
        $catalogo['modelo']    = $row->modelo;
        $catalogo['sistema']   = $row->sistema;
        
        return $catalogo;
    }
    
    function modificarCompra($nro_interno, $nro_compra, $fecha, $empresa, $pais_empresa, $descripcion, $modalidad, $precio_total, $cantidad_armas) {
        
        $data_compra_set = array(
            'nro_compra'          => $nro_compra,
            'fecha'               => $fecha,
            'empresa_proveedora'  => $empresa,
            'pais_empresa'        => $pais_empresa,
            'descripcion'         => $descripcion,
            'modalidad'           => $modalidad,
            'cantidad_armas'      => $cantidad_armas,
            'precio'              => $precio_total,
            'usuario_edita'       => base64_decode($_SESSION['usuario'])
        );
        
        $data_compra_where = array(
            'nro_interno' => $nro_interno
        );
        
        $this->db->update('compras', $data_compra_set, $data_compra_where);
        
        $data_db_logs = array(
            'tipo_movimiento' => 'update',
            'tabla'           => 'compras',
            'clave_tabla'     => 'nro_interno = '.$nro_interno,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        

        $this->db->insert('db_logs', $data_db_logs);
        
        $data_compra_catalogo_where = array(
            'nro_interno_compra' => $nro_interno
        );
        
        $this->db->delete('compras_catalogos', $data_compra_catalogo_where);
        
        for($i=0; $i<count($_SESSION['catalogos']); $i=$i+3) {
            $data_compra_catalogo = array(
                'nro_interno_compra'   => $nro_interno,
                'nro_interno_catalogo' => $_SESSION['catalogos'][$i],
                'cantidad_armas'       => $_SESSION['catalogos'][$i+1],
                'precio'               => $_SESSION['catalogos'][$i+2]
            );            

            $this->db->insert('compras_catalogos', $data_compra_catalogo);
        }
    }
}

?>
