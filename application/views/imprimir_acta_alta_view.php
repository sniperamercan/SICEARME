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
        
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <link rel="shortcut icon" href=<?php echo base_url('css/template/favicon.png');?> />	
        <script type="text/javascript" src='<?php echo base_url('js/jquery-ui/js/jquery-1.8.0.min.js');?>' ></script>	
        <script type="text/javascript" src='<?php echo base_url('js/jquery-ui/js/jquery-ui-1.8.23.custom.min.js');?>' ></script>	
        <link rel="stylesheet" href='<?php echo base_url('css/estilo.css'); ?>' type="text/css" />
        <link rel="stylesheet" href='<?php echo base_url('js/jquery-ui/css/black-tie/jquery-ui-1.8.23.custom.css'); ?>' type="text/css" />
        <script type="text/javascript" src='<?php echo base_url('js/print/jquery.printElement.js'); ?>'></script>
        
        <script type="text/javascript">

            $(document).ready(function() {
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button();
            });

            function imprimir(){
                $('#contenido').printElement();
            }
        
        </script>
        
    </head>
    
    <body class="cuerpo">
       
       <div id="contenido">
           
            <button onclick="imprimir();"> Imprimir </button>

            <hr /><br /><br />           
           
            <div class="datagrid">

                 <table>

                     <thead style='text-align: center; cursor: pointer;'>
                         <tr>      
                             <th> Nº acta </th>
                             <th> Fecha    </th>
                         </tr>
                     </thead>

                     <tbody style="text-align: center"> 
                         <tr>      
                             <td> <?php echo $nro_acta ?> </td>
                             <td> <?php echo $fecha ?>    </td>
                         </tr>                
                     </tbody>   

                </table>  

            </div>     

            <br /><br />  

            <div class="datagrid">

                 <table>

                     <thead style='text-align: center; cursor: pointer;'>
                         <tr>      
                             <th> Unidad entrega  </th>
                             <th> Unidad recibe   </th>
                         </tr>
                     </thead>

                     <tbody style="text-align: center"> 
                         <tr>      
                             <td> S.M.A </td>
                             <td> <?php echo $unidad_recibe ?>    </td>
                         </tr>                
                     </tbody>   

                </table> 

            </div> 

            <br /><br />  

            <div class="datagrid">

                 <table>

                     <thead style='text-align: center; cursor: pointer;'>
                         <tr>      
                             <th> Observaciones </th>
                         </tr>
                     </thead>

                     <tbody> 
                         <tr>      
                             <td> <?php echo $observaciones ?> </td>
                         </tr>                
                     </tbody>   

                </table>  

            </div>            

            <hr />

            <p class="subtituloform"> Datos del armamento entregado </p>

            <div class="datagrid">

                 <table>

                     <thead style='text-align: center; cursor: pointer;'>
                         <tr>      
                             <th> Nº serie </th>
                             <th> Marca     </th>
                             <th> Calibre   </th>
                             <th> Modelo    </th>
                         </tr>
                     </thead>

                     <tbody> <?php echo $entrega_fichas; ?> </tbody>   

                </table>  

            </div>    

            <p class="subtituloform"> Datos de accesorios entregado </p>

            <div class="datagrid">

                 <table>

                     <thead style='text-align: center; cursor: pointer;'>
                         <tr>      
                             <th> Nº serie      </th>
                             <th> Marca         </th>
                             <th> Calibre       </th>
                             <th> Modelo        </th>
                             <th> Nº accesorio  </th>
                         </tr>
                     </thead>

                     <tbody> <?php echo $entrega_accesorios; ?> </tbody>

                </table>  

            </div>  

            <br /> <hr /> <br />

            <div class="datagrid">

                 <table>

                     <thead style='text-align: center; cursor: pointer;'>
                         <tr>      
                             <th> Representante Unidad </th>
                             <th> Representante SMA   </th>
                             <th> Supervisor   </th>
                         </tr>
                     </thead>

                     <tbody> 
                         <tr>      
                             <td> <?php echo $representante_unidad ?>    </td>
                             <td> <?php echo $representante_sma ?>    </td>
                             <td> <?php echo $supervision ?>    </td>
                         </tr>

                         <tr style="text-align: center">      
                             <td> Firma    </td>
                             <td> Firma    </td>
                             <td> Firma    </td>
                         </tr>
                     </tbody>   

                     <tfoot>
                         <tr> <td colspan="3"> <div id="paging"> <br /> </div> </td> </tr>
                     </tfoot>                  

                </table> 

            </div> 
        
        </div>    
    </body>    
    
</html>