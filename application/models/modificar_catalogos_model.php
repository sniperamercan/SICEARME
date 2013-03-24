<?php

class modificar_catalogos_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function catalogoAsociadoFicha($nro_catalogo) {
        
        $query = $this->db->query("SELECT *
                                   FROM fichas
                                   WHERE nro_interno_catalogo = ".$this->db->escape($nro_catalogo));
        
        return $query->num_rows();
    }
    
    function datosCatalogo($nro_catalogo) {
        
        $query = $this->db->query("SELECT tipo_arma, marca, calibre, modelo, sistema
                                   FROM catalogos
                                   WHERE nro_interno = ".$this->db->escape($nro_catalogo));        
        
        $row = $query->row();
        
        $retorno = array();
        
        $retorno[] = $row->tipo_arma;
        $retorno[] = $row->marca;
        $retorno[] = $row->calibre;
        $retorno[] = $row->modelo;
        $retorno[] = $row->sistema;
        
        return $retorno;
    }
    
    function datosGenerales($nro_catalogo) {
        
        $query = $this->db->query("SELECT empresa, pais_origen, año_fabricacion, vencimiento
                                   FROM catalogos
                                   WHERE nro_interno = ".$this->db->escape($nro_catalogo));        
        
        $row = $query->row();
        
        $retorno = array();
        
        $retorno[] = $row->empresa;
        $retorno[] = $row->pais_origen;
        $retorno[] = $row->año_fabricacion;
        $retorno[] = $row->vencimiento;
        
        return $retorno;
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
    
    function modificarCatalogo($nro_catalogo, $tipo_arma, $marca, $calibre, $modelo, $sistema, $empresa, $pais_empresa, $fabricacion, $vencimiento) {
        
        $data_catalogo_set = array(
            'tipo_arma'       => $tipo_arma,
            'marca'           => $marca,
            'calibre'         => $calibre,
            'modelo'          => $modelo,
            'sistema'         => $sistema,
            'empresa'         => $empresa,
            'pais_origen'     => $pais_empresa,
            'año_fabricacion' => $fabricacion,
            'vencimiento'     => $vencimiento,
            'usuario_edita'   => base64_decode($_SESSION['usuario'])
        );
        
        $data_catalogo_where = array(
            'nro_interno' => $nro_catalogo
        );
        
        $this->db->update('catalogos', $data_catalogo_set, $data_catalogo_where);
        
        $data_db_logs = array(
            'tipo_movimiento' => 'update',
            'tabla'           => 'catalogos',
            'clave_tabla'     => 'nro_interno = '.$nro_catalogo,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        

        $this->db->insert('db_logs', $data_db_logs);
    }
}

?>
