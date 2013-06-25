<!DOCTYPE html>
<html lang="es">
    
    <head>
    
        <script type="text/javascript">
            $(document).ready(function() {
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button();
            });	

            function responder() {
                $.colorbox({href: "<?php echo base_url() ?>redactar_correo", iframe: false, innerWidth: "50%", innerHeight: "500px", scrolling: false, opacity: 0.75, onClosed: function(){ irAFrame('<?php echo base_url('ver_correo'); ?>','Ver correo'); } });
            }            
        </script>
        
    </head>

    <body class="cuerpo">

        <div>			

            <h1> <?php echo $asunto; ?> </h1>     
            
            <fieldset>	

                <label> <?php echo $envia; ?> </label> &emsp;&emsp;&emsp; <button onclick="responder();"> Responder </button> &emsp;&emsp;&emsp; <button onclick="irAFrame('<?php echo base_url('correo'); ?>','Correo');"> Volver </button>
                
                <dl>
                <dt> <textarea class="correo" readonly="readonly"> <?php echo $contenido; ?> </textarea> </dt>
                <dd></dd>
                </dl>                
                
            </fieldset>	
            
        </div>        
        
    </body>
	
</html>