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
                
                var catalogo = $("#catalogo").val();
                
                if(catalogo != ""){
                    $("form").attr("action", "<?php echo base_url(); ?>upload/do_upload/"+catalogo);
                    $("form").submit();                   
                }else{
                    jAlert("ERROR: El numero de catalogo no puede ser vacio", "Error");
                }
            }
        
            function busquedaCatalogos() {
                $.colorbox({href:"<?php echo base_url('busqueda_catalogos'); ?>", top:false, iframe:false, innerWidth:900, innerHeight:700, title:"BUSQUEDA CATALOGOS", onClosed: function(){ cargoCatalogosFiltro(); } });
            }
            
            function cargoCatalogos() {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>upload/cargoCatalogos",
                   success: function(data) {
                       $("#catalogo").html(data);
                   }
                });
            }  
            
            function cargoCatalogosFiltro() {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>upload/cargoCatalogosFiltro",
                   success: function(data) {
                       $("#catalogo").html("");
                       $("#catalogo").html(data);
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
                <dt><label for="catalogo"> Catalogos </label></dt>
                <dd><select id="catalogo"> <?php echo $catalogos; ?> </select> <img style="cursor: pointer;" onclick="busquedaCatalogos();" src="<?php echo base_url(); ?>images/search.png" /> </dd>
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
