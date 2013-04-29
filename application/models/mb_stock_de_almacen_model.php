<?php

class mb_stock_de_almacen_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    //para paginado
    function cantidadRegistros($condicion){
        $query = $this->db->query("SELECT * 
                                   FROM stock_repuestos
                                   WHERE ".$condicion);
        
        return $query->num_rows();
    }

    function consulta_db($ini, $param, $condicion, $order){
        
        $result = array();

        $query = $this->db->query("SELECT s.nro_parte, s.nombre_parte, s.nro_interno_catalogo, c.tipo_arma, c.marca, c.calibre, c.modelo, s.cantidad
                                   FROM stock_repuestos s
                                   INNER JOIN catalogos c ON s.nro_interno_catalogo = c.nro_interno
                                   WHERE ".$condicion."
                                   ORDER BY ".$order."
                                   LIMIT ".$ini.",".$param);
        
        foreach($query->result() as $row){
            $result[] = $row->nro_parte;
            $result[] = $row->nombre_parte;
            $result[] = $row->nro_interno_catalogo;
            $result[] = $row->tipo_arma;
            $result[] = $row->marca;
            $result[] = $row->calibre;
            $result[] = $row->modelo;
            $result[] = $row->cantidad;
        }
        
        return $result;
    }
    
    function eliminar($nro_parte, $nombre_parte, $nro_interno_catalogo) {
        
        $this->db->trans_start();
        
            $data_stock_where = array(
                'nro_parte'            => $nro_parte,
                'nombre_parte'         => $nombre_parte,
                'nro_interno_catalogo' => $nro_interno_catalogo
            );

            $this->db->delete('stock_repuestos', $data_stock_where);
            
            $data_db_logs = array(
                'tipo_movimiento' => 'delete',
                'tabla'           => 'stock_repuestos',
                'clave_tabla'     => 'nro_parte = '.$nro_parte.", nombre_parte = ".$nombre_parte.", nro_interno_catalogo = ".$nro_interno_catalogo,
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
