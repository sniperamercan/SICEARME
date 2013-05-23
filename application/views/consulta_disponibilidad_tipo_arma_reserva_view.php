<!DOCTYPE html>
<html lang="es">

    <head>
        
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
                //cargoConsulta();
            });	     
     
            function filtrarDisponibilidad(){
                
                var nro_catalogo = $("#nro_catalogo").val();                       
                
                $.ajax({ 
                    type: 'post',
                    url: '<?php echo base_url(); ?>consulta_disponibilidad_tipo_arma_reserva/consulta/0',
                    data: "nro_catalogo="+nro_catalogo,
                    success: function(){
                        cargoConsultaDisponibilidad();
                    }
                });                
            }
            
            function impresion(){ 
                $('#datos_consulta').printElement();   
            } 

            function seteoImpresion(de_pagina, a_pagina){                
                $.ajax({
                    type: 'post',
                    url: "<?php echo base_url("consulta_disponibilidad_tipo_arma_reserva/armoImpresion"); ?>",
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
                    url: "<?php echo base_url("consulta_disponibilidad_tipo_arma_reserva/orderBy"); ?>",
                    data: "order="+param,
                    success: function(){
                        cargoConsulta();                       
                    }                  
                });           
            }      

            function cargoConsultaDisponibilidad() {
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: "<?php echo base_url("consulta_disponibilidad_tipo_arma_reserva/consulta"); ?>",
                    success: function(data){
                        if(data[0] != 0) {
                            $("#datos_consulta").html(data[0]);
                        }else {
                            jAlert("ERROR: Debe seleccionar un catalogo para realizar dicha consulta", "Error");
                        }
                        
                    }                  
                });            
            }
            
            function busquedaCatalogos() {
                $.colorbox({href:"<?php echo base_url('busqueda_catalogos'); ?>", top:false, iframe:false, innerWidth:900, innerHeight:700, title:"BUSQUEDA CATALOGOS", onClosed: function(){ cargoCatalogosFiltro(); } });
            }
            
            function cargoCatalogosFiltro() {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>consulta_disponibilidad_tipo_arma_reserva/cargoCatalogosFiltro",
                   success: function(data) {
                       $("#nro_catalogo").val("");
                       $("#nro_catalogo").val(data);
                       filtrarDisponibilidad();
                   }
                });                
            }             
        </script>
        
    </head>
    
    <body class="cuerpo">
        
        <p class="subtituloform"> Filtro obligatorio </p>
        
        <hr />
        
        <dl>
        <dt><label> Buscar catalogo </label></dt>
        <dd><img style="cursor: pointer;" onclick="busquedaCatalogos();" src="<?php echo base_url(); ?>images/search.png" /> </dd>
        </dl>         

        <dl>
        <dt><label for="nro_catalogo"> Nº catálogo </label></dt>
        <dd><input readonly="readonly" type="text" id="nro_catalogo" class="txtautomatico" /> </dd>
        </dl>     
        
        <br /><br /><br /> 
        
        &emsp; <button onclick="impresion();"> Imprimir </button>              
        
        <br /> 
        
        <hr />
        
        <div class="datagrid">
        
            <div id="datos_consulta">
                
                
            </div>    
            
       </div>     
        
    </body>    
    
</html>