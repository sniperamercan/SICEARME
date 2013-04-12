<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Primera Iteracion
* Clase - listado_documentos_imagenes_catalogos
*/

class listado_documentos_imagenes_catalogos extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->model('listado_documentos_imagenes_catalogos_model');
        $this->load->library('perms');
        $this->load->library('mensajes');
        
        if(!$this->perms->verificoUsuario()) {
            die($this->mensajes->sinPermisos());
        }
    }

    function index() {
        
        $_SESSION['id_upload'] = array();
        $_SESSION['id_upload'][0] = 1;
        $_SESSION['id_upload'][1] = 0;
        
        //Cargo empresas
        $catalogos = array();
        $catalogos = $this->listado_documentos_imagenes_catalogos_model->cargoCatalogos();
        
        $concat = "<option selected='selected'> </option>";
        
        foreach($catalogos as $val) {
            $concat .= "<option value='".$val."'>".$val."</option>";
        }
        //Fin cargo empresas
            
        $data = array(
            'error'   => ' ',
            'catalogos'=> $concat
        );

        $this->load->view('listado_documentos_imagenes_catalogos_view', $data);
    }
    
    function cargoDocumentos() {
        
        $catalogo = $_POST['catalogo'];
        
        $target_path = $_SERVER['DOCUMENT_ROOT'].'/SICEARME/catalogos_carpeta/'.$catalogo.'/';
        
        $concat = "";
        
        if(is_dir($target_path)) {
        
            $directorio = dir($target_path);

            while ($archivo = $directorio->read()) {
                
                $tipo = strtolower(substr($archivo, -3));

                if($tipo == 'jpg' || $tipo == 'png' 
                        || $tipo == 'gif' || $tipo == 'doc' 
                        || $tipo == 'pdf' || $tipo == 'docx' || $tipo == 'xls' || $tipo == 'xlsx') {                

                    $path = '/SICEARME/catalogos_carpeta/'.$catalogo.'/'.$archivo;
                    $concat .= "<tr><td><a href='".$path."' target='_blank'>".$archivo."</a></td></tr>";
                }
            }
            
            $directorio->close();
        }
        
        echo $concat;
    }
    
    function cargoId() {
        
       $_SESSION['id_upload'][0]++;
       $_SESSION['id_upload'][1]++;
       echo json_encode($_SESSION['id_upload']);
    }
    
    function quitoId() {
        
       $_SESSION['id_upload'][1]--;
       echo $_SESSION['id_upload'][1];
    }
    
    function do_upload($catalogo) {        

        $html = "<!DOCTYPE html> <html lang='es'> <head> <title>SICEARME</title> <meta http-equiv='content-type' content='text/html; charset=UTF-8' /> 
        <link rel='stylesheet' href='".base_url('css/estilo.css')."' type='text/css' />
        <link rel='shortcut icon' href='".base_url('css/template/favicon.png')."' /> </head> <body style='background-color: #E6E6E6;'> <section>";

        $html .='<img style="margin-right: 20px;" src="'.base_url().'images/menu.png" /> <br /><br />';
        
        if(isset($_FILES['userfile']['name'])) {
        
            $target_path = $_SERVER['DOCUMENT_ROOT'].'/SICEARME/catalogos_carpeta/'.$catalogo.'/';

            if(!is_dir($target_path)) {
                mkdir($target_path, 0777);
            }

            //Asigno todos los permisos a la carpeta
            if(!chmod($target_path, 0777)) {
                chmod($target_path, 0777); 
            }

            for($i=0; $i < count($_FILES['userfile']['name']); $i++ ) {

                $mensArray = array();

                if(empty($catalogo)) {
                    $mensArray[] =  1; 
                }    

                $html .= "<br />";

                //echo "<font color='red'>".$_FILES['userfile']['type'][$i]."</font><br />";

                $tipo = $_FILES['userfile']['type'][$i];

                $target_path = $target_path . basename( $_FILES['userfile']['name'][$i]);

                //Controles sobre archivos antes de ser subidos al servidor

                if(empty($_FILES['userfile']['name'][$i])){
                    $mensArray[] =  2;
                }

                if($tipo != 'image/jpeg' && $tipo != 'image/png' 
                        && $tipo != 'image/gif' && $tipo != 'application/msword' 
                        && $tipo != 'application/pdf') {

                    $mensArray[] =  3; 
                }

                if($_FILES['userfile']['size'][$i] > 8500000) {
                    $mensArray[] =  4;
                }

                if(count($mensArray) > 0) {

                    switch($mensArray[0]) {

                        case 1:
                            $html .= "<article class='error'> <img src='".  base_url()."images/error.png' /> <br /> El numero de rut de la empresa, no puede ser vacio para la suba de archivos </article>";
                            break;

                        case 2:
                            $html .= "<article class='error'> <img src='".  base_url()."images/error.png' /> <br /> El archivo con nombre vacio no puede ser subido al servidor </article>";
                            break;

                        case 3:
                            $html .=  "<article class='error'> <img src='".  base_url()."images/error.png' /> <br /> ".$_FILES['userfile']['name'][$i]."<br/>
                                  El archivo que desea ingresar no cumple con el tipo de dato permitido por el sistema <br /> 
                                  Tipos de datos permitidos son <br />
                                  <u>Imagenes</u> - jpg, png, gif <br />
                                  <u>Documentos</u> - doc, pdf, docx <br /> </article>";
                            break;

                        case 4:
                            $html .= "<article class='error'> <img src='".  base_url()."images/error.png' /> <br /> ".$_FILES['userfile']['name'][$i]." <br /> El archivo que desea subir al servidor es demasiado grande </article>";
                            break;

                    }
                }else{
                    if(move_uploaded_file($_FILES['userfile']['tmp_name'][$i], $target_path)) {
                        $html .= "<article class='exito'> <img src='".  base_url()."images/success.png' /> <br /> Archivo ".$_FILES['userfile']['name'][$i]." subido correctamente </article> <br />";
                    }else{
                        $html .= "<article class='error'> <img src='".  base_url()."images/error.png' /> <br /> <u>Error</u> al subir el archivo de nombre ".$_FILES['userfile']['name'][$i]." </article> <br />";
                    }                    
                }
            }
            
        }else {
            $html .= "<article class='error'> <img src='".  base_url()."images/error.png' /> <br /> No selecciono ningun archivo. </article>";
        }

        $_SESSION['irAFrame'] = 'upload';

        $html .= "<br /><br /> <article class='href'> <a href='".base_url()."panelprincipal' style='font-size: 18px; color: white; text-align: center;'> VOLVER AL FORMULARIO DE IMAGENES </a> </article> <br />";

        $html .="</section> </body> </html>";        
        
        echo $html;
    }
    
    function cargoCatalogos() {
        
        $catalogos = $this->listado_documentos_imagenes_catalogos_model->cargoCatalogos();
        
        $concat = "<option> </option>";
        
        foreach($catalogos as $val) {
            if($_SESSION['alta_nro_catalogo'] == $val) {
                $concat .= "<option selected='selected' value='".$val."'>".$val."</option>";
            }else{
                $concat .= "<option value='".$val."'>".$val."</option>";
            }
        }
        
        echo $concat;
    }
    
    function cargoCatalogosFiltro() {
        
        $catalogos = $this->listado_documentos_imagenes_catalogos_model->cargoCatalogos();
        
        $concat = "<option> </option>";
        
        foreach($catalogos as $val) {
            if(isset($_SESSION['seleccion_busqueda'])) {
                if($val == $_SESSION['seleccion_busqueda']) {
                    $concat .= "<option selected='selected' value='".$val."'>".$val."</option>";
                }else {
                    $concat .= "<option value='".$val."'>".$val."</option>";
                }
            }else {
                $concat .= "<option value='".$val."'>".$val."</option>";
            }
        }
        
        echo $concat;        
    }     
    
}
?>
