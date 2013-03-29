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
                $("#fecha1").datepicker({ dateFormat: "yy-mm-dd", monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"], dayNames: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"], dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"], changeYear: true, changeMonth: true, dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"], monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"] } );
                $("#fecha2").datepicker({ dateFormat: "yy-mm-dd", monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"], dayNames: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"], dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"], changeYear: true, changeMonth: true, dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"], monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"] } );
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
                cargoConsulta();
            });	     
     
            function filtrar(){
                
                var nro_compra    = $("#nro_compra").val();
                var modalidad     = $("#modalidad").val();
                var empresa       = $("#empresa").val();
                var pais_empresa  = $("#pais_empresa").val();
                var fecha1        = $("#fecha1").val();
                var fecha2        = $("#fecha2").val();                
                
                $.ajax({ 
                    type: 'post',
                    url: '<?php echo base_url(); ?>mb_compras/consulta/0',
                    data: "nro_compra="+nro_compra+"&modalidad="+modalidad+"&empresa="+empresa+"&pais_empresa="+pais_empresa+"&fecha1="+fecha1+"&fecha2="+fecha2,
                    success: function(){
                        cargoConsulta();
                    }
                });                
            }
            
            function impresion(){                
                $.colorbox({href:"<?php echo base_url('mb_compras/seteoImpresion'); ?>", top: true, iframe: false, scrolling: false, innerWidth: 800, innerHeight: 200, title: "IMPRESION"});                
            } 

            function seteoImpresion(de_pagina, a_pagina){                
                $.ajax({
                    type: 'post',
                    url: "<?php echo base_url("mb_compras/armoImpresion"); ?>",
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
                    url: "<?php echo base_url("mb_compras/orderBy"); ?>",
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
                    url: "<?php echo base_url("mb_compras/consulta"); ?>",
                    success: function(data){
                        $("#datos_consulta").html(data[0]);
                        $("#paginado").html(data[1]);
                    }                  
                });            
            }
            
            function verCatalogos(nro_interno) {
                $.ajax({
                    type: "post",  
                    url: "<?php base_url(); ?>mb_compras/verCatalogos",
                    data: "nro_interno="+nro_interno,
                    success: function(data){
                        jAlert(data, "CATALOGOS ASOCIADOS A LA COMPRA");
                  }
                });                
            }            
              
            function editarCompra(nro_compra) {
                $.ajax({
                    type: 'post',
                    url: "<?php echo base_url("mb_compras/editarCompra"); ?>",
                    data: "nro_compra="+nro_compra,
                    success: function(){
                        irAFrame('<?php echo base_url('modificar_compras'); ?>','O.C.I >> Modificar/Anular >> Compras');
                    }                  
                });            
            }
            
            function eliminarCompra(nro_compra) {
            
                 jConfirm('Estas seguro que quieres eliminar la compra - '+nro_compra, 'ELIMINAR COMPRA DEL SISTEMA', function(r) {
                    if(r) {           
                        $.ajax({
                            type: 'post',
                            url: "<?php echo base_url("mb_compras/eliminarCompra"); ?>",
                            data: "nro_compra="+nro_compra,
                            success: function(data){
                                if(data == 1) {
                                    jAlert("La compra nro - "+nro_compra+" fue eliminado con exito del sistema", "ELIMINAR COMPRA", function() { irAFrame('<?php echo base_url('mb_compras'); ?>','O.C.I >> Modificar/Anular >> Compras') } );
                                }else {

                                    jAlert("La compra nro - "+nro_compra+" no se puede elimianar del sistema, debido a que esta asociados a una ficha", "ELIMINAR COMPRA");
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
                <td><label> &emsp; Nro compra - </label> </td> <td>  <input type="text" class="text" id="nro_compra" /></td>
                <td><label> &emsp; Modalidad  - </label> </td> <td>  <input type="text" class="text" id="modalidad" /></td>
            </tr>
            
            <tr>
                <td><label> &emsp; Empresa      - </label> </td> <td>  <input type="text" class="text" id="empresa" /></td>
                <td><label> &emsp; Pais empresa - </label> </td> <td>  <input type="text" class="text" id="pais_empresa" /></td>
            </tr>            

            <tr>
                <td><label> &emsp; Fecha 1 - </label> </td> <td>  <input type="text" class="text" id="fecha1" /></td>
                <td><label> &emsp; Fecha 2 - </label> </td> <td>  <input type="text" class="text" id="fecha2" /></td>
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
                        <th onclick="orderBy(0)"> Numero          </th>
                        <th onclick="orderBy(1)"> N. Compra       </th>
                        <th onclick="orderBy(2)"> Fecha           </th>
                        <th onclick="orderBy(3)"> Empresa         </th>
                        <th onclick="orderBy(4)"> Pais            </th>
                        <th onclick="orderBy(5)"> Descr           </th>
                        <th onclick="orderBy(6)"> Mod             </th>
                        <th onclick="orderBy(7)"> Cant armas      </th>
                        <th onclick="orderBy(8)"> Precio total    </th>
                        <th> Ver cat       </th>
                        <th> Editar        </th>
                        <th> Eliminar      </th>
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