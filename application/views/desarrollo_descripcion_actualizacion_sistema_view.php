<!DOCTYPE html>
<html lang="es">
    
    <head>
    
        <script type="text/javascript">

            $(document).ready(function() {
                $("#fecha").datepicker({ dateFormat: "yy-mm-dd", monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"], dayNames: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"], dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"], changeYear: true, changeMonth: true, dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"], monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"] } );
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button();
            });	

            function ingresarDatos() {
                
                var version        = $("#version").text();
                var fecha          = $("#fecha").val();
                var descripcion    = $("#descripcion").val();
                var critica        = $("#critica").is(':checked'); 
                
                if(critica) {
                    critica = 1;
                }else {
                    critica = 0;
                }
                
                $.ajax({
                    type: "post",                    
                    url: "<?php base_url(); ?>desarrollo_descripcion_actualizacion_sistema/validarDatos",
                    data: "version="+version+"&fecha="+fecha+"&descripcion="+descripcion+"&critica="+critica,
                    success: function(data){
                        if(data == "1"){            
                            jAlert("Nueva actualizacion de sistema ingresada con exito", "Correcto", function() { irAFrame('<?php echo base_url('desarrollo_descripcion_actualizacion_sistema'); ?>','Administracion >> Desarrollo >> Descripcion actualizacion'); });
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

            <h1>Descripción de actualizacion sobre versión actual del sistema</h1>    
            
            <fieldset>	

                <dl>
                <dt><label for="version"> Versión actual </label></dt>
                <dd><label id="version"> <?php echo $version; ?> </label></dd>
                </dl>                 

                <dl>
                <dt><label for="fecha"> Fecha <font color="red">*</font> </label></dt>
                <dd><input type="text" id="fecha" class="date" readonly="readonly" /></dd>
                </dl>                
                
                <dl>
                <dt><label for="descripcion"> Descripción <font color="red">*</font> </label></dt>
                <dd><textarea id="descripcion"> </textarea></dd>
                </dl>
                
                <dl>
                <dt><label for="critica"> Crítica </label></dt>
                <dd><input id="critica" name="critica" type="checkbox" value="1"></dd>
                </dl>
                
            </fieldset>	

            <fieldset class="action">	
                <button onclick="ingresarDatos();"> Agregar descripción </button>
            </fieldset>           
            
        </div>        
        
    </body>
	
</html>