<!DOCTYPE html>
<html lang="es">

    <head>
        
        <title>SICEARME</title>	
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        	
        <!-- SCRIPT Plugins -->
        <script type="text/javascript" src='<?php echo base_url('js/jquery-ui/js/jquery-1.8.2.min.js');?>' ></script>	
        <script type="text/javascript" src='<?php echo base_url('js/jquery-ui/js/jquery-ui-1.8.23.custom.min.js');?>' ></script>	
        <script type="text/javascript" src='<?php echo base_url('js/colorbox/colorbox/jquery.colorbox.js'); ?>'></script>
        <script type="text/javascript" src='<?php echo base_url('js/print/jquery.printElement.js'); ?>'></script>
        <script type="text/javascript" src='<?php echo base_url('js/jquery.alerts-1.1/jquery.alerts.js'); ?>'></script>
        <script type="text/javascript" src='<?php echo base_url('js/smartupdater/smartupdater.4.0.js'); ?>'></script>
        
        <!-- CSS Plugins -->
        <link media="screen" rel="stylesheet" href='<?php echo base_url('js/jquery.alerts-1.1/jquery.alerts.css'); ?>' />
        <link media="screen" rel="stylesheet" href='<?php echo base_url('js/colorbox/example1/colorbox.css'); ?>' />	
        <link rel="stylesheet" href='<?php echo base_url('js/jquery-ui/css/black-tie/jquery-ui-1.8.23.custom.css'); ?>' type="text/css" />
        <link rel="stylesheet" href='<?php echo base_url('css/estilo.css'); ?>' type="text/css" />
        <link rel="shortcut icon" href='<?php echo base_url('css/template/favicon.png');?>' />        
        
        <style>
            .datagrid table { border-collapse: collapse; text-align: left; width: 100%; } 
            .datagrid {font: normal 12px/150% Arial, Helvetica, sans-serif; background: #fff; overflow: hidden; border: 1px solid #8C8C8C; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; }
            .datagrid table td, .datagrid table th { padding: 3px 10px; }
            
            .datagrid table thead th {background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #8C8C8C), color-stop(1, #7D7D7D) );background:-moz-linear-gradient( center top, #8C8C8C 5%, #7D7D7D 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#8C8C8C', endColorstr='#7D7D7D');background-color:#8C8C8C; color:#FFFFFF; font-size: 15px; font-weight: bold; border-left: 1px solid #A3A3A3; } 
            .datagrid table thead th:first-child { border: none; }
            
            .datagrid table tbody td { background: #F2FBEF; color: #7D7D7D; border-left: 1px solid #DBDBDB; border-bottom: 1px solid #DBDBDB; font-size: 12px;font-weight: normal; }
            .datagrid table tbody .alt td { background: #E6F8E0; color: #7D7D7D; }
            .datagrid table tbody td:first-child { border-left: none; }
            .datagrid table tbody tr:last-child td { border-bottom: none; }
            
            .datagrid table tfoot td div { border-top: 1px solid #8C8C8C;background: #EBEBEB;} 
            .datagrid table tfoot td { padding: 0; font-size: 12px } .datagrid table tfoot td div{ padding: 2px; }
            .datagrid table tfoot td ul { margin: 0; padding:0; list-style: none; text-align: right; }
            .datagrid table tfoot  li { display: inline; }
            .datagrid table tfoot li a { text-decoration: none; display: inline-block;  padding: 2px 8px; margin: 1px;color: #F5F5F5;border: 1px solid #8C8C8C;-webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #8C8C8C), color-stop(1, #7D7D7D) );background:-moz-linear-gradient( center top, #8C8C8C 5%, #7D7D7D 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#8C8C8C', endColorstr='#7D7D7D');background-color:#8C8C8C; }
            .datagrid table tfoot ul.active, .datagrid table tfoot ul a:hover { text-decoration: none;border-color: #7D7D7D; color: #F5F5F5; background: none; background-color:#8C8C8C;}
        </style>          
        
        <script type="text/javascript">
     
            $(document).ready(function() {
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
                cargoConsulta();
            });	     
     
            function filtrar(){
                
                var nro_serie    = $("#nro_serie").val();
                var nro_compra   = $("#nro_compra").val();
                var nro_catalogo = $("#nro_catalogo").val();
                var marca        = $("#marca").val();
                var calibre      = $("#calibre").val();
                var modelo       = $("#modelo").val();
                
                $.ajax({ 
                    type: 'post',
                    url: '<?php echo base_url(); ?>busqueda_fichas_totales/consulta/0',
                    data: "nro_serie="+nro_serie+"&nro_compra="+nro_compra+"&nro_catalogo="+nro_catalogo+"&marca="+marca+"&calibre="+calibre+"&modelo="+modelo,
                    success: function(){
                        cargoConsulta();
                    }
                });                
            }
            
            function impresion(){                
                $.colorbox({href:"<?php echo base_url('busqueda_fichas_totales/seteoImpresion'); ?>", top: true, iframe: false, scrolling: false, innerWidth: 800, innerHeight: 200, title: "IMPRESION"});                
            } 

            function seteoImpresion(de_pagina, a_pagina){                
                $.ajax({
                    type: 'post',
                    url: "<?php echo base_url("busqueda_fichas_totales/armoImpresion"); ?>",
                    data: "de_pagina="+de_pagina+"&a_pagina="+a_pagina,
                    success: function(data){
                        if(data == "1"){
                            window.open ("<?php echo base_url("contenido_impresion"); ?>", "mywindow","toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=1,resizable=0");
                        }else{
                            jAlert(data);
                        }
                    }                  
                });
            }            
            
            function orderBy(param){            
                $.ajax({
                    type: 'post',
                    url: "<?php echo base_url("busqueda_fichas_totales/orderBy"); ?>",
                    data: "order="+param,
                    success: function(){
                        cargoConsulta();                       
                    }                  
                });           
            }      
            
            function seleccion(nro_serie, marca, calibre, modelo) {
                
                $.ajax({
                    type: 'post',
                    url: "<?php echo base_url("busqueda_fichas_totales/seteoSeleccion"); ?>",
                    data: "nro_serie="+nro_serie+"&marca="+marca+"&calibre="+calibre+"&modelo="+modelo,
                    success: function(){
                        jAlert("Nro serie - "+nro_serie+", Marca - "+marca+", Calibre - "+calibre+", Modelo - "+modelo+" seleccionados", "SELECCION", parent.$.fn.colorbox.close());                        
                    }                  
                });            
            }
            
            function cargoConsulta() {
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: "<?php echo base_url("busqueda_fichas_totales/consulta"); ?>",
                    success: function(data){
                        $("#datos_consulta").html(data[0]);
                        $("#paginado").html(data[1]);
                    }                  
                });            
            }
                  
        </script>
        
    </head>
    
    <body class="cuerpo">
        
        <table>
            
            <tr>
                <td><label> &emsp; Nro serie - </label> </td> <td> <input type="text" class="text" id="nro_serie" /></td>
                <td><label> &emsp; Nro compra - </label> </td> <td>  <input type="text" class="text" id="nro_compra" /></td>
            </tr>            
            
            <tr>
                <td><label> &emsp; Nro catalogo - </label> </td> <td>  <input type="text" class="text" id="nro_catalogo" /></td>
                <td><label> &emsp; Marca - </label> </td> <td>  <input type="text" class="text" id="marca" /></td>
            </tr>
            
            <tr>
                <td><label> &emsp; Calibre - </label> </td> <td> <input type="text" class="text" id="calibre" /></td>
                <td><label> &emsp; Modelo - </label> </td> <td>  <input type="text" class="text" id="modelo" /></td>
            </tr>            
            
        </table>
        
        <br /> 
        
        &emsp; <button onclick="filtrar();"> Buscar </button> &emsp;&emsp; <button onclick="impresion();"> Imprimir </button>              
        
        <br /> 
        
        <hr />
        
        <div class="datagrid">
        
            <table>

                <thead style='text-align: center; cursor: pointer;'>
                    <tr>      
                        <th> Seleccion  </th>
                        <th onclick="orderBy(0)"> N Ser   </th>
                        <th onclick="orderBy(1)"> N Com   </th>
                        <th onclick="orderBy(2)"> N Cat   </th>
                        <th onclick="orderBy(3)"> Mar     </th>
                        <th onclick="orderBy(4)"> Cal     </th>
                        <th onclick="orderBy(5)"> Mod     </th>
                    </tr>
                </thead>

                <tbody id="datos_consulta"> </tbody>   

                <tfoot>
                    <tr> <td colspan="7"> <div id="paging"> <br /> </div> </td> </tr>
                </tfoot>
                
           </table>  
            
       </div>     
            
       <div id="paginado"> </div>     
        
    </body>    
    
</html>