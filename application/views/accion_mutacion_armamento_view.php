<!DOCTYPE html>
<html lang="es">
    
    <head>
        
        <style>
            .datagrid table { border-collapse: collapse; text-align: left; width: 100%; } 
            .datagrid {font: normal 12px/150% Arial, Helvetica, sans-serif; background: #fff; overflow: hidden; border: 1px solid #8C8C8C; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; }
            .datagrid table td, .datagrid table th { padding: 3px 10px; }
            
            .datagrid table thead th {background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #8C8C8C), color-stop(1, #7D7D7D) );background:-moz-linear-gradient( center top, #8C8C8C 5%, #7D7D7D 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#8C8C8C', endColorstr='#7D7D7D');background-color:#8C8C8C; color:#FFFFFF; font-size: 15px; font-weight: bold; border-left: 1px solid #A3A3A3; } 
            .datagrid table thead th:first-child { border: none; }
            
            .datagrid table tbody td { background: #F2FBEF; color: #7D7D7D; border-left: 1px solid #DBDBDB; border-bottom: 1px solid #DBDBDB; font-size: 12px; font-weight: normal; }
            .datagrid table tbody .alt td { background: #E6F8E0; color: #7D7D7D; }
            .datagrid table tbody .total td { background: #F5F6CE; color: #7D7D7D; font-weight: bold; text-align: center; }
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
                $("#fecha").datepicker({ dateFormat: "yy-mm-dd", monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"], dayNames: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"], dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"], changeYear: true, changeMonth: true, dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"], monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"] } );
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
            });	

            function accionMutacionArma() {
                
                jConfirm('Esta seguro de realizar esta accion ?', 'ESTA SEGURO', function(r) {
                    
                    if(r) {

                        jConfirm('<b>Esta verdaderamente seguro de realizar esta accion ?</b>', 'ESTA VERDADERAMENTE SEGURO', function(r) {
                            
                            if(r) {

                                var fecha           = $("#fecha").val();
                                var nro_orden       = $("#nro_orden").val();
                                var seccion         = $("#seccion").val();
                                var observaciones   = $("#observaciones").val();
                                var nro_pieza_nueva = $("#nro_pieza_nueva").val();
                                
                                var observaciones = cambiarCaracter();

                                $.ajax({
                                    type: "post",  
                                    url: "<?php base_url(); ?>accion_mutacion_armamento/validarDatos",
                                    data: "fecha="+fecha+"&nro_orden="+nro_orden+"&seccion="+seccion+"&observaciones="+observaciones+"&nro_pieza_nueva="+nro_pieza_nueva,
                                    success: function(data){
                                        if(data == 1){            
                                            jAlert("CORRECTO: La mutacion del armamento se dio correctamente, quedo registrado un historico de dicho cambio", "Correcto", function() { irAFrame('<?php echo base_url('accion_mutacion_armamento'); ?>','Taller armamento >> Accion >> Mutacion de armamento'); });
                                        }else{
                                            jAlert(data, "Error");
                                        }                            
                                  }
                                });
                            }
                        });
                    }
                });
            }
            
            function cambiarCaracter(){
                var val = $("#observaciones").val();    
                while (val !=(val = val.replace('&', '')));
                return val;
            }            
            
            //cargo y creo Secciones
            function crearSeccion() {
                $.colorbox({href:"<?php echo base_url('alta_seccion'); ?>", top:false, iframe:false, innerWidth:800, innerHeight:200, title:"ALTA SECCION", onClosed: function(){ cargoSecciones(); } });
            }            
            
            function cargoSecciones() {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>accion_mutacion_armamento/cargoSecciones",
                   success: function(data) {
                       $("#seccion").html(data);
                   }
                });
            }     
            //fin cargo y creo Empresas
            
            function busquedaOrdenesTrabajo() {
                $.colorbox({href:"<?php echo base_url('busqueda_ordenes_trabajo'); ?>", top:false, iframe:false, innerWidth:900, innerHeight:700, title:"BUSQUEDA ORDENES TRABAJO", onClosed: function(){ cargoOrdenesTrabajoFiltro(); } });
            }
            
            function cargoOrdenesTrabajoFiltro() {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>accion_mutacion_armamento/cargoOrdenesTrabajoFiltro",
                   success: function(data) {
                       $("#nro_orden").html("");
                       $("#nro_orden").html(data);
                       cargoDatosArma();
                   }
                });                
            }
            
            function cargoDatosArma() {
            
                var nro_orden = $("#nro_orden").val();
            
                $.ajax({
                   type: "post",
                   dataType: "json",
                   url: "<?php base_url(); ?>accion_mutacion_armamento/cargoDatosArma",
                   data: "nro_orden="+nro_orden,
                   success: function(data) {
                       if(data[0] !== 0) {
                           $("#nro_serie").val(data[0]);
                           $("#marca").val(data[1]);
                           $("#calibre").val(data[2]);
                           $("#modelo").val(data[3]);
                           $("#tipo_arma").val(data[4]);
                       }else {
                           $("#nro_serie").val("");
                           $("#marca").val("");
                           $("#calibre").val("");
                           $("#modelo").val("");
                           $("#tipo_arma").val("");                           
                       }
                   }
                }); 
            }
            
            function busquedaRepuestos() {
             
                var nro_pieza = $("#nro_pieza_anterior").val();
                
                if(nro_pieza == "") {
                    jAlert("ERROR: Debe seleccionar una pieza del armamento antes de buscar en el almacen", "Error");
                    return false;
                }
            
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>accion_mutacion_armamento/busquedaRepuestos",
                   data: "nro_pieza="+nro_pieza,
                   success: function() {
                       $.colorbox({href:"<?php echo base_url('busqueda_repuestos_nro_pieza'); ?>", top:false, iframe:false, innerWidth:900, innerHeight:700, title:"BUSQUEDA REPUESTOS", onClosed: function(){ cargoRepuestosFiltro(); } });
                   }
                });                
            }
            
            function cargoRepuestosFiltro() {
                $.ajax({
                   type: "post",
                   dataType: "json",
                   url: "<?php base_url(); ?>accion_mutacion_armamento/cargoRepuestosFiltro",
                   success: function(data) {
               
                        $("#nro_pieza_nueva").val("");
                        $("#nro_parte").val("");
                        $("#nombre_parte").val("");
                        $("#nro_catalogo").val("");
                        
                        if(data[0] != 0) {
                            $("#nro_pieza_nueva").val(data[0]);
                            $("#nro_parte").val(data[1]);
                            $("#nombre_parte").val(data[2]);
                            $("#nro_catalogo").val(data[3]);                           
                        }
                   }
                });                
            } 
            
            function busquedaPiezasArmamento() {
            
                $("#nro_pieza_nueva").val("");
                $("#nro_parte").val("");
                $("#nombre_parte").val("");
                $("#nro_catalogo").val("");            
            
                var nro_orden = $("#nro_orden").val();
            
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>accion_mutacion_armamento/cargoNroOrdenTrabajo",
                   data: "nro_orden="+nro_orden,
                   success: function(data) {
                       if(data == 1) {
                           $.colorbox({href:"<?php echo base_url('busqueda_piezas'); ?>", top:false, iframe:false, innerWidth:900, innerHeight:700, title:"BUSQUEDA PIEZAS", onClosed: function(){ cargoPiezasArmamentoFiltro(); } });
                       }else {
                           jAlert("ERROR: Debe de seleccionar un nro de orden primero y luego buscar el repuesto en el almacen", "Error");
                       }
                   }
                });             
            }   
            
            function cargoPiezasArmamentoFiltro() {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>accion_mutacion_armamento/cargoPiezasArmamentoFiltro",
                   success: function(data) {
               
                        $("#nro_pieza_anterior").val("");
                        
                        if(data != 0) {
                            $("#nro_pieza_anterior").val(data);
                        }
                   }
                });                
            }              
    
        </script>
        
    </head>

    <body class="cuerpo">

        <div>			

            <h1> Mutación de armamento - <font color="red"> * ¿Esta seguro de realizar esta acción? </font> </h1>    
            
            <fieldset>	

                <dl>
                <dt><label for="fecha"> Fecha <font color="red"> * </font> </label></dt>
                <dd><input readonly="readonly" type="text" id="fecha" class="text" /></dd>
                </dl>                
                
                <dl>
                <dt><label for="nro_orden"> Nº orden <font color="red"> * </font> </label></dt>
                <dd><select id="nro_orden" onchange='cargoDatosArma(this.value);'> <?php echo $nro_ordenes ?> </select> <img style="cursor: pointer;" onclick="busquedaOrdenesTrabajo();" src="<?php echo base_url(); ?>images/search.png" /> </dd>
                </dl>                 
                
                <p><img style='width: 100%; height: 6px;' src="<?php echo base_url() ?>images/barra.png" /></p>
                
                <p class="subtituloform"> Datos del arma </p>
                
                <dl>
                <dt><label for="nro_serie"> Nº serie </label></dt>
                <dd><input readonly="readonly" type="text" id="nro_serie" class="txtautomatico" /></dd>
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
                </dl> 
                
                <dl>
                <dt><label for="tipo_arma"> Tipo </label></dt>
                <dd><input readonly="readonly" type="text" id="tipo_arma" class="txtautomatico" /></dd>
                </dl>                 
                
                <p><img style='width: 100%; height: 6px;' src="<?php echo base_url() ?>images/barra.png" /></p>
                
                <dl>
                <dt><label> Pieza a cambiar <font color="red"> * </font> </label></dt>
                <dd><img style="cursor: pointer;" onclick="busquedaPiezasArmamento();" src="<?php echo base_url(); ?>images/search.png" /> <label> (Pieza que contiene actualmente el armamento) </label> </dd>
                </dl>                 
                
                <dl>
                <dt><label for="nro_pieza_anterior"> Nº pieza </label></dt>
                <dd><input readonly="readonly" type="text" id="nro_pieza_anterior" class="txtautomatico" /> </dd>
                </dl> 
                
                <p><img style='width: 100%; height: 6px;' src="<?php echo base_url() ?>images/barra.png" /></p>
                                
                <dl>
                <dt><label for="seccion"> Sección <font color="red"> * </font> </label></dt>
                <dd><select id="seccion"> <?php echo $secciones ?> </select> <img style="cursor: pointer;" onclick="crearSeccion();" src="<?php echo base_url(); ?>images/sumar.png" /></dd>
                </dl>       
                
                <dl> 		
                <dt><label for="observaciones"> Observaciones </label></dt>	
                <dd><textarea id="observaciones"> </textarea></dd> 					
                </dl>       
                
                <p><img style='width: 100%; height: 6px;' src="<?php echo base_url() ?>images/barra.png" /></p>
                
                <p class="subtituloform"> Datos de pieza nueva principal que va a portar el armamento </p>
                
                 <dl>
                <dt><label> Buscar repuesto <font color="red"> * </font> </label></dt>
                <dd><img style="cursor: pointer;" onclick="busquedaRepuestos();" src="<?php echo base_url(); ?>images/search.png" /> </dd>
                </dl>  
                
                <dl>
                <dt><label for="nro_pieza_nueva"> Nº pieza </label></dt>
                <dd><input readonly="readonly" type="text" id="nro_pieza_nueva" class="txtautomatico" /> </dd>
                </dl>  
                
                <dl>
                <dt><label for="nro_parte"> Nº parte </label></dt>
                <dd><input readonly="readonly" type="text" id="nro_parte" class="txtautomatico" /> </dd>
                </dl>
                
                <dl>
                <dt><label for="nombre_parte"> Nombre parte </label></dt>
                <dd><input readonly="readonly" type="text" id="nombre_parte" class="txtautomatico" /> </dd>
                </dl>
                
                <dl>
                <dt><label for="nro_catalogo"> Nº catálogo </label></dt>
                <dd><input readonly="readonly" type="text" id="nro_catalogo" class="txtautomatico" /> </dd>
                </dl>               
                
            </fieldset>	

            <fieldset class="action">	
                <button style="margin-right: 20px;" onclick="accionMutacionArma();"> Cambiar pieza principal del arma (chasis) </button> 
            </fieldset>  
             
        </div>        
        
    </body>
	
</html>