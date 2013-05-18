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
                
                var modelo       = $("#modelo").val();
                var nro_serie    = $("#nro_serie").val();
                var marca        = $("#marca").val();
                var calibre      = $("#calibre").val();
                
                $.ajax({ 
                    type: 'post',
                    url: '<?php echo base_url(); ?>mb_inventario_reserva/consulta/0',
                    data: "modelo="+modelo+"&nro_serie="+nro_serie+"&marca="+marca+"&calibre="+calibre,
                    success: function(){
                        cargoConsulta();
                    }
                });                
            }
            
            function impresion(){                
                $.colorbox({href:"<?php echo base_url('mb_inventario_reserva/seteoImpresion'); ?>", iframe: false, scrolling: false, innerWidth: 800, innerHeight: 200, title: "IMPRESION"});                
            } 

            function seteoImpresion(de_pagina, a_pagina){                
                $.ajax({
                    type: 'post',
                    url: "<?php echo base_url("mb_inventario_reserva/armoImpresion"); ?>",
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
                    url: "<?php echo base_url("mb_inventario_reserva/orderBy"); ?>",
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
                    url: "<?php echo base_url("mb_inventario_reserva/consulta"); ?>",
                    success: function(data){
                        $("#datos_consulta").html(data[0]);
                        //$("#paginado").html(data[1]);
                    }                  
                });            
            }
              
            function editarInventario(nro_serie, marca, calibre, modelo) {
                $.ajax({
                    type: 'post',
                    url: "<?php echo base_url("mb_inventario_reserva/editarInventario"); ?>",
                    data: "nro_serie="+nro_serie+"&marca="+marca+"&calibre="+calibre+"&modelo="+modelo,
                    success: function(data){
                        if(data == 1) {
                            irAFrame('<?php echo base_url('alta_inventario_reserva'); ?>','Reserva >> Alta >> Inventario reserva');
                        }else {
                            jAlert("ERROR: El inventario no se pudo modificar", "EDITAR INVENTARIO RESERVA");
                        }
                    }                  
                });            
            }
              
        </script>
        
    </head>
    
    <body class="cuerpo">
        
        <table>
            
            <tr>
                <td><label> &emsp; Nro serie  - </label> </td> <td>  <input type="text" class="text" id="nro_serie" /></td>
                <td><label> &emsp; Modelo     - </label> </td> <td>  <input type="text" class="text" id="modelo" /></td>
            </tr>
            
            <tr>
                <td><label> &emsp; Marca      - </label> </td> <td>  <input type="text" class="text" id="marca" /></td>
                <td><label> &emsp; Calibre    - </label> </td> <td>  <input type="text" class="text" id="calibre" /></td>
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
                        <th onclick="orderBy(1)"> Nro serie       </th>
                        <th onclick="orderBy(2)"> Marca           </th>
                        <th onclick="orderBy(3)"> Calibre         </th>
                        <th onclick="orderBy(4)"> Modelo          </th>
                        <th onclick="orderBy(5)"> Deposito        </th>
                        <th> Editar </th>
                    </tr>
                </thead>

                <tbody id="datos_consulta"> </tbody>   

                <tfoot>
                    <tr> <td colspan="6"> <div id="paging"> <br /> </div> </td> </tr>
                </tfoot>
                
           </table>  
            
       </div>     
      
    </body>    
    
</html>