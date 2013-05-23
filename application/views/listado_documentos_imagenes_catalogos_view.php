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
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
            });	
        
            function previo() {
                
                var catalogo = $("#catalogo").val();
                
                if(catalogo != ""){
                    $("form").attr("action", "<?php echo base_url(); ?>listado_documentos_imagenes_catalogos/do_upload/"+catalogo);
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
                   url: "<?php base_url(); ?>listado_documentos_imagenes_catalogos/cargoCatalogos",
                   success: function(data) {
                       $("#catalogo").html(data);
                   }
                });
            }  
            
            function cargoCatalogosFiltro() {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>listado_documentos_imagenes_catalogos/cargoCatalogosFiltro",
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
            
            function cargoDocumentos(catalogo) {
                
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>listado_documentos_imagenes_catalogos/cargoDocumentos",
                   data: "catalogo="+catalogo,
                   success: function(data) {
                       $("#archivos").html("");
                       $("#archivos").html(data);
                   }
                });                
                
            }
            
        
        </script>
        
    </head>

    <?php echo $error;?>

    <body class="cuerpo">

        <div>			
            
            <h1> Documentos / imágenes (catálogos) </h1>        
            
            <fieldset>	

                <dl>
                <dt><label for="catalogo"> Catálogos </label></dt>
                <dd><select onchange="cargoDocumentos(this.value);" id="catalogo"> <?php echo $catalogos; ?> </select> <img style="cursor: pointer;" onclick="busquedaCatalogos();" src="<?php echo base_url(); ?>images/search.png" /> </dd>
                </dl>              
                
            </fieldset>	

            <div class="datagrid">

                <table>

                    <thead style='text-align: center; cursor: pointer;'>
                        <tr>      
                            <th> Archivo </th>
                        </tr>
                    </thead>

                    <tbody id="archivos"> </tbody>   

                    <tfoot>
                        <tr> <td colspan="1"> <div id="paging"> <br /> </div> </td> </tr>
                    </tfoot>

               </table>  

           </div>            
            

        </div>
        
    </body>

</html>
