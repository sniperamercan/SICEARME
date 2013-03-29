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
                $("#fecha").datepicker({ dateFormat: "yy-mm-dd", monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"], dayNames: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"], dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"], changeYear: true, changeMonth: true, dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"], monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"] } );
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
            });	

            function modificarActa() {
                
                var fecha                = $("#fecha").val();
                var unidad_recibe        = $("#unidad_recibe").val();
                var representante_sma    = $("#representante_sma").val();
                var representante_unidad = $("#representante_unidad").val();
                var supervision          = $("#supervision").val();
                var observaciones        = $("#observaciones").val();
                
                $.ajax({
                    type: "post",  
                    dataType: "json",
                    url: "<?php base_url(); ?>modificar_actas_alta/validarDatos",
                    data: "fecha="+fecha+"&unidad_recibe="+unidad_recibe+"&representante_sma="+representante_sma+"&representante_unidad="+representante_unidad+"&supervision="+supervision+"&observaciones="+observaciones,
                    success: function(data){
                        if(data[0] == "1"){            
                            jAlert("El acta fue modificada con exito", "Correcto", function() { irAFrame('<?php echo base_url('mb_actas_alta'); ?>','Abastecimiento >> Modificar >> Acta alta'); });
                        }else{
                            jAlert(data, "Error");
                        }                            
                  }
                });               
            }
            
            function volverListado() {
                irAFrame('<?php echo base_url('mb_actas_alta'); ?>','Abastecimiento >> Modificar >> Acta alta');
            }
            
            function cargoMarcas(nro_serie) {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>modificar_actas_alta/cargoMarcas",
                   data: "nro_serie="+nro_serie,
                   success: function(data) {
                       $("#marca").html("");
                       $("#marca").html(data);
                   }
                });                
            }
            
            function cargoCalibres(nro_serie, marca) {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>modificar_actas_alta/cargoCalibres",
                   data: "nro_serie="+nro_serie+"&marca="+marca,
                   success: function(data) {
                       $("#calibre").html("");
                       $("#calibre").html(data);
                   }
                });                
            }
            
            function cargoModelos(nro_serie, marca, calibre) {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>modificar_actas_alta/cargoModelos",
                   data: "nro_serie="+nro_serie+"&marca="+marca+"&calibre="+calibre,
                   success: function(data) {
                       $("#modelo").html("");
                       $("#modelo").html(data);
                   }
                });                
            }

            function cargoMarcasAccesorios(nro_serie) {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>modificar_actas_alta/cargoMarcasAccesorios",
                   data: "nro_serie="+nro_serie,
                   success: function(data) {
                       $("#marca_accesorio").html("");
                       $("#marca_accesorio").html(data);
                   }
                });                
            }
            
            function cargoCalibresAccesorios(nro_serie, marca) {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>modificar_actas_alta/cargoCalibresAccesorios",
                   data: "nro_serie="+nro_serie+"&marca="+marca,
                   success: function(data) {
                       $("#calibre_accesorio").html("");
                       $("#calibre_accesorio").html(data);
                   }
                });                
            }
            
            function cargoModelosAccesorios(nro_serie, marca, calibre) {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>modificar_actas_alta/cargoModelosAccesorios",
                   data: "nro_serie="+nro_serie+"&marca="+marca+"&calibre="+calibre,
                   success: function(data) {
                       $("#modelo_accesorio").html("");
                       $("#modelo_accesorio").html(data);
                   }
                });                
            }

            function cargoNroAccesorios(nro_serie, marca, calibre, modelo) {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>modificar_actas_alta/cargoNroAccesorios",
                   data: "nro_serie="+nro_serie+"&marca="+marca+"&calibre="+calibre+"&modelo="+modelo,
                   success: function(data) {
                       $("#nro_accesorio").html("");
                       $("#nro_accesorio").html(data);
                   }
                });                
            }
            
            function busquedaFichas() {
                $.colorbox({href:"<?php echo base_url('busqueda_fichas'); ?>", top:true, iframe:false, innerWidth:900, innerHeight:700, title:"BUSQUEDA FICHAS", onClosed: function(){ cargoFichasFiltro(); } });
            }
            
            function busquedaAccesorios() {
                $.colorbox({href:"<?php echo base_url('busqueda_accesorios'); ?>", top:true, iframe:false, innerWidth:900, innerHeight:700, title:"BUSQUEDA ACCESORIOS", onClosed: function(){ cargoAccesoriosFiltro(); } });
            }            

            function cargoFichasFiltro() {
                $.ajax({
                   type: "post",
                   dataType: "json",
                   url: "<?php base_url(); ?>modificar_actas_alta/cargoFichasFiltro",
                   success: function(data) {
                       $("#nro_serie").html("");
                       $("#nro_serie").html(data[0]);
                       $("#marca").html("");
                       $("#marca").html(data[1]);
                       $("#calibre").html("");
                       $("#calibre").html(data[2]);
                       $("#modelo").html("");
                       $("#modelo").html(data[3]);
                   }
                });                
            } 
            
            function cargoAccesoriosFiltro() {
                $.ajax({
                   type: "post",
                   dataType: "json",
                   url: "<?php base_url(); ?>modificar_actas_alta/cargoAccesoriosFiltro",
                   success: function(data) {
                       $("#nro_serie_accesorio").html("");
                       $("#nro_serie_accesorio").html(data[0]);
                       $("#marca_accesorio").html("");
                       $("#marca_accesorio").html(data[1]);
                       $("#calibre_accesorio").html("");
                       $("#calibre_accesorio").html(data[2]);
                       $("#modelo_accesorio").html("");
                       $("#modelo_accesorio").html(data[3]);
                       $("#nro_accesorio").html("");
                       $("#nro_accesorio").html(data[4]);                       
                   }
                });                
            } 

            function agregarFicha() {
            
                var nro_serie  = $("#nro_serie").val();
                var marca      = $("#marca").val();
                var calibre    = $("#calibre").val();
                var modelo     = $("#modelo").val();
             
                $.ajax({
                   type: "post",
                   dataType: "json",
                   url: "<?php base_url(); ?>modificar_actas_alta/agregarFicha",
                   data: "nro_serie="+nro_serie+"&marca="+marca+"&calibre="+calibre+"&modelo="+modelo,
                   success: function(data) {
                       if(data[0] == 1) {
                           $("#entregas_fichas").append(data[1]);
                       }else {
                           jAlert(data[0], "Error");
                       }
                   }
                });           
            }
            
            function anularFicha(nro_serie, marca, calibre, modelo) {
            
                $.ajax({
                   type: "post",
                   dataType: "json",
                   url: "<?php base_url(); ?>modificar_actas_alta/anularFicha",
                   data: "nro_serie="+nro_serie+"&marca="+marca+"&calibre="+calibre+"&modelo="+modelo,
                   success: function(data) {
                       if(data[0] == 1) {
                           $("#entregas_fichas").html("");
                           $("#entregas_fichas").html(data[1]);
                       }else {
                           $("#entregas_fichas").html("");
                       }
                   }
                });
            }
            
            function agregarAccesorio() {
            
                var nro_serie     = $("#nro_serie_accesorio").val();
                var marca         = $("#marca_accesorio").val();
                var calibre       = $("#calibre_accesorio").val();
                var modelo        = $("#modelo_accesorio").val();
                var nro_accesorio = $("#nro_accesorio").val();
             
                $.ajax({
                   type: "post",
                   dataType: "json",
                   url: "<?php base_url(); ?>modificar_actas_alta/agregarAccesorio",
                   data: "nro_serie="+nro_serie+"&marca="+marca+"&calibre="+calibre+"&modelo="+modelo+"&nro_accesorio="+nro_accesorio,
                   success: function(data) {
                       if(data[0] == 1) {
                           $("#entregas_accesorios").append(data[1]);
                       }else {
                           jAlert(data[0], "Error");
                       }
                   }
                });           
            }
            
            function anularAccesorio(nro_serie, marca, calibre, modelo, nro_accesorio) {
            
                $.ajax({
                   type: "post",
                   dataType: "json",
                   url: "<?php base_url(); ?>modificar_actas_alta/anularAccesorio",
                   data: "nro_serie="+nro_serie+"&marca="+marca+"&calibre="+calibre+"&modelo="+modelo+"&nro_accesorio="+nro_accesorio,
                   success: function(data) {
                       if(data[0] == 1) {
                           $("#entregas_accesorios").html("");
                           $("#entregas_accesorios").html(data[1]);
                       }else {
                           $("#entregas_accesorios").html("");
                       }
                   }
                });
            }
        </script>
        
    </head>

    <body class="cuerpo">

        <div>			

            <h1> Modificar actas de alta </h1>    
            
            <fieldset>	

                <dl>
                <dt><label for="fecha"> Fecha </label></dt>
                <dd><input readonly="readonly" type="text" id="fecha" class="text" value="<?php echo $fecha; ?>" /></dd>
                </dl>                
                
                <dl> 		
                <dt><label for="unidad_entrega"> Unidad entrega </label></dt>	
                <dd><input type="text" id="unidad_entrega" class="txtautomatico" readonly="readonly" value="S.M.A" /></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="unidad_recibe"> Unidad recibe </label></dt>	
                <dd><select id="unidad_recibe"> <?php echo $unidades; ?> </select></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="representante_sma"> Repr SMA </label></dt>	
                <dd><input type="text" id="representante_sma" class="text" value="<?php echo $representante_sma; ?>" /></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="representante_unidad"> Repr unidad </label></dt>	
                <dd><input type="text" id="representante_unidad" class="text" value="<?php echo $representante_unidad; ?>" /></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="supervision"> Supervision </label></dt>	
                <dd><input type="text" id="supervision" class="text" value="<?php echo $supervision; ?>" /></dd> 					
                </dl>                
 
                <dl> 		
                <dt><label for="observaciones"> Observaciones </label></dt>	
                <dd><textarea id="observaciones"> <?php echo $observaciones ?> </textarea></dd> 					
                </dl>
                
                <p><img src="<?php echo base_url() ?>images/barra.png" /></p>
                
                <p class="subtituloform"> Armamento a entregar </p>
                
                <dl> 		
                <dt><label for="nro_serie"> Nro serie </label></dt>	
                <dd><select id="nro_serie" onchange="cargoMarcas(this.value);"> <?php echo $nro_series; ?> </select> <img style="cursor: pointer;" onclick="busquedaFichas();" src="<?php echo base_url(); ?>images/search.png" /></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="marca"> Marca </label></dt>	
                <dd><select id="marca" onchange="cargoCalibres($('#nro_serie').val(), this.value);"> </select></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="calibre"> Calibre </label></dt>	
                <dd><select id="calibre" onchange="cargoModelos($('#nro_serie').val(), $('#marca').val(), this.value);"> </select></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="modelo"> Modelo </label></dt>	
                <dd><select id="modelo"> </select></dd> 					
                </dl>
                
                <button style="margin-right: 20px;" onclick="agregarFicha();"> Agregar armamento </button>     
                
                <p><img src="<?php echo base_url() ?>images/barra.png" /></p>
                
                <p class="subtituloform"> Accesorios a entregar </p>
                
                <dl> 		
                <dt><label for="nro_serie_accesorio"> Nro serie </label></dt>	
                <dd><select id="nro_serie_accesorio" onchange="cargoMarcasAccesorios(this.value);"> <?php echo $nro_series_accesorios; ?> </select> <img style="cursor: pointer;" onclick="busquedaAccesorios();" src="<?php echo base_url(); ?>images/search.png" /></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="marca_accesorio"> Marca </label></dt>	
                <dd><select id="marca_accesorio" onchange="cargoCalibresAccesorios($('#nro_serie_accesorio').val(), this.value);"> </select> </dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="calibre_accesorio"> Calibre </label></dt>	
                <dd><select id="calibre_accesorio" onchange="cargoModelosAccesorios($('#nro_serie_accesorio').val(), $('#marca_accesorio').val(), this.value);"> </select> </dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="modelo_accesorio"> Modelo </label></dt>	
                <dd><select id="modelo_accesorio" onchange="cargoNroAccesorios($('#nro_serie_accesorio').val(), $('#marca_accesorio').val(), $('#calibre_accesorio').val(), $('#modelo_accesorio').val());"> </select></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="nro_accesorio"> Nro accesorio </label></dt>	
                <dd><select id="nro_accesorio"> </select></dd> 					
                </dl>                
                
                <button style="margin-right: 20px;" onclick="agregarAccesorio();"> Agregar accesorio </button>               
                                
            </fieldset>	

            <fieldset class="action">	
                <button style="margin-right: 20px;" onclick="modificarActa();"> Modificar acta de alta </button> <button style="margin-right: 20px;" onclick="volverListado();"> Volver al listado de actas </button>
            </fieldset> 
            
            <hr />
            
            <div>
                
                <h1> Entregas armamento </h1>       
                
                <fieldset>	

                    <div id="imprimir">
                                 
                        <div class="datagrid" style="margin-top: 30px;">
                            <table> 
                                <thead>
                                    <tr>
                                        <th> Nro serie </th> <th> Marca </th> <th> Modelo </th> <th> Calibre </th> <th> </th>
                                    </tr>
                                </thead>
                                <tbody id="entregas_fichas"> <?php echo $entrega_fichas; ?> </tbody>
                                <tfoot>
                                    <tr> <td colspan="5"> <div id="paging"> <br /> </div> </td> </tr>
                                </tfoot>                                
                            </table> 
                        </div>
                        
                        <br />
                    
                    </div>    
                        
                </fieldset>	
                
            </div>
            
            <div>
                
                <h1> Entregas accesorios </h1>       
                
                <fieldset>	

                    <div id="imprimir">
                                 
                        <div class="datagrid" style="margin-top: 30px;">
                            <table> 
                                <thead>
                                    <tr> 
                                        <th> Nro serie </th> <th> Marca </th> <th> Modelo </th> <th> Calibre </th> <th> Nro accesorio </th> <th> </th>
                                    </tr>
                                </thead>
                                <tbody id="entregas_accesorios"> <?php echo $entrega_accesorios; ?> </tbody>
                                <tfoot>
                                    <tr> <td colspan="6"> <div id="paging"> <br /> </div> </td> </tr>
                                </tfoot>                                
                            </table> 
                        </div>
                        
                        <br />
                    
                    </div>    
                        
                </fieldset>	
                
            </div>            
            
        </div>        
        
    </body>
	
</html>