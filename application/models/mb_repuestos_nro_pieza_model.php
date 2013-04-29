<?php

class mb_repuestos_nro_pieza_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    //para paginado
    function cantidadRegistros($condicion){
        
        $query = $this->db->query("SELECT * 
                                   FROM stock_repuestos_nro_pieza
                                   WHERE ".$condicion);
        
        return $query->num_rows();
    }

    function consulta_db($ini, $param, $condicion, $order){
        
        $result = array();

        $query = $this->db->query("SELECT s.nro_pieza, s.nro_parte, s.nombre_parte, s.nro_interno_catalogo, c.tipo_arma, c.marca, c.calibre, c.modelo
                                   FROM stock_repuestos_nro_pieza s
                                   INNER JOIN catalogos c ON s.nro_interno_catalogo = c.nro_interno
                                   WHERE ".$condicion."
                                   ORDER BY ".$order);
                                   //LIMIT ".$ini.",".$param);
        
        foreach($query->result() as $row){
            $result[] = $row->nro_pieza;
            $result[] = $row->nro_parte;
            $result[] = $row->nombre_parte;
            $result[] = $row->nro_interno_catalogo;
            $result[] = $row->tipo_arma;
            $result[] = $row->marca;
            $result[] = $row->calibre;
            $result[] = $row->modelo;
        }
        
        return $result;
    }
    
    function eliminar($nro_pieza, $nro_parte, $nombre_parte, $nro_interno_catalogo) {
        
        $this->db->trans_start();
        
            $data_stock_where = array(
                'nro_pieza'            => $nro_pieza,
                'nro_parte'            => $nro_parte,
                'nombre_parte'         => $nombre_parte,
                'nro_interno_catalogo' => $nro_interno_catalogo
            );

            $this->db->delete('stock_repuestos_nro_pieza', $data_stock_where);
            
            $data_db_logs = array(
                'tipo_movimiento' => 'delete',
                'tabla'           => 'stock_repuestos_nro_pieza',
                'clave_tabla'     => 'nro_pieza = '.$nro_pieza.', nro_parte = '.$nro_parte.", nombre_parte = ".$nombre_parte.", nro_interno_catalogo = ".$nro_interno_catalogo,
                'usuario'         => base64_decode($_SESSION['usuario'])
            );             
            
            $this->db->insert('db_logs', $data_db_logs);
        
        $this->db->trans_complete();
        
        //verifico que la transaccion fue completada
        if ($this->db->trans_status() === FALSE) {
            return 0;
        }else {
            return 1;
        }
    }    
}

?>
