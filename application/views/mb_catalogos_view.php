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
                cargoConsulta();
            });	     
     
            function filtrar(){
                
                var tipo_arma   = $("#tipo_arma").val();
                var marca       = $("#marca").val();
                var calibre     = $("#calibre").val();
                var modelo      = $("#modelo").val();
                var empresa     = $("#empresa").val();
                var pais_origen = $("#pais_origen").val();                
                
                $.ajax({ 
                    type: 'post',
                    url: '<?php echo base_url(); ?>mb_catalogos/consulta/0',
                    data: "tipo_arma="+tipo_arma+"&marca="+marca+"&calibre="+calibre+"&modelo="+modelo+"&empresa="+empresa+"&pais_origen="+pais_origen,
                    success: function(){
                        cargoConsulta();
                    }
                });                
            }
            
            function impresion(){                
                $.colorbox({href:"<?php echo base_url('mb_catalogos/seteoImpresion'); ?>", top: true, iframe: false, scrolling: false, innerWidth: 800, innerHeight: 200, title: "IMPRESION"});                
            } 

            function seteoImpresion(de_pagina, a_pagina){                
                $.ajax({
                    type: 'post',
                    url: "<?php echo base_url("mb_catalogos/armoImpresion"); ?>",
                    data: "de_pagina="+de_pagina+"&a_pagina="+a_pagina,
                    success: function(data){
                        if(data == "1"){
                            window.open ("<?php echo base_url("contenido_impresion"); ?>", "mywindow","toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=1,resizable=0");
                        }else{
                            jAlert(data);
                        }
                    }                  
                });
            }            
            
            function orderBy(param){            
                $.ajax({
                    type: 'post',
                    url: "<?php echo base_url("mb_catalogos/orderBy"); ?>",
                    data: "order="+param,
                    success: function(){
                        cargoConsulta();                       
                    }                  
                });           
            }      

            function cargoConsulta() {
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: "<?php echo base_url("mb_catalogos/consulta"); ?>",
                    success: function(data){
                        $("#datos_consulta").html(data[0]);
                        $("#paginado").html(data[1]);
                    }                  
                });            
            }
            
            function editarCatalogo(nro_catalogo) {
                $.ajax({
                    type: 'post',
                    url: "<?php echo base_url("mb_catalogos/editarCatalogo"); ?>",
                    data: "nro_catalogo="+nro_catalogo,
                    success: function(){
                        irAFrame('<?php echo base_url('modificar_catalogos'); ?>','O.C.I >> Modificar/Anular >> Catalogos');
                    }                  
                });            
            }
            
            function eliminarCatalogo(nro_catalogo) {
            
                 jConfirm('Estas seguro que quieres eliminar el catalogo - '+nro_catalogo, 'ELIMINAR CATALOGO DEL SISTEMA', function(r) {
                    if(r) {           
                        $.ajax({
                            type: 'post',
                            url: "<?php echo base_url("mb_catalogos/eliminarCatalogo"); ?>",
                            data: "nro_catalogo="+nro_catalogo,
                            success: function(data){
                                if(data == 1) {
                                    jAlert("El catalogo nro - "+nro_catalogo+" fue eliminado con exito del sistema", "ELIMINAR CATALOGO", function() { irAFrame('<?php echo base_url('mb_catalogos'); ?>','O.C.I >> Modificar/Anular >> Catalogos') } );
                                }else {

                                    jAlert("El catalogo nro - "+nro_catalogo+" no se puede elimianar del sistema, debido a que esta asociados a compras y fichas", "ELIMINAR CATALOGO");
                                }

                            }                  
                        });   
                    }
                });
            }
                  
        </script>
        
    </head>
    
    <body class="cuerpo">
        
        <table>
            
            <tr>
                <td><label> &emsp; Tipo arma       - </label> </td> <td> <input type="text" class="text" id="tipo_arma" /></td>
                <td><label> &emsp; Marca - </label> </td> <td>  <input type="text" class="text" id="marca" /></td>
            </tr>
            
            <tr>
                <td><label> &emsp; Calibre       - </label> </td> <td> <input type="text" class="text" id="calibre" /></td>
                <td><label> &emsp; Modelo - </label> </td> <td>  <input type="text" class="text" id="modelo" /></td>
            </tr>            

            <tr>
                <td><label> &emsp; Empresa       - </label> </td> <td> <input type="text" class="text" id="empresa" /></td>
                <td><label> &emsp; Pais origen - </label> </td> <td>  <input type="text" class="text" id="pais_origen" /></td>
            </tr>            
            
            
        </table>
        
        <br /> 
        
        &emsp; <button onclick="filtrar();"> Buscar </button> &emsp;&emsp; <button onclick="impresion();"> Imprimir </button>              
        
        <br /> 
        
        <hr />
        
        <div class="datagrid">
        
            <table>

                <thead style='text-align: center; cursor: pointer;'>
                    <tr>      
                        <th onclick="orderBy(0)"> Numero      </th>
                        <th onclick="orderBy(1)"> Tipo        </th>
                        <th onclick="orderBy(2)"> Marca       </th>
                        <th onclick="orderBy(3)"> Calibre     </th>
                        <th onclick="orderBy(4)"> Modelo      </th>
                        <th onclick="orderBy(5)"> Sistema     </th>
                        <th onclick="orderBy(6)"> Fab         </th>
                        <th onclick="orderBy(7)"> Empresa     </th>
                        <th onclick="orderBy(8)"> Origen      </th>
                        <th onclick="orderBy(9)"> Venc        </th>
                        <th> Editar    </th>
                        <th> Eliminar  </th>
                    </tr>
                </thead>

                <tbody id="datos_consulta"> </tbody>   

                <tfoot>
                    <tr> <td colspan="12"> <div id="paging"> <br /> </div> </td> </tr>
                </tfoot>
                
           </table>  
            
       </div>     
            
       <div id="paginado"> </div>     
        
    </body>    
    
</html>