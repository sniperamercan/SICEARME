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
            
            function altaAccionPiezasAsociadas() {
                
                var nro_pieza_nueva    = $("#nro_pieza_nueva").val();
                var nro_pieza_anterior = $("#nro_pieza_anterior").val();
                
                var nro_parte    = $("#nro_parte").val();
                var nombre_parte = $("#nombre_parte").val();
                var nro_catalogo = $("#nro_catalogo").val();
                
                $.ajax({
                    type: "post",  
                    url: "<?php base_url(); ?>modificar_accion_piezas_asociadas/validarDatos",
                    data: "nro_pieza_nueva="+nro_pieza_nueva+"&nro_pieza_anterior="+nro_pieza_anterior+"&nro_parte="+nro_parte+"&nombre_parte="+nombre_parte+"&nro_catalogo="+nro_catalogo,
                    success: function(data){
                        if(data == 1){            
                            jAlert("CORRECTO: La pieza fue modificada para el armamento correctamente", "Correcto", function() { irAFrame('<?php echo base_url('modificar_accion_piezas_asociadas'); ?>','Taller armamento >> Accion >> Ordenes de trabajo'); });
                        }else{
                            jAlert(data, "Error");
                        }                            
                  }
                });               
            }            
            
            function volver() {
                irAFrame('<?php echo base_url('accion_ordenes_trabajo'); ?>','Taller armamento >> Accion >> Ordenes de trabajo');
            }
            
            function busquedaRepuestos() {
                $.colorbox({href:"<?php echo base_url('busqueda_repuestos_nro_pieza'); ?>", top:false, iframe:false, innerWidth:900, innerHeight:700, title:"BUSQUEDA REPUESTOS", onClosed: function(){ cargoRepuestosFiltro(); } });
            }
            
            function cargoRepuestosFiltro() {
                $.ajax({
                   type: "post",
                   dataType: "json",
                   url: "<?php base_url(); ?>modificar_accion_piezas_asociadas/cargoRepuestosFiltro",
                   success: function(data) {
               
                        $("#nro_pieza_nueva").val("");
                        $("#nro_parte").val("");
                        $("#nombre_parte").val("");
                        $("#nro_catalogo").val("");
                        
                        if(data[0] !== 0) {
                            $("#nro_pieza_nueva").val(data[0]);
                            $("#nro_parte").val(data[1]);
                            $("#nombre_parte").val(data[2]);
                            $("#nro_catalogo").val(data[3]);                           
                        }
                   }
                });                
            }
            
            function busquedaPiezasArmamento() {
                $.colorbox({href:"<?php echo base_url('busqueda_piezas'); ?>", top:false, iframe:false, innerWidth:900, innerHeight:700, title:"BUSQUEDA PIEZAS", onClosed: function(){ cargoPiezasArmamentoFiltro(); } });
            }
            
            function cargoPiezasArmamentoFiltro() {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>modificar_accion_piezas_asociadas/cargoPiezasArmamentoFiltro",
                   success: function(data) {
               
                        $("#nro_pieza_anterior").val("");
                        
                        if(data !== 0) {
                            $("#nro_pieza_anterior").val(data);
                        }
                   }
                });                
            }  
            
            function eliminarAccionAsociada(nro_cambio) {
            
                jConfirm('Esta seguro que desea eliminar esta accion ?', 'Elminar Accion', function(r) {
                    
                    if(r) {
                        $.ajax({
                            type: "post",  
                            url: "<?php base_url(); ?>modificar_accion_piezas_asociadas/eliminarAccionAsociada",
                            data: "nro_cambio="+nro_cambio,
                            success: function(data){
                                
                                if(data == 1) {
                                    irAFrame('<?php echo base_url('modificar_accion_piezas_asociadas'); ?>','Taller armamento >> Accion >> Ordenes de trabajo');
                                }else{
                                    jAlert("ERROR: Borrar accion de cambio de pieza no se puede ejecutar, debido a que la pieza del armamento ya no posee dicha pieza", "Error");
                                }
                          }
                        });                        
                    }
                    
                });
            }
            
        </script>
        
    </head>

    <body class="cuerpo">

        <div>			

            <h1> Modificar Accion de cambios de piezas asociadas a un armamento </h1>    
            
            <fieldset>	
            
                <dl>
                <dt><label> Orden de trabajo  </label></dt>
                <dd><label> Nro - <?php echo $nro_orden ?>  </label></dd>
                </dl>                 
                
                <dl>
                <dt><label> Buscar repuesto </label></dt>
                <dd><img style="cursor: pointer;" onclick="busquedaRepuestos();" src="<?php echo base_url(); ?>images/search.png" /> </dd>
                </dl>  
                
                <dl>
                <dt><label for="nro_pieza_nueva"> Nro pieza </label></dt>
                <dd><input readonly="readonly" type="text" id="nro_pieza_nueva" class="txtautomatico" /> </dd>
                </dl>  
                
                <dl>
                <dt><label for="nro_parte"> Nro parte </label></dt>
                <dd><input readonly="readonly" type="text" id="nro_parte" class="txtautomatico" /> </dd>
                </dl>
                
                <dl>
                <dt><label for="nombre_parte"> Nombre parte </label></dt>
                <dd><input readonly="readonly" type="text" id="nombre_parte" class="txtautomatico" /> </dd>
                </dl>
                
                <dl>
                <dt><label for="nro_catalogo"> Nro catalogo </label></dt>
                <dd><input readonly="readonly" type="text" id="nro_catalogo" class="txtautomatico" /> </dd>
                </dl>                

                <p><img src="<?php echo base_url() ?>images/barra.png" /></p>
                
                <dl>
                <dt><label> Piezas armamento </label></dt>
                <dd><img style="cursor: pointer;" onclick="busquedaPiezasArmamento();" src="<?php echo base_url(); ?>images/search.png" /> </dd>
                </dl>                 
                
                <dl>
                <dt><label for="nro_pieza_anterior"> Nro pieza </label></dt>
                <dd><input readonly="readonly" type="text" id="nro_pieza_anterior" class="txtautomatico" /> </dd>
                </dl>     
                
            </fieldset>	

            <fieldset class="action">	
                <button style="margin-right: 20px;" onclick="altaAccionPiezasAsociadas();"> Ingresar pieza </button> 
                <button style="margin-right: 20px;" onclick="volver();"> Volver </button> 
            </fieldset>  
            
            <hr />
            
            <div>
                
                <h1> Cambio de piezas </h1>       
                
                <fieldset>	

                    <div id="imprimir">
                                 
                        <dl>
                        <dt></dt>
                        <dd><label> Nro pieza actual que tiene el armamento - <?php echo $nro_pieza_actual ?></label></dd>
                        </dl> 
                        
                        <div class="datagrid" style="margin-top: 30px;">
                            <table> 
                                <thead style="text-align: center;">
                                    <tr>
                                        <th> Nro cambio </th> <th> Nro pieza nueva </th> <th> Nro pieza anterior </th> <th> Borrar </th> 
                                    </tr>
                                </thead>
                                <tbody id="acciones"> <?php echo $acciones; ?> </tbody>
                                <tfoot>
                                    <tr> <td colspan="4"> <div id="paging"> <br /> </div> </td> </tr>
                                </tfoot>                                
                            </table> 
                        </div>
                    
                    </div>    
                        
                </fieldset>	
                
            </div>
            
        </div>        
        
    </body>
	
</html>