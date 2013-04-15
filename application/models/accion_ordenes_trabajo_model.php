<?php

class accion_ordenes_trabajo_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
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
    
    function altaCompra($nro_compra, $fecha, $empresa, $pais_empresa, $descripcion, $modalidad, $precio_total, $cantidad_armas) {
        
        $data_compra = array(
            'nro_compra'          => $nro_compra,
            'fecha'               => $fecha,
            'empresa_proveedora'  => $empresa,
            'pais_empresa'        => $pais_empresa,
            'descripcion'         => $descripcion,
            'modalidad'           => $modalidad,
            'cantidad_armas'      => $cantidad_armas,
            'precio'              => $precio_total,
            'usuario_alta'        => base64_decode($_SESSION['usuario']),
            'usuario_edita'       => base64_decode($_SESSION['usuario'])
        );
        
        $this->db->insert('compras', $data_compra);
        
        $query = $this->db->query("SELECT last_insert_id() as nro_interno");
        
        $row = $query->row();
        
        $nro_interno = $row->nro_interno;
        
        $data_db_logs = array(
            'tipo_movimiento' => 'insert',
            'tabla'           => 'compras',
            'clave_tabla'     => 'nro_interno = '.$nro_interno,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        

        $this->db->insert('db_logs', $data_db_logs);
        
        for($i=0; $i<count($_SESSION['catalogos']); $i=$i+3) {
            $data_compra_catalogo = array(
                'nro_interno_compra'   => $nro_interno,
                'nro_interno_catalogo' => $_SESSION['catalogos'][$i],
                'cantidad_armas'       => $_SESSION['catalogos'][$i+1],
                'precio'               => $_SESSION['catalogos'][$i+2]
            );            

            $this->db->insert('compras_catalogos', $data_compra_catalogo);
        }
        
        return $nro_interno;
    }
    
}

?>
