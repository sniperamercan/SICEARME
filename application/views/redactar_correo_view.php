<!DOCTYPE html>
<html lang="es">
    
    <head>
        
        <script type="text/javascript">
            
            $(document).ready(function() {
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button();
            });	
            
            function ingresarDatos() {
                
                var destinatario  = $("#destinatario").val();
                var asunto        = $("#asunto").val();
                var contenido     = $("#contenido").val();

                $.ajax({
                    type: "post",                    
                    url: "<?php base_url(); ?>redactar_correo/validarDatos",
                    data: "destinatario="+destinatario+"&asunto="+asunto+"&contenido="+contenido,
                    success: function(data){
                        if(data == "1"){            
                            jAlert("Correo enviado correctamente", "Correcto", function(){ parent.$.fn.colorbox.close(); });
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

            <h1> REDACTAR CORREO </h1> 
            
            <fieldset>	

                <dl>
                <dt> <label> Destinatario </label> </dt>
                <dd> <select id="destinatario"> <?php echo $usuarios ?> </select> </dd>
                </dl>                 
                
                <dl>
                <dt> <label> Asunto </label> </dt>
                <dd> <input type="text" id="asunto" class="text" value="<?php echo $asunto ?>"/> </dd>
                </dl>                 
                
                <dl>
                <dt> <textarea class="correo" id="contenido"> </textarea> </dt>
                <dd></dd>
                </dl> 
                
                <dl  >
                <dt style="text-align: left;"> <button onclick="ingresarDatos();"> Enviar </button> </dt>
                <dd></dd>
                </dl>                
                
            </fieldset>	
            
        </div>        
        
    </body>
	
</html>
