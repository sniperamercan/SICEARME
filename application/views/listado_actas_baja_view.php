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
                
                var nro_acta = $("#nro_acta").val();
                var estado   = $("#estado").val();
                var fecha1   = $("#fecha1").val();
                var fecha2   = $("#fecha2").val();                
                
                $.ajax({ 
                    type: 'post',
                    url: '<?php echo base_url(); ?>listado_actas_baja/consulta/0',
                    data: "nro_acta="+nro_acta+"&estado="+estado+"&fecha1="+fecha1+"&fecha2="+fecha2,
                    success: function(){
                        cargoConsulta();
                    }
                });                
            }
            
            function impresion(){                
                $.colorbox({href:"<?php echo base_url('listado_actas_baja/seteoImpresion'); ?>", top: true, iframe: false, scrolling: false, innerWidth: 800, innerHeight: 200, title: "IMPRESION"});                
            } 

            function seteoImpresion(de_pagina, a_pagina){                
                $.ajax({
                    type: 'post',
                    url: "<?php echo base_url("listado_actas_baja/armoImpresion"); ?>",
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
                    url: "<?php echo base_url("listado_actas_baja/orderBy"); ?>",
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
                    url: "<?php echo base_url("listado_actas_baja/consulta"); ?>",
                    success: function(data){
                        $("#datos_consulta").html(data[0]);
                        $("#paginado").html(data[1]);
                    }                  
                });            
            }
            
            function verObservaciones(nro_acta) {
                $.ajax({
                    type: "post",  
                    url: "<?php base_url(); ?>listado_actas_baja/verObservaciones",
                    data: "nro_acta="+nro_acta,
                    success: function(data){
                        jAlert(data, "OBSERVACIONES DEL ACTA");
                  }
                });                
            }    
            
            function verEntrega(nro_acta) {
                $.ajax({
                    type: "post",  
                    url: "<?php base_url(); ?>listado_actas_baja/verEntregas",
                    data: "nro_acta="+nro_acta,
                    success: function(data){
                        jAlert(data, "ARMAMENTO Y ACCESORIOS ENTREGADOS");
                  }
                });                
            }

            function imprimirRecibo(nro_acta) {
                $.ajax({
                    type: "post",  
                    url: "<?php base_url(); ?>listado_actas_baja/imprimirRecibo",
                    data: "nro_acta="+nro_acta,
                    success: function(){
                        window.open ("<?php echo base_url("imprimir_acta_baja"); ?>", "mywindow","toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=1,resizable=0");
                  }
                });                
            }
            
        </script>
        
    </head>
    
    <body class="cuerpo">
        
        <table>
            
            <tr>
                <td><label> Nº acta </label> </td> <td>  <input type="text" class="text" id="nro_acta" /></td>
                <td><label> Estado   </label> </td> <td>  <input type="text" class="text" id="estado" /></td>
            </tr>
         
            <tr>
                <td><label> Fecha desde </label> </td> <td>  <input type="text" class="text" id="fecha1" /></td>
                <td><label> Fecha hasta </label> </td> <td>  <input type="text" class="text" id="fecha2" /></td>
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
                        <th onclick="orderBy(0)"> Nº acta         </th>
                        <th onclick="orderBy(1)"> Fecha           </th>
                        <th onclick="orderBy(2)"> Unidad entrega  </th>
                        <th onclick="orderBy(3)"> Unidad recibe   </th>
                        <th onclick="orderBy(4)"> Rep. SMA        </th>
                        <th onclick="orderBy(5)"> Rep. Unidad     </th>
                        <th onclick="orderBy(6)"> Supervisor      </th>
                        <th onclick="orderBy(7)"> Estado          </th>
                        <th> Obs.          </th>
                        <th> Devoluciones  </th>
                        <th> Ver           </th>
                        <th> Imprimir      </th>
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