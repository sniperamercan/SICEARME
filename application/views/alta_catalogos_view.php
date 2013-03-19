<!DOCTYPE html>
<html lang="es">
    
    <head>
        
        <script type="text/javascript">

            $(document).ready(function() {
                $("#fecha").datepicker({ dateFormat: "yy-mm-dd", monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"], dayNames: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"], dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"], changeYear: true, changeMonth: true, dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"], monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"] } );
                $("#fabricacion").datepicker({ dateFormat: "yy-mm-dd", monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"], dayNames: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"], dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"], changeYear: true, changeMonth: true, dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"], monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"] } );
                $("#vencimiento").datepicker({ dateFormat: "yy-mm-dd", monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"], dayNames: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"], dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"], changeYear: true, changeMonth: true, dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"], monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"] } );
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
            });	

            function altaCatalogo() {
                
                var tipo_arma    = $("#tipo_arma").val();
                var marca        = $("#marca").val();
                var calibre      = $("#calibre").val();
                var modelo       = $("#modelo").val();
                var sistema      = $("#sistema").val();
                var empresa      = $("#empresa").val();
                var pais_empresa = $("#pais_empresa").val();
                var fabricacion  = $("#fabricacion").val();
                var vencimiento  = $("#vencimiento").val();
                
                $.ajax({
                    type: "post",  
                    dataType: "json",
                    url: "<?php base_url(); ?>alta_catalogos/validarDatos",
                    data: "tipo_arma="+tipo_arma+"&marca="+marca+"&calibre="+calibre+"&modelo="+modelo+"&sistema="+sistema+"&empresa="+empresa+"&pais_empresa="+pais_empresa+"&fabricacion="+fabricacion+"&vencimiento="+vencimiento,
                    success: function(data){
                        if(data[0] == "1"){            
                            jAlert("Catalogo ingresado con exito - Nro de catalogo = "+data[1], "Correcto", function() { irAFrame('<?php echo base_url('alta_catalogos'); ?>','O.C.I >> Alta >> Catalogos'); });
                        }else{
                            jAlert(data[0], "Error");
                        }                            
                  }
                });               
            }
        </script>
        
    </head>

    <body class="cuerpo">

        <div>			

            <h1> Alta catalogos </h1>    
            
            <fieldset>	
                
                <dl> 		
                <dt><label for="tipo_arma"> Tipo arma </label></dt>	
                <dd><select id="tipo_arma"> <?php echo $tipos_armas; ?> </select> <img style="cursor: pointer;" onclick="crearTipoArma();" src="<?php echo base_url(); ?>images/sumar.png" /></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="marca"> Marca </label></dt>	
                <dd><select id="marca"> <?php echo $marcas; ?> </select> <img style="cursor: pointer;" onclick="crearMarca();" src="<?php echo base_url(); ?>images/sumar.png" /></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="calibre"> Calibre </label></dt>	
                <dd><select id="calibre"> <?php echo $calibres; ?> </select> <img style="cursor: pointer;" onclick="crearCalibre();" src="<?php echo base_url(); ?>images/sumar.png" /></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="modelo"> Modelo </label></dt>	
                <dd><select id="modelo"> <?php echo $modelos; ?> </select> <img style="cursor: pointer;" onclick="crearModelo();" src="<?php echo base_url(); ?>images/sumar.png" /></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="sistema"> Sistema </label></dt>	
                <dd><select id="sistema"> <?php echo $sistemas; ?> </select> <img style="cursor: pointer;" onclick="crearSistema();" src="<?php echo base_url(); ?>images/sumar.png" /></dd> 					
                </dl>
                
                <dl>
                <dt><label for="empresa"> Empresa </label></dt>
                <dd><select id="empresa"> <?php echo $empresas; ?> </select> <img style="cursor: pointer;" onclick="crearTipoAccesorio();" src="<?php echo base_url(); ?>images/sumar.png" /></dd>
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
                <button style="margin-right: 20px;" onclick="altaCatalogo();"> Alta catalogo </button>
            </fieldset>  
            
        </div>        
        
    </body>
	
</html>