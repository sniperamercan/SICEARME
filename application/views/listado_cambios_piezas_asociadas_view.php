<!DOCTYPE html>
<html lang="es">

    <head>
        
        <style>
            .datagrid table { border-collapse: collapse; text-align: left; width: 100%; } 
            .datagrid {font: normal 12px/150% Arial, Helvetica, sans-serif; background: #fff; overflow: auto; border: 1px solid #8C8C8C; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; }
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
                
                var nro_orden     = $("#nro_orden").val();
                var nro_pieza     = $("#nro_pieza").val();
                var nro_parte     = $("#nro_parte").val();
                var nombre_parte  = $("#nombre_parte").val();
                
                $.ajax({ 
                    type: 'post',
                    url: '<?php echo base_url(); ?>listado_cambios_piezas_asociadas/consulta/0',
                    data: "nro_orden="+nro_orden+"&nro_pieza="+nro_pieza+"&nro_parte="+nro_parte+"&nombre_parte="+nombre_parte,
                    success: function(){
                        cargoConsulta();
                    }
                });                
            }
            
            function impresion(){                
                $.colorbox({href:"<?php echo base_url('listado_cambios_piezas_asociadas/seteoImpresion'); ?>", iframe: false, scrolling: false, innerWidth: 800, innerHeight: 200, title: "IMPRESION"});                
            } 

            function seteoImpresion(de_pagina, a_pagina){                
                $.ajax({
                    type: 'post',
                    url: "<?php echo base_url("listado_cambios_piezas_asociadas/armoImpresion"); ?>",
                    data: "de_pagina="+de_pagina+"&a_pagina="+a_pagina,
                    success: function(data){
                        if(data == "1"){
                            window.open ("<?php echo base_url("contenido_impresion"); ?>", "mywindow","toolbar=0,scrollbars=1,location=0,status=1,menubar=1,resizable=0");
                        }else{
                            jAlert(data);
                        }
                    }                  
                });
            }            
            
            function orderBy(param){            
                $.ajax({
                    type: 'post',
                    url: "<?php echo base_url("listado_cambios_piezas_asociadas/orderBy"); ?>",
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
                    url: "<?php echo base_url("listado_cambios_piezas_asociadas/consulta"); ?>",
                    success: function(data){
                        $("#datos_consulta").html(data[0]);
                        //$("#paginado").html(data[1]);
                    }                  
                });            
            }
            
            function verObservaciones(nro_orden) {
                $.ajax({
                    type: "post",  
                    url: "<?php base_url(); ?>listado_cambios_piezas_asociadas/verObservaciones",
                    data: "nro_orden="+nro_orden,
                    success: function(data){
                        jAlert(data, "OBSERVACIONES DE LA ORDEN DE TRABAJO");
                  }
                });                
            }  
            
            function imprimir(nro_cambio) {
                $.ajax({
                    type: "post",  
                    url: "<?php base_url(); ?>listado_cambios_piezas_asociadas/imprimir",
                    data: "nro_cambio="+nro_cambio,
                    success: function(){
                        window.open ("<?php echo base_url("imprimir_cambios_piezas_asociadas"); ?>", "mywindow","toolbar=0,scrollbars=1,location=0,status=1,menubar=1,resizable=0");
                  }
                });                
            }              
              
        </script>
        
    </head>
    
    <body class="cuerpo">
        
        <table>
            
            <tr>
                <td><label> Nº orden </label> </td> <td>  <input type="text" class="text" id="nro_orden" /></td>
                <td><label> Nº pieza </label> </td> <td>  <input type="text" class="text" id="nro_pieza" /></td>
            </tr>
            
            <tr>
                <td><label> Nº parte      </label> </td> <td>  <input type="text" class="text" id="nro_parte" /></td>
                <td><label> Nombre parte   </label> </td> <td>  <input type="text" class="text" id="nombre_parte" /></td>
            </tr>   
            
        </table>
        
        <br /> 
        
        &nbsp; <button onclick="filtrar();"> Buscar </button> &nbsp;&nbsp; <button onclick="impresion();"> Imprimir </button>              
        
        <br /> 
        
        <hr />
        
        <div class="datagrid">
        
            <table>

                <thead style='text-align: center; cursor: pointer;'>
                    <tr>      
                        <th onclick="orderBy(0);"> Nº orden   </th>
                        <th> Nº serie  </th>
                        <th> Marca     </th>
                        <th> Calibre   </th>
                        <th> Modelo    </th>
                        <th onclick="orderBy(1);"> Pieza ant.    </th>
                        <th onclick="orderBy(2);"> Pieza nueva   </th>
                        <th onclick="orderBy(3);"> Nº parte      </th>
                        <th onclick="orderBy(4);"> Nombre parte  </th>
                        <th> Obs.     </th>
                        <th> Ver      </th>
                        <th> Imprimir </th>
                    </tr>
                </thead>

                <tbody id="datos_consulta"> </tbody>   

                <tfoot>
                    <tr> <td colspan="12"> <div id="paging"> <br /> </div> </td> </tr>
                </tfoot>
                
           </table>  
            
       </div>     
        
    </body>    
    
</html>