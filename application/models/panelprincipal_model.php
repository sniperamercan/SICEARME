<?php

Class PanelPrincipal_Model extends CI_Model{
	
    function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function informacionUsuario_db(){

        $reg = array();

        $query = $this->db->query("SELECT nombre, apellido 
                                   FROM usuarios                                   
                                   WHERE usuario=".$this->db->escape(base64_decode($_SESSION['usuario'])));

        $row = $query->row();

        $reg['nombre']   = $row->nombre;
        $reg['apellido'] = $row->apellido;

        return $reg;
    }
    
    function destruyoSession_db(){

        $usuario = base64_decode($_SESSION['usuario']);

        $this->db->trans_start();		
            $this->db->delete('usuarios_en_linea', array('usuario' => $usuario));	    
        $this->db->trans_complete();		
    }	
    
    function verificarCorreo_db() {
        
        $query = $this->db->query("SELECT * 
                                   FROM correos 
                                   WHERE usuario_recibe = ".$this->db->escape(base64_decode($_SESSION['usuario']))."
                                   AND leido = 1");
        
        return $query->num_rows();
    }

}

?>