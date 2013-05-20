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
                
                var nro_pieza    = $("#nro_pieza").val();      
                var nro_catalogo = $("#nro_catalogo").val();      
                var nro_parte    = $("#nro_parte").val();      
                var nombre_parte = $("#nombre_parte").val();      
                
                $.ajax({ 
                    type: 'post',
                    url: '<?php echo base_url(); ?>listado_repuestos_nro_pieza/consulta/0',
                    data: "nro_pieza="+nro_pieza+"&nro_catalogo="+nro_catalogo+"&nro_parte="+nro_parte+"&nombre_parte="+nombre_parte,
                    success: function(){
                        cargoConsulta();
                    }
                });                
            }      
            
            function orderBy(param){            
                $.ajax({
                    type: 'post',
                    url: "<?php echo base_url("listado_repuestos_nro_pieza/orderBy"); ?>",
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
                    url: "<?php echo base_url("listado_repuestos_nro_pieza/consulta"); ?>",
                    success: function(data){
                        $("#datos_consulta").html(data[0]);
                        $("#paginado").html(data[1]);
                    }                  
                });            
            }
            
            function imprimir(nro_pieza, nro_parte, nombre_parte, nro_catalogo) {
                $.ajax({
                    type: 'post',
                    url: "<?php echo base_url("listado_repuestos_nro_pieza/imprimir"); ?>",
                    data: "nro_pieza="+nro_pieza+"&nro_parte="+nro_parte+"&nombre_parte="+nombre_parte+"&nro_catalogo="+nro_catalogo,
                    success: function(){
                        window.open ("<?php echo base_url("imprimir_stock_almacen_nro_serie"); ?>", "mywindow","toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=1,resizable=0");
                    }                  
                });            
            }
                 
        </script>
        
    </head>
    
    <body class="cuerpo">
        
        <table>
            
            <tr>
                <td><label> &emsp; Nro pieza       - </label> </td> <td> <input type="text" class="text" id="nro_pieza" /></td>
                <td><label> &emsp; Nro catalogo    - </label> </td> <td> <input type="text" class="text" id="nro_catalogo" /></td>
            </tr> 
            
            <tr>
                <td><label> &emsp; Nro parte       - </label> </td> <td> <input type="text" class="text" id="nro_parte" /></td>
                <td><label> &emsp; Nombre parte    - </label> </td> <td> <input type="text" class="text" id="nombre_parte" /></td>
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
                        <th onclick="orderBy(0)"> N Pie  </th>
                        <th onclick="orderBy(1)"> N Par  </th>
                        <th onclick="orderBy(2)"> Nom    </th>
                        <th onclick="orderBy(3)"> Cat    </th>
                        <th> Tip  </th>
                        <th> Mar  </th>
                        <th> Cal  </th>
                        <th> Mod  </th>
                        <th> Imp  </th>
                    </tr>
                </thead>

                <tbody id="datos_consulta"> </tbody>   

                <tfoot>
                    <tr> <td colspan="9"> <div id="paging"> <br /> </div> </td> </tr>
                </tfoot>
                
           </table>  
            
       </div>     
        
    </body>    
    
</html>