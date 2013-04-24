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

            function modificarAccionSimple() {
                
                var fecha         = $("#fecha").val();
                var nro_orden     = $("#nro_orden").val();
                var seccion       = $("#seccion").val();
                var observaciones = $("#observaciones").val();
                
                $.ajax({
                    type: "post",  
                    url: "<?php base_url(); ?>modificar_accion_simple/validarDatos",
                    data: "fecha="+fecha+"&nro_orden="+nro_orden+"&seccion="+seccion+"&observaciones="+observaciones,
                    success: function(data){
                        if(data == 1){            
                            jAlert("CORRECTO: La accion fue modificada correctamente", "Correcto", function() { irAFrame('<?php echo base_url('accion_ordenes_trabajo'); ?>','Taller armamento >> Accion >> Ordenes de trabajo'); });
                        }else{
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
                   url: "<?php base_url(); ?>modificar_accion_ordenes_trabajo/cargoSecciones",
                   success: function(data) {
                       $("#seccion").html(data);
                   }
                });
            }     
            //fin cargo y creo Empresas
    
            function volver() {
                irAFrame('<?php echo base_url('accion_ordenes_trabajo'); ?>','Taller armamento >> Accion >> Ordenes de trabajo');
            }
            
    
        </script>
        
    </head>

    <body class="cuerpo">

        <div>			

            <h1> Modificar accion simple </h1>    
            
            <fieldset>	

                <dl>
                <dt><label for="fecha"> Fecha </label></dt>
                <dd><input readonly="readonly" type="text" id="fecha" class="text" value="<?php echo $fecha; ?>" /></dd>
                </dl>                
                
                <dl>
                <dt><label for="nro_orden"> Nro orden </label></dt>
                <dd><input readonly="readonly" type="text" id="nro_orden" class="txtautomatico" value="<?php echo $nro_orden; ?>" /></dd>
                </dl>                 
                
                <p><img src="<?php echo base_url() ?>images/barra.png" /></p>
                
                <p class="subtituloform"> Datos del arma </p>
                
                <dl>
                <dt><label for="nro_serie"> Nro serie </label></dt>
                <dd><input readonly="readonly" type="text" id="nro_serie" class="txtautomatico" value="<?php echo $nro_serie; ?>" /></dd>
                </dl>     
                
                <dl>
                <dt><label for="marca"> Marca </label></dt>
                <dd><input readonly="readonly" type="text" id="marca" class="txtautomatico" value="<?php echo $marca; ?>" /></dd>
                </dl> 
                
                <dl>
                <dt><label for="calibre"> Calibre </label></dt>
                <dd><input readonly="readonly" type="text" id="calibre" class="txtautomatico" value="<?php echo $calibre; ?>" /></dd>
                </dl> 
                
                <dl>
                <dt><label for="modelo"> Modelo </label></dt>
                <dd><input readonly="readonly" type="text" id="modelo" class="txtautomatico" value="<?php echo $modelo; ?>" /></dd>
                </dl> 
                
                <dl>
                <dt><label for="tipo_arma"> Tipo </label></dt>
                <dd><input readonly="readonly" type="text" id="tipo_arma" class="txtautomatico" value="<?php echo $tipo_arma; ?>" /></dd>
                </dl>                 
                
                <p><img src="<?php echo base_url() ?>images/barra.png" /></p>
                
                <dl>
                <dt><label for="seccion"> Seccion </label></dt>
                <dd><select id="seccion"> <?php echo $secciones ?> </select> <img style="cursor: pointer;" onclick="crearSeccion();" src="<?php echo base_url(); ?>images/sumar.png" /></dd>
                </dl>       
                
                <dl> 		
                <dt><label for="observaciones"> Observaciones </label></dt>	
                <dd><textarea id="observaciones"> <?php echo $detalles; ?> </textarea></dd> 					
                </dl>                
                
            </fieldset>	

            <fieldset class="action">	
                <button style="margin-right: 20px;" onclick="modificarAccionSimple();"> Modificar accion simple </button> 
                <button style="margin-right: 20px;" onclick="volver();"> Volver </button> 
            </fieldset>  
            
        </div>        
        
    </body>
	
</html>