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
                $("#nro_compra").focus();
                $("#fecha").datepicker({ dateFormat: "yy-mm-dd", monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"], dayNames: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"], dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"], changeYear: true, changeMonth: true, dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"], monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"] } );
                $("#fabricacion").datepicker({ dateFormat: "yy-mm-dd", monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"], dayNames: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"], dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"], changeYear: true, changeMonth: true, dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"], monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"] } );
                $("#vencimiento").datepicker({ dateFormat: "yy-mm-dd", monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"], dayNames: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"], dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"], changeYear: true, changeMonth: true, dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"], monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"] } );
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
            });	

            function ingresarDatos() {
                
                var usuario   = $("#usuario").val();
                var nombre    = $("#nombre").val();
                var apellido  = $("#apellido").val();
                var clave     = $("#clave").val();
                
                $.ajax({
                    type: "post",  
                    dataType: "json",
                    url: "<?php base_url(); ?>agregar_usuario/validarDatos",
                    data: "usuario="+usuario+"&nombre="+nombre+"&apellido="+apellido+"&clave="+clave+"&persmisos="+JSON.stringify(permisos),
                    success: function(data){
                        if(data == "1"){            
                            jAlert("Usuario agregado al sistema con exito", "Correcto", function() { irAFrame('<?php echo base_url('agregar_usuario'); ?>','Adminitracion >> Agregar usuarios'); });
                        }else{
                            jAlert(data, "Error");
                        }                            
                  }
                });               
            }
        </script>
        
    </head>

    <body class="cuerpo">

        <div>			

            <h1> Modificar catalogos </h1>    
            
            <fieldset>	

                <dl> 		
                <dt><label for="tipo_arma"> Tipo arma </label></dt>	
                <dd><select id="tipo_arma"> <?php echo $tipo_arma; ?> </select> <img style="cursor: pointer;" onclick="crearTipoArma();" src="<?php echo base_url(); ?>images/sumar.png" /></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="marca"> Marca </label></dt>	
                <dd><select id="marca"> <?php echo $marca; ?> </select> <img style="cursor: pointer;" onclick="crearMarca();" src="<?php echo base_url(); ?>images/sumar.png" /></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="calibre"> Calibre </label></dt>	
                <dd><select id="calibre"> <?php echo $calibre; ?> </select> <img style="cursor: pointer;" onclick="crearCalibre();" src="<?php echo base_url(); ?>images/sumar.png" /></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="modelo"> Modelo </label></dt>	
                <dd><select id="modelo"> <?php echo $modelo; ?> </select> <img style="cursor: pointer;" onclick="crearModelo();" src="<?php echo base_url(); ?>images/sumar.png" /></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="sistema"> Sistema </label></dt>	
                <dd><select id="sistema"> <?php echo $sistema; ?> </select> <img style="cursor: pointer;" onclick="crearSistema();" src="<?php echo base_url(); ?>images/sumar.png" /></dd> 					
                </dl>
                
                <dl>
                <dt><label for="empresa"> Empresa </label></dt>
                <dd><input type="text" id="empresa" class="text" /> <img style="cursor: pointer;" onclick="crearTipoAccesorio();" src="<?php echo base_url(); ?>images/sumar.png" /></dd>
                </dl>                
                
                <dl>
                <dt><label for="pais_empresa"> Pais empresa </label></dt>
                <dd><select id="pais_empresa"> <?php echo $paises ?> </select></dd>
                </dl>                 

                <dl>
                <dt><label for="fabricacion"> Año Fabricacion </label></dt>
                <dd><input type="text" id="fabricacion" class="text" /></dd>
                </dl>
                
                <dl>
                <dt><label for="vencimiento"> Vencimiento </label></dt>
                <dd><input type="text" id="vencimiento" class="text" /></dd>
                </dl>  
                
            </fieldset>	

            <fieldset class="action">	
                <button style="margin-right: 20px;" onclick="ingresarDatos();"> Modificar catalogo </button>
            </fieldset>  
            
        </div>        
        
    </body>
	
</html>