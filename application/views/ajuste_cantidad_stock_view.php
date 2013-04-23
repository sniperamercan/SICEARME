<!DOCTYPE html>
<html lang="es">
    
    <head>
        
        <script type="text/javascript">

            $(document).ready(function() {
                $("#cantidad").focus();
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
            });	

            function ajusteCantidad() {
                
                var nro_parte   = $("#nro_parte").val();
                var cantidad    = $("#cantidad").val();
                
                $.ajax({
                    type: "post",  
                    url: "<?php base_url(); ?>ajuste_cantidad_stock/validarDatos",
                    data: "nro_parte="+nro_parte+"&cantidad="+cantidad,
                    success: function(data){
                        if(data == "1"){            
                            jAlert("Ajuste realizado con exito", "Correcto", function() { parent.$.fn.colorbox.close(); });
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

            <h1> Ajuste cantidad de stock </h1>    
            
            <fieldset>	

                <dl>
                <dt><label for="cantidad"> Cantidad </label></dt>
                <dd><input type="text" id="cantidad" class="text" /></dd>
                </dl>
                
            </fieldset>	

            <fieldset class="action">	
                <button style="margin-right: 20px;" onclick="ajusteCantidad();"> Editar cantidad </button>
            </fieldset>  
            
        </div>        
        
    </body>
	
</html>