<!DOCTYPE html>
<html lang="es">
    
    <head>

        <script type="text/javascript">

            $(document).ready(function() {
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
            });	
        
            function previo() {
                
                var rut = $("#rut").val();
                
                if(rut != ""){
                    $("form").attr("action", "<?php echo base_url(); ?>upload/do_upload/"+rut);
                    $("form").submit();                   
                }else{
                    jAlert("El numero de rut de la empresa no puede ser vacio", "Error");
                }
            }
        
            function buscarEmpresa() {
                $.colorbox({href: "<?php echo base_url() ?>busqueda_empresa", title: "BUSQUEDA EMPRESA", iframe: false, innerWidth: "60%", innerHeight: "750px", onClosed: function(){ cargoRut(); } });              
            }
            
            function cargoRut() {
                
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>upload/cargoRut",
                   success: function(data) {
                       $("#rut").html(data);
                   }
                }); 
                
            }  
            
            $('.add_field').click(function(){

                $.ajax({
                   type: "post",
                   dataType: "json",
                   url: "<?php base_url(); ?>upload/cargoId",
                   success: function(data) {
                       var concat ='<tbody id="'+data[0]+'_all"> <tr> <td> <input onchange="cargoNombre('+data[0]+');" type="file" name="userfile[]" id="'+data[0]+'" /> </td> </tr> <tr> <td id="'+data[0]+'_name">  </td> <td> <img style="cursor: pointer;" onclick="eliminoInput('+data[0]+');" src="<?php echo base_url() ?>images/remove.png" /> </td></tr> <tr> <td colspan="2"> <hr /><br /> </td> </tr> </tbody>';
                       $("#archivos").append(concat);
                       
                       if(data[1] == 5) {
                           $('.add_field').hide();
                       }
                   }
                });
            });
 
            function eliminoInput(id) {
                
                $.ajax({
                   type: "post",
                   dataType: "json",
                   url: "<?php base_url(); ?>upload/quitoId",
                   success: function(data) {
                       $("#"+id+"_all").remove();
                       
                       if(data < 5) {
                           $('.add_field').show();
                       }
                   }
                });
            }
            
            function cargoNombre(id) {
                var aux = $('#'+id).val();
                $('#'+id+'_name').html(aux);                
            }
            
        
        </script>
        
    </head>

    <?php echo $error;?>

    <body class="cuerpo">

        <div>			
            
            <form enctype="multipart/form-data" method="POST">

            <h1> Subir archivos catalogo </h1>        
            
            <fieldset>	

                <dl>
                <dt><label for="rut"> Catalogos </label></dt>
                <dd><select id="rut"> <?php echo $empresas; ?> </select> <img style="cursor: pointer;" onclick="buscarEmpresa();" src="<?php echo base_url(); ?>images/search.png" /> </dd>
                </dl>              
                
                <div style="padding-left: 20px; margin-top: 50px" id="archivos">
                    
                    <label style="margin-right: 10px;"> Archivos </label> <span class="add_field" style="cursor: pointer;"><img src="<?php echo base_url() ?>images/add.png" /></span>
                    
                    <br /><br />
                    
                    <table>
                        
                        <tbody id="archivos">
     
                            
                        </tbody>    
                        
                    </table>    
                    
                    
                </div>    
                
            </fieldset>	

            </form>

            
            <fieldset class="action">	
                <button onclick="previo();"> Subir archivos </button>
            </fieldset>

            
        </div>
        
    </body>

</html>
