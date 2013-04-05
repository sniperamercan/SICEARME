<?php

class alta_generar_ordenes_de_trabajo_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function cargoTiposArmas() {
        
        $query = $this->db->query("SELECT tipo_arma
                                   FROM tipos_armas
                                   ORDER BY tipo_arma");
        
        $tipos_armas = array();
        
        foreach($query->result() as $row) {
            $tipos_armas[] = $row->tipo_arma;
        }
        
        return $tipos_armas;
    }
    
    function cargoMarcas() {
        
        $query = $this->db->query("SELECT marca
                                   FROM marcas
                                   ORDER BY marca");
        
        $marcas = array();
        
        foreach($query->result() as $row) {
            $marcas[] = $row->marca;
        }
        
        return $marcas;
    }
    
    function cargoCalibres() {
        
        $query = $this->db->query("SELECT calibre
                                   FROM calibres
                                   ORDER BY calibre");
        
        $calibres = array();
        
        foreach($query->result() as $row) {
            $calibres[] = $row->calibre;
        }
        
        return $calibres;
    }
    
    function cargoModelos() {
        
        $query = $this->db->query("SELECT modelo
                                   FROM modelos
                                   ORDER BY modelo");
        
        $modelos = array();
        
        foreach($query->result() as $row) {
            $modelos[] = $row->modelo;
        }
        
        return $modelos;
    }
        function cargoSistemas() {
        
        $query = $this->db->query("SELECT sistema
                                   FROM sistemas
                                   ORDER BY sistema");
        
        $sistemas = array();
        
        foreach($query->result() as $row) {
            $sistemas[] = $row->sistema;
        }
        
        return $sistemas;
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
    
    function altaCatalogo($tipo_arma, $marca, $calibre, $modelo, $sistema, $empresa, $pais_empresa, $fabricacion, $vencimiento) {
        
        $data_catalogo = array(
            'tipo_arma'       => $tipo_arma,
            'marca'           => $marca,
            'calibre'         => $calibre,
            'modelo'          => $modelo,
            'sistema'         => $sistema,
            'empresa'         => $empresa,
            'pais_origen'     => $pais_empresa,
            'año_fabricacion' => $fabricacion,
            'vencimiento'     => $vencimiento,
            'usuario_alta'    => base64_decode($_SESSION['usuario']),
            'usuario_edita'   => base64_decode($_SESSION['usuario'])
        );
        
        $this->db->insert('catalogos', $data_catalogo);
        
        $query = $this->db->query("SELECT last_insert_id() as nro_interno");
        
        $row = $query->row();
        
        $nro_interno = $row->nro_interno;
        
        $data_db_logs = array(
            'tipo_movimiento' => 'insert',
            'tabla'           => 'catalogos',
            'clave_tabla'     => 'nro_interno = '.$nro_interno,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        

        $this->db->insert('db_logs', $data_db_logs);
        
        return $nro_interno;
    }
}

?>
