<!DOCTYPE html>
<html lang="es">

    <head>
        
        <style>
            
            button.reparada {
                    font-family: Arial, Helvetica, sans-serif;
                    font-size: 14px;
                    color: #ffffff;
                    padding: 10px 20px;
                    background: -moz-linear-gradient(
                            top,
                            #42ff42 0%,
                            #146600);
                    background: -webkit-gradient(
                            linear, left top, left bottom, 
                            from(#42ff42),
                            to(#146600));
                    -moz-border-radius: 10px;
                    -webkit-border-radius: 10px;
                    border-radius: 10px;
                    border: 1px solid #134201;
                    -moz-box-shadow:
                            0px 1px 3px rgba(000,000,000,0.5),
                            inset 0px 0px 2px rgba(255,255,255,1);
                    -webkit-box-shadow:
                            0px 1px 3px rgba(000,000,000,0.5),
                            inset 0px 0px 2px rgba(255,255,255,1);
                    box-shadow:
                            0px 1px 3px rgba(000,000,000,0.5),
                            inset 0px 0px 2px rgba(255,255,255,1);
                    text-shadow:
                            0px -1px 0px rgba(000,000,000,0.4),
                            0px 1px 0px rgba(255,255,255,0.3);
                    margin-left: 0px;
            }
          
            button.reparada_fallas {
                    font-family: Arial, Helvetica, sans-serif;
                    font-size: 14px;
                    color: #ffffff;
                    padding: 10px 20px;
                    background: -moz-linear-gradient(
                            top,
                            #f7ff05 0%,
                            #6d7a0c);
                    background: -webkit-gradient(
                            linear, left top, left bottom, 
                            from(#f7ff05),
                            to(#6d7a0c));
                    -moz-border-radius: 10px;
                    -webkit-border-radius: 10px;
                    border-radius: 10px;
                    border: 1px solid #66500c;
                    -moz-box-shadow:
                            0px 1px 3px rgba(000,000,000,0.5),
                            inset 0px 0px 2px rgba(255,255,255,1);
                    -webkit-box-shadow:
                            0px 1px 3px rgba(000,000,000,0.5),
                            inset 0px 0px 2px rgba(255,255,255,1);
                    box-shadow:
                            0px 1px 3px rgba(000,000,000,0.5),
                            inset 0px 0px 2px rgba(255,255,255,1);
                    text-shadow:
                            0px -1px 0px rgba(000,000,000,0.4),
                            0px 1px 0px rgba(255,255,255,0.3);
                    margin-left: 0px;
            }
            
            button.fallas {
                    font-family: Arial, Helvetica, sans-serif;
                    font-size: 14px;
                    color: #ffffff;
                    padding: 10px 20px;
                    background: -moz-linear-gradient(
                            top,
                            #ff0d05 0%,
                            #4f1414);
                    background: -webkit-gradient(
                            linear, left top, left bottom, 
                            from(#ff0d05),
                            to(#4f1414));
                    -moz-border-radius: 10px;
                    -webkit-border-radius: 10px;
                    border-radius: 10px;
                    border: 1px solid #8a3030;
                    -moz-box-shadow:
                            0px 1px 3px rgba(000,000,000,0.5),
                            inset 0px 0px 2px rgba(255,255,255,1);
                    -webkit-box-shadow:
                            0px 1px 3px rgba(000,000,000,0.5),
                            inset 0px 0px 2px rgba(255,255,255,1);
                    box-shadow:
                            0px 1px 3px rgba(000,000,000,0.5),
                            inset 0px 0px 2px rgba(255,255,255,1);
                    text-shadow:
                            0px -1px 0px rgba(000,000,000,0.4),
                            0px 1px 0px rgba(255,255,255,0.3);
                    margin-left: 0px;
            }            
            
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
                $("#fecha1").datepicker({ dateFormat: "yy-mm-dd", monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"], dayNames: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"], dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"], changeYear: true, changeMonth: true, dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"], monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"] } );
                $("#fecha2").datepicker({ dateFormat: "yy-mm-dd", monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"], dayNames: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"], dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"], changeYear: true, changeMonth: true, dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"], monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"] } );
                //cargoConsulta();
            });	     
     
            function filtrarConsulta(){
                
                //obligatorios
                var nro_serie = $("#nro_serie").val();               
                var marca     = $("#marca").val();          
                var calibre   = $("#calibre").val();          
                var modelo    = $("#modelo").val();          
                
                //opcionales
                var fecha1 = $("#fecha1").val();
                var fecha2 = $("#fecha2").val();
                
                $.ajax({ 
                    type: 'post',
                    url: '<?php echo base_url(); ?>consulta_estado_actual_arma/consulta/0',
                    data: "nro_serie="+nro_serie+"&marca="+marca+"&calibre="+calibre+"&modelo="+modelo+"&fecha1="+fecha1+"&fecha2="+fecha2,
                    success: function(){
                        cargoConsulta();
                    }
                });                
            }
            
            function impresion(){                
                $('#datos_consulta').printElement();            
            } 

            function seteoImpresion(de_pagina, a_pagina){                
                $.ajax({
                    type: 'post',
                    url: "<?php echo base_url("consulta_estado_actual_arma/armoImpresion"); ?>",
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
                    url: "<?php echo base_url("consulta_estado_actual_arma/orderBy"); ?>",
                    data: "order="+param,
                    success: function(){
                        cargoConsulta();                       
                    }                  
                });           
            }      

            function cargoConsulta() {
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: "<?php echo base_url("consulta_estado_actual_arma/consulta"); ?>",
                    success: function(data){
                        if(data[0] != 0) {
                            $("#datos_consulta").html(data[0]);
                        }else {
                            jAlert("ERROR: Debe seleccionar un armamento para realizar dicha consulta", "Error");
                        }
                    }                  
                });            
            }
            
            function busquedaFichas() {
                $.colorbox({href:"<?php echo base_url('busqueda_fichas_totales'); ?>", top:false, iframe:true, innerWidth:900, innerHeight:700, title:"BUSQUEDA FICHAS", onClosed: function(){ cargoFichasFiltro(); } });
            }
            
            function cargoFichasFiltro() {
                $.ajax({
                   type: "post",
                   dataType: "json",
                   url: "<?php base_url(); ?>consulta_estado_actual_arma/cargoFichasFiltro",
                   success: function(data) {
                       $("#nro_serie").val("");
                       $("#nro_serie").val(data[0]);
                       $("#marca").val("");
                       $("#marca").val(data[1]);
                       $("#calibre").val("");
                       $("#calibre").val(data[2]);
                       $("#modelo").val("");
                       $("#modelo").val(data[3]);
                       
                       filtrarConsulta();      
                   }
                });                
            } 
            
        </script>
        
    </head>
    
    <body class="cuerpo">
       
        <p class="subtituloform"> Filtro obligatorio </p>
        
        <hr />
        
        <dl> 		
        <dt><label for="nro_serie"> Nº serie </label></dt>	
        <dd><input readonly="readonly" type="text" id="nro_serie" class="txtautomatico" /> <img style="cursor: pointer;" onclick="busquedaFichas();" src="<?php echo base_url(); ?>images/search.png" /></dd> 					
        </dl>

        <dl> 		
        <dt><label for="marca"> Marca </label></dt>	
        <dd><input readonly="readonly" type="text" id="marca" class="txtautomatico" /></dd> 					
        </dl>

        <dl> 		
        <dt><label for="calibre"> Calibre </label></dt>	
        <dd><input readonly="readonly" type="text" id="calibre" class="txtautomatico" /></dd> 					
        </dl>

        <dl> 		
        <dt><label for="modelo"> Modelo </label></dt>	
        <dd><input readonly="readonly" type="text" id="modelo" class="txtautomatico" /></dd> 	
        
        <br /><br /><br />  
        
        &nbsp; <button onclick="impresion();"> Imprimir </button>              
        
        <br />
        
        <hr />
        
        <div class="datagrid">
        
            <div id="datos_consulta">
                
                
            </div>    
            
       </div>     
        
    </body>    
    
</html>