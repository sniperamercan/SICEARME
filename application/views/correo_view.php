<!DOCTYPE html>
<html lang="es">
    
    <head>
    
        <script type="text/javascript">

            $(document).ready(function() {
                
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button();
                
                $("#correos").smartupdater({
                    type: 'post',
                    url: '<?php echo base_url(); ?>correo/cargarCorreos',
                    minTimeout: 60000 // 60 seconds
                    }, function (data) {
                           $("#correos").html('<tr style="text-align: center; background: #F2F5A9; font-weight: bold;"><td>  </td> <td> Envio </td> <td> Asunto </td> <td> Fecha </td> <td> </td></tr>' + data); 
                       }
                );
            });	
            
            function eliminarCorreo(id_correo) {
                
                $.ajax({
                    type: "post",                    
                    url: "<?php base_url(); ?>correo/eliminarCorreo/"+id_correo,
                    success: function(data){
                        if(data == "1"){            
                            jAlert("Correo eliminado correctamente", "Correcto", function(){ irAFrame('<?php echo base_url('correo'); ?>','Correo'); });
                        }else{
                            jAlert(data, "Error");
                        }                            
                  }
                });
            }
            
            function vaciarBandeja() {
            
                $.ajax({
                    type: "post",                    
                    url: "<?php base_url(); ?>correo/vaciarBandeja",
                    success: function(data){
                        if(data == "1"){            
                            jAlert("Bandeja de correos vaciada correctamente", "Correcto", function(){ irAFrame('<?php echo base_url('correo'); ?>','Correo'); });
                        }else{
                            jAlert(data, "Error");
                        }                            
                  }
                });            
            }
            
            function verCorreo(id_correo) {
                
                $.ajax({
                   type: 'post',
                   url: '<?php echo base_url(); ?>correo/verCorreo',
                   data: 'id_correo='+id_correo,
                   success: function(){
                       irAFrame('<?php echo base_url('ver_correo'); ?>','Ver correo');
                  }
                });
            
            }            
            
            function redactarCorreo() {
                $.colorbox({href: "<?php echo base_url() ?>redactar_correo", iframe: false, innerWidth: "55%", innerHeight: "500px", scrolling: false, opacity: 0.75, onClosed: function(){ irAFrame('<?php echo base_url('correo'); ?>','Correo'); } });
            }
            
        </script>
        
    </head>

    <body class="cuerpo">

        <div>			

            <h1> CORREOS </h1>  
            
            <fieldset>	

                <button onclick="redactarCorreo();"> Redactar nuevo correo </button> &emsp;&emsp; <button onclick="vaciarBandeja();"> Vaciar bandeja </button> </dt>
                
                <br /> <br />
                
                <table cellpadding="3" cellspacing="3" id="correos" width="100%" style="font-size: 13px;"> 

                    <tr style="text-align: center; background: #F2F5A9; font-weight: bold;">
                        <td>  </td> <td> Envio </td> <td> Asunto </td> <td> Fecha </td> <td> </td>
                        <?php echo $correo; ?>
                    </tr>    

                </table>
                
            </fieldset>	
            
        </div>        
        
    </body>
	
</html>