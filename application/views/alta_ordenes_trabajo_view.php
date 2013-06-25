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

            function altaOrdenTrabajo() {
    
                var fecha         = $("#fecha").val();
                var unidad        = $("#unidad").val();
                var nro_serie     = $("#nro_serie").val();
                var marca         = $("#marca").val();
                var calibre       = $("#calibre").val();
                var modelo        = $("#modelo").val();     
                
                var observaciones = cambiarCaracter();
                
                $.ajax({
                    type: "post",  
                    dataType: "json",
                    url: "<?php base_url(); ?>alta_ordenes_trabajo/validarDatos",
                    data: "fecha="+fecha+"&unidad="+unidad+"&nro_serie="+nro_serie+"&marca="+marca+"&calibre="+calibre+"&modelo="+modelo+"&observaciones="+observaciones,
                    success: function(data){
                        if(data[0] == "1"){            
                            jAlert("Orden de trabajo generada con exito - <b> Nro de orden generado = "+data[1]+" </b>", "Correcto", function() { irAFrame('<?php echo base_url('alta_ordenes_trabajo'); ?>','Taller armamento >> Alta >> Ordenes de trabajo'); });
                        }else{
                            jAlert(data[0], "Error");
                        }                            
                    }
                });               
            }
            
            function cambiarCaracter(){
                var val = $("#observaciones").val();    
                while (val !=(val = val.replace('&', '')));
                return val;
            }
            
            function busquedaFichas() {
                $.colorbox({href:"<?php echo base_url('busqueda_fichas_taller'); ?>", top:false, iframe:false, innerWidth:900, innerHeight:700, title:"BUSQUEDA FICHAS", onClosed: function(){ cargoFichasFiltro(); } });
            }
            
            function cargoFichasFiltro() {
                $.ajax({
                   type: "post",
                   dataType: "json",
                   url: "<?php base_url(); ?>alta_ordenes_trabajo/cargoFichasFiltro",
                   success: function(data) {
                       $("#nro_serie").val("");
                       $("#nro_serie").val(data[0]);
                       $("#marca").val("");
                       $("#marca").val(data[1]);
                       $("#calibre").val("");
                       $("#calibre").val(data[2]);
                       $("#modelo").val("");
                       $("#modelo").val(data[3]);
                       $("#tipo_arma").val("");
                       $("#sistema").val("");
                       $("#tipo_arma").val(data[4]);
                       $("#sistema").val(data[5]);                       
                   }
                });                
            }            
            
            function vacioCamposArma() {
                $("#marca").html("");
                $("#calibre").html("");
                $("#modelo").html("");
                $("#tipo_arma").val("");
                $("#sistema").val("");
            }
            
            function cargoMarcas(nro_serie) {
                
                if(nro_serie == "") {
                    vacioCamposArma();
                }else {
                    $.ajax({
                       type: "post",
                       url: "<?php base_url(); ?>alta_ordenes_trabajo/cargoMarcas",
                       data: "nro_serie="+nro_serie,
                       success: function(data) {
                           $("#marca").html("");
                           $("#marca").html(data);
                       }
                    }); 
                }
            }
            
            function cargoCalibres(nro_serie, marca) {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>alta_ordenes_trabajo/cargoCalibres",
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
                   url: "<?php base_url(); ?>alta_ordenes_trabajo/cargoModelos",
                   data: "nro_serie="+nro_serie+"&marca="+marca+"&calibre="+calibre,
                   success: function(data) {
                       $("#modelo").html("");
                       $("#modelo").html(data);
                   }
                });                
            }
            
            function cargoDatos(nro_serie, marca, calibre, modelo) {
                $.ajax({
                   type: "post",
                   dataType: "json",
                   url: "<?php base_url(); ?>alta_ordenes_trabajo/cargoDatos",
                   data: "nro_serie="+nro_serie+"&marca="+marca+"&calibre="+calibre+"&modelo="+modelo,
                   success: function(data) {
                       $("#tipo_arma").val("");
                       $("#sistema").val("");
                       $("#tipo_arma").val(data[0]);
                       $("#sistema").val(data[1]);
                   }
                });                 
            }
            
            function verHistorico() {
                
                var nro_serie     = $("#nro_serie").val();
                var marca         = $("#marca").val();
                var calibre       = $("#calibre").val();
                var modelo        = $("#modelo").val();                
            
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>alta_ordenes_trabajo/verHistorico",
                   data: "nro_serie="+nro_serie+"&marca="+marca+"&calibre="+calibre+"&modelo="+modelo,
                   success: function(data) {
                        jAlert(data, "Historico armamento");
                   }
                });              
            
            }
            
        </script>
        
    </head>

    <body class="cuerpo">

        <div>			

            <h1> Alta de orden de trabajo </h1>    
            
            <fieldset>	
                
                <dl>
                <dt><label for="fecha"> Fecha <font color="red"> * </font> </label></dt>
                <dd><input readonly="readonly" type="text" id="fecha" class="text" /></dd>
                </dl>                
                
                <p><img style='width: 100%; height: 6px;' src="<?php echo base_url() ?>images/barra.png" /></p>
                
                <p class="subtituloform"> Datos del arma </p>
                
                <dl> 		
                <dt><label for="nro_serie"> Nº serie <font color="red"> * </font> </label></dt>	
                <dd><input readonly="readonly" type="text" id="nro_serie" class="txtautomatico" /> <img style="cursor: pointer;" onclick="busquedaFichas();" src="<?php echo base_url(); ?>images/search.png" /></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="marca"> Marca <font color="red"> * </font> </label></dt>	
                <dd><input readonly="readonly" type="text" id="marca" class="txtautomatico" /></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="calibre"> Calibre <font color="red"> * </font> </label></dt>	
                <dd><input readonly="readonly" type="text" id="calibre" class="txtautomatico" /></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="modelo"> Modelo <font color="red"> * </font> </label></dt>	
                <dd><input readonly="readonly" type="text" id="modelo" class="txtautomatico" /></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="tipo_arma"> Tipo arma <font color="red"> * </font> </label></dt>	
                <dd><input readonly="readonly" type="text" id="tipo_arma" class="txtautomatico" /></dd> 					
                </dl>
                
                <dl>
                <dt><label for="sistema"> Sistema <font color="red"> * </font> </label></dt>
                <dd><input readonly="readonly" type="text" id="sistema" class="txtautomatico" /></dd>
                </dl>                
                
                <p><img style='width: 100%; height: 6px;' src="<?php echo base_url() ?>images/barra.png" /></p>
                
                <dl>
                <dt><label for="unidad"> Unidad <font color="red"> * </font> </label></dt>
                <dd><select id="unidad"> <?php echo $unidades; ?> </select> <img style="cursor: pointer;" onclick="verHistorico();" src="<?php echo base_url(); ?>images/eye.png" alt="ver historico" /></dd>
                </dl>                  
                
                <dl> 		
                <dt><label for="observaciones"> Observaciones </label></dt>	
                <dd><textarea id="observaciones"> </textarea></dd> 					
                </dl>                
                
            </fieldset>	

            <fieldset class="action">	
                <button style="margin-right: 20px;" onclick="altaOrdenTrabajo();"> Alta de orden de trabajo </button>
            </fieldset>  
            
        </div>        
        
    </body>
	
</html>