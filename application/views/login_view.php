<!DOCTYPE html>
<html lang="es">
    
    <head>
    
        <title>SICEARME</title>	
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <link rel="shortcut icon" href=<?php echo base_url('css/template/favicon.png');?> />	
        <script type="text/javascript" src='<?php echo base_url('js/jquery-ui/js/jquery-1.8.0.min.js');?>' ></script>	
        <script type="text/javascript" src='<?php echo base_url('js/jquery-ui/js/jquery-ui-1.8.23.custom.min.js');?>' ></script>	
        <link rel="stylesheet" href='<?php echo base_url('css/estilo.css'); ?>' type="text/css" />
        <link rel="stylesheet" href='<?php echo base_url('js/jquery-ui/css/black-tie/jquery-ui-1.8.23.custom.css'); ?>' type="text/css" />
        <script type="text/javascript" src='<?php echo base_url('js/jquery.alerts-1.1/jquery.alerts.js'); ?>'></script>
        <link media="screen" rel="stylesheet" href='<?php echo base_url('js/jquery.alerts-1.1/jquery.alerts.css'); ?>' />

        <script>

        $(document).ready(function() {
            //click derecho off
            $(document).bind("contextmenu",function(e){
                return false;
            });

            $("input:submit").button();
            $("button").button(); 
            $("input:button").button();    

            $('input:text').attr('maxLength','100'); //seteo el maximo de caracteres a 60
            $('textarea').attr('maxLength','1000'); //seteo el maximo de caracteres a 1000

        });

        </script> 
        
        <script type="text/javascript">

            $(document).ready(function() {

                $("#usuario").focus();
                
                //funcion para hacer algo con el evento del boton enter
                $(window).keypress(function(e) {
                    if(e.keyCode == 13) {
                        validarUsuario();
                    }
                });
                //fin funcion para evento enter
			    
            });	

            function validarUsuario() {
                
                var usuario = $("#usuario").val();
                var clave   = $("#clave").val();
                
                $.ajax({
                    type: 'post',  
                    url: "<?php base_url(); ?>login/validar",
                    data: "usuario="+usuario+"&clave="+clave,
                    success: function(data){
                        if(data == "1"){
                            window.location.href='<?php echo base_url('panelprincipal'); ?>'
                        }else{
                            jAlert(data, "Error");
                        }   
                    }
                });
                
            }

        </script>
        
    </head>

    <body class="cuerpo" style="background-color: #E6E6E6;">

        		
    <header style="margin-top: 100px;">        
        <img src="<?php echo base_url(); ?>/images/menu.png" style="margin-left: 150px;" />
    </header>
    <br /><br />

    <article style="margin-left: 150px;">

        <h1> Acceso al sistema </h1>      
        
        <fieldset>	

            <dl>
            <dt><label for="usuario"> Usuario </label></dt>
            <dd><input id="usuario" type="text" class="text" /></dd>
            </dl>

            <dl>
            <dt><label for="clave"> Clave </label></dt>
            <dd><input id="clave" type="password" class="text" /></dd>
            </dl>

        </fieldset>	

        <fieldset class="action">	
            <button onclick="validarUsuario();"> Ingresar </button>
        </fieldset>
    </article>    
   
    <footer>
        <?php echo $info; ?>
        <br />
        <?php echo $version; ?>
    </footer>
            
       
        
    </body>
	
</html>
