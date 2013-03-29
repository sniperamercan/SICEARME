<!DOCTYPE html>
<html lang="es">
    
    <head>
        
        <style>
            
            .datagrid table { border-collapse: collapse; text-align: left; width: 100%; } 
            .datagrid {font: normal 12px/150% Arial, Helvetica, sans-serif; background: #fff; overflow: hidden; border: 1px solid #8C8C8C; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; }
            .datagrid table td, .datagrid table th { padding: 3px 10px; }
            
            .datagrid table thead th {background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #8C8C8C), color-stop(1, #7D7D7D) );background:-moz-linear-gradient( center top, #8C8C8C 5%, #7D7D7D 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#8C8C8C', endColorstr='#7D7D7D');background-color:#8C8C8C; color:#FFFFFF; font-size: 15px; font-weight: bold; border-left: 1px solid #A3A3A3; } 
            .datagrid table thead th:first-child { border: none; }
            
            .datagrid table tbody td { color: #2E2E2E; border-left: 1px solid #DBDBDB; border-bottom: 1px solid #DBDBDB; font-size: 12px;font-weight: normal; }
            .datagrid table tbody .alt td { background: #EBEBEB; color: #7D7D7D; }
            .datagrid table tbody td:first-child { border-left: none; }
            .datagrid table tbody tr:last-child td { border-bottom: none; }
            
            .datagrid table tfoot td div { border-top: 1px solid #8C8C8C;background: #EBEBEB;} 
            .datagrid table tfoot td { padding: 0; font-size: 12px } .datagrid table tfoot td div{ padding: 2px; }
            .datagrid table tfoot td ul { margin: 0; padding:0; list-style: none; text-align: right; }
            .datagrid table tfoot  li { display: inline; }
            .datagrid table tfoot li a { text-decoration: none; display: inline-block;  padding: 2px 8px; margin: 1px;color: #F5F5F5;border: 1px solid #8C8C8C;-webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #8C8C8C), color-stop(1, #7D7D7D) );background:-moz-linear-gradient( center top, #8C8C8C 5%, #7D7D7D 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#8C8C8C', endColorstr='#7D7D7D');background-color:#8C8C8C; }
            .datagrid table tfoot ul.active, .datagrid table tfoot ul a:hover { text-decoration: none;border-color: #7D7D7D; color: #F5F5F5; background: none; background-color:#8C8C8C;}
        </style>         
        
        <script type="text/javascript" src='<?php echo base_url('js/charts/jquery.jqplot.min.js'); ?>'></script>
        <script type="text/javascript" src="js/charts/plugins/jqplot.dateAxisRenderer.min.js"></script>
        <script type="text/javascript" src="js/charts/plugins/jqplot.canvasTextRenderer.min.js"></script>
        <script type="text/javascript" src="js/charts/plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
        <script type="text/javascript" src="js/charts/plugins/jqplot.categoryAxisRenderer.min.js"></script>
        <script type="text/javascript" src="js/charts/plugins/jqplot.barRenderer.min.js"></script>    
        <link rel="stylesheet" type="text/css" href="js/charts/jquery.jqplot.css" />        

        <script type="text/javascript">
            
            $(document).ready(function() {

                $("input:submit").button();
                $("button").button(); 
                $("input:button").button();
/*
                var grafica1 = [['',0]]; 
                var grafica2 = [['',0]]; 
                var grafica3 = [['',0]]; 
                var i;
                
                $.ajax({
                    type: "post",                    
                    url: "<?php base_url(); ?>resumen/armoGraficas1",
                    dataType: "json",
                    success: function(data){
                        
                        for(i=0; i<data.length; i=i+2) {
                            grafica1.push([data[i], data[i+1]]);
                        }
                        
                        //PRIMER GRAFICA
                        var plot1 = $.jqplot('chart1', [grafica1], {
                            title: 'PRODUCTOS CON MAS STOCK EN DEPOSITO',
                            series:[{renderer:$.jqplot.BarRenderer}],
                            axesDefaults: {
                                tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
                                tickOptions: {
                                angle: -30,
                                fontSize: '10pt'
                                }
                            },
                            axes: {
                            xaxis: {
                                renderer: $.jqplot.CategoryAxisRenderer
                            }
                            }
                        });
                    }
                });
                
                $.ajax({
                    type: "post",                    
                    url: "<?php base_url(); ?>resumen/armoGraficas2",
                    dataType: "json",
                    success: function(data){
                        
                        for(i=0; i<data.length; i=i+2) {
                            grafica2.push([data[i], data[i+1]]);
                        }
                        
                        //SEGUNDA GRAFICA
                        var plot2 = $.jqplot('chart2', [grafica2], {
                            title: 'PRODUCTOS MAS ENTREGADOS EMPRESAS',
                            series:[{renderer:$.jqplot.BarRenderer}],
                            axesDefaults: {
                                tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
                                tickOptions: {
                                angle: -30,
                                fontSize: '10pt'
                                }
                            },
                            axes: {
                            xaxis: {
                                renderer: $.jqplot.CategoryAxisRenderer
                            }
                            }
                        });
                    }
                });

                $.ajax({
                    type: "post",                    
                    url: "<?php base_url(); ?>resumen/armoGraficas3",
                    dataType: "json",
                    success: function(data){
                        
                        for(i=0; i<data.length; i=i+2) {
                            grafica3.push([data[i], data[i+1]]);
                        }
                        
                        //TERCER GRAFICA
                        var plot3 = $.jqplot('chart3', [grafica3], {
                            title: 'PRODUCTOS MAS ENTREGADOS UNIDADES',
                            series:[{renderer:$.jqplot.BarRenderer}],
                            axesDefaults: {
                                tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
                                tickOptions: {
                                angle: -30,
                                fontSize: '10pt'
                                }
                            },
                            axes: {
                            xaxis: {
                                renderer: $.jqplot.CategoryAxisRenderer
                            }
                            }
                        });
                    }
                });*/
                
            });	

        </script>
        
    </head>

    <body>
        <section>
            <article class="resumen">
                <div id="seccion_mejoras">
                    <?php echo $descripcion_version; ?>
                </div>  
            <article>   
        </section>
        
        <br /><br />
        
        <section>
            <article class="resumen">
                <p style="font-size: 17px; color: #1C1C1C; text-decoration: underline; font-weight: bold; font-family: monospace;"> GRAFICAS CON DATOS DEL SISTEMA </p>
                <br />
                
                <div id="chart1" style="height:300px; width:500px;"></div>
                <div id="chart2" style="height:300px; width:500px;"></div>
                <div id="chart3" style="height:300px; width:500px;"></div>
            </article>
        </section>     
    </body>
	
</html>