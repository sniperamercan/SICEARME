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

            function altaAccionSimple() {
                
                var fecha         = $("#fecha").val();
                var nro_orden     = $("#nro_orden").val();
                var seccion       = $("#seccion").val();
                var observaciones = $("#observaciones").val();
                
                $.ajax({
                    type: "post",  
                    url: "<?php base_url(); ?>accion_ordenes_trabajo/validarDatos",
                    data: "fecha="+fecha+"&nro_orden="+nro_orden+"&seccion="+seccion+"&observaciones="+observaciones,
                    success: function(data){
                        if(data == 1){            
                            jAlert("Accion simple al armamento generada correctamente sobre la orden de trabajo", "Correcto", function() { limpioCampos(); });
                        }else{
                            jAlert(data, "Error");
                        }                            
                  }
                });               
            }

            //luego de un ingreso de una accion
            function limpioCampos() {
            
                $("#fecha").val("");
                $("#seccion").val("");
                $("#observaciones").val("");
                
                cargoAcciones();
            }

            function altaAccionPiezaSecundarias() {
                
                var fecha         = $("#fecha").val();
                var nro_orden     = $("#nro_orden").val();
                var seccion       = $("#seccion").val();
                var observaciones = $("#observaciones").val();                
                
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>accion_ordenes_trabajo/accionPiezasSecundarias",
                   data: "fecha="+fecha+"&nro_orden="+nro_orden+"&seccion="+seccion+"&observaciones="+observaciones,
                   success: function(data) {
                       if(data == 1) {
                           irAFrame('<?php echo base_url('accion_piezas_secundarias'); ?>','Taller armamento >> Accion >> Ordenes de trabajo');
                       }else {
                           jAlert(data, "Error");
                       }
                   }
                });            
            }   
            
            function altaAccionPiezaAsociadas() {
            
                var fecha         = $("#fecha").val();
                var nro_orden     = $("#nro_orden").val();
                var seccion       = $("#seccion").val();
                var observaciones = $("#observaciones").val();                
                
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>accion_ordenes_trabajo/accionPiezasAsociadas",
                   data: "fecha="+fecha+"&nro_orden="+nro_orden+"&seccion="+seccion+"&observaciones="+observaciones,
                   success: function(data) {
                       if(data == 1) {
                           irAFrame('<?php echo base_url('accion_piezas_asociadas'); ?>','Taller armamento >> Accion >> Ordenes de trabajo');
                       }else {
                           jAlert(data, "Error");
                       }
                   }
                });             
            }             
            
            //cargo y creo Secciones
            function crearSeccion() {
                $.colorbox({href:"<?php echo base_url('alta_seccion'); ?>", top:false, iframe:false, innerWidth:800, innerHeight:200, title:"ALTA SECCION", onClosed: function(){ cargoSecciones(); } });
            }            
            
            function cargoSecciones() {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>accion_ordenes_trabajo/cargoSecciones",
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
                   url: "<?php base_url(); ?>accion_ordenes_trabajo/cargoOrdenesTrabajoFiltro",
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
                   url: "<?php base_url(); ?>accion_ordenes_trabajo/cargoDatosArma",
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
                       
                       cargoAcciones();
                   }
                }); 
            }
    
            function cargoAcciones() {
            
                var nro_orden = $("#nro_orden").val();
               
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>accion_ordenes_trabajo/cargoAcciones",
                   data: "nro_orden="+nro_orden,
                   success: function(data) {
                       if(data !== 0) {
                           $("#acciones").html("");
                           $("#acciones").html(data);
                       }
                   }
                }); 
            }
            
            function verInformacion(nro_accion) {
            
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>accion_ordenes_trabajo/verInformacion",
                   data: "nro_accion="+nro_accion,
                   success: function(data) {
                       jAlert(data, "Informacion");
                   }
                });                
            
            }
            
            function eliminarAccion(nro_accion) {
            
                jConfirm('Esta seguro que desea anular la accion Nro - '+nro_accion+'?', 'Confirme anulacion de accion', function(r) {
                    
                    if(r) {
                        $.ajax({
                           type: "post",
                           url: "<?php base_url(); ?>accion_ordenes_trabajo/eliminarAccion",
                           data: "nro_accion="+nro_accion,
                           success: function(data) {
                               jAlert(data, "Anulacion correcta", function() { cargoAcciones(); });
                           }
                        });  
                    }
                });
            }
            
            function editarAccion(nro_accion) {
                
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>accion_ordenes_trabajo/editarAccion",
                   data: "nro_accion="+nro_accion,
                   success: function(data) {
                       
                       switch(data) {
                           case '0':
                               irAFrame('<?php echo base_url('modificar_accion_simple'); ?>','Taller armamento >> Accion >> Ordenes de trabajo');
                               break;
                               
                           case '1':
                               irAFrame('<?php echo base_url('modificar_accion_piezas_secundarias'); ?>','Taller armamento >> Accion >> Ordenes de trabajo');
                               break;
                           
                           case '2':
                               irAFrame('<?php echo base_url('modificar_accion_piezas_asociadas'); ?>','Taller armamento >> Accion >> Ordenes de trabajo');
                               break;
                       }
                   }
                });                
            
            }
    
        </script>
        
    </head>

    <body class="cuerpo">

        <div>			

            <h1> Accion sobre una orden de trabajo </h1>    
            
            <fieldset>	

                <dl>
                <dt><label for="fecha"> Fecha </label></dt>
                <dd><input readonly="readonly" type="text" id="fecha" class="text" /></dd>
                </dl>                
                
                <dl>
                <dt><label for="nro_orden"> Nro orden </label></dt>
                <dd><select id="nro_orden" onchange='cargoDatosArma(this.value);'> <?php echo $nro_ordenes ?> </select> <img style="cursor: pointer;" onclick="busquedaOrdenesTrabajo();" src="<?php echo base_url(); ?>images/search.png" /> </dd>
                </dl>                 
                
                <p><img src="<?php echo base_url() ?>images/barra.png" /></p>
                
                <p class="subtituloform"> Datos del arma </p>
                
                <dl>
                <dt><label for="nro_serie"> Nro serie </label></dt>
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
                
                <p><img src="<?php echo base_url() ?>images/barra.png" /></p>
                
                <dl>
                <dt><label for="seccion"> Seccion </label></dt>
                <dd><select id="seccion"> <?php echo $secciones ?> </select> <img style="cursor: pointer;" onclick="crearSeccion();" src="<?php echo base_url(); ?>images/sumar.png" /></dd>
                </dl>       
                
                <dl> 		
                <dt><label for="observaciones"> Observaciones </label></dt>	
                <dd><textarea id="observaciones"> </textarea></dd> 					
                </dl>                
                
            </fieldset>	

            <fieldset class="action">	
                <button style="margin-right: 20px;" onclick="altaAccionSimple();"> Accion simple </button> 
                <button style="margin-right: 20px;" onclick="altaAccionPiezaSecundarias();"> Accion piezas secundarias </button> 
                <button style="margin-right: 20px;" onclick="altaAccionPiezaAsociadas();"> Accion piezas asociadas </button>
            </fieldset>  
            
            <hr />
            
            <div>
                
                <h1> Acciones sobre orden de trabajo <label id="acciones_nro_orden"> </label> </h1>       
                
                <fieldset>	

                    <div id="imprimir">
                                 
                        <div class="datagrid" style="margin-top: 30px;">
                            <table> 
                                <thead style="text-align: center;">
                                    <tr>
                                        <th> Nro accion </th> <th> Fecha </th> <th> Seccion </th> <th> Tipo accion </th> <th> Ver </th> <th> Editar </th> <th> Borrar </th> 
                                    </tr>
                                </thead>
                                <tbody id="acciones"></tbody>
                                <tfoot>
                                    <tr> <td colspan="7"> <div id="paging"> <br /> </div> </td> </tr>
                                </tfoot>                                
                            </table> 
                        </div>
                    
                    </div>    
                        
                </fieldset>	
                
            </div>
            
        </div>        
        
    </body>
	
</html>