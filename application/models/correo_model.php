<?php

class correo_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function verificoCorreos() {
        
        $query = $this->db->query("SELECT *
                                   FROM correos
                                   WHERE usuario_recibe = ".$this->db->escape(base64_decode($_SESSION['usuario'])));
        
        return $query->num_rows();
    }
    
    function cargoCorreos_db() {
        
        $query = $this->db->query("SELECT id_correo, leido, usuario_envia, asunto, fecha
                                   FROM correos
                                   WHERE usuario_recibe = ".$this->db->escape(base64_decode($_SESSION['usuario']))." 
                                   ORDER BY id_correo DESC");
        
        $correos = array();
        
        foreach($query->result() as $row) {
            $correos[] = $row->leido;
            $correos[] = $row->id_correo;
            $correos[] = $row->usuario_envia;
            $correos[] = $row->asunto;
            $correos[] = $row->fecha;
        }
        
        return $correos;
    }
    
    function eliminarCorreo_db($id_correo) {
        
        $data_where = array(
            'id_correo' => $id_correo
        );
        
        return $this->db->delete('correos', $data_where);
    }
    
    function vaciarBandeja_db() {
        
        $data_where = array(
            'usuario_recibe' => base64_decode($_SESSION['usuario'])
        );
        
        $this->db->delete("correos", $data_where);
    }
    
}

?>
