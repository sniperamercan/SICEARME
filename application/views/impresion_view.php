<!DOCTYPE html>
<html lang="es">

    <head> 
    
        <script type="text/javascript">

            $(document).ready(function() {
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
            });
            
        </script>
    
    </head>

    <body class="cuerpo">

        <div>

            <h1> Seteo parametros impresion </h1>
            
            <fieldset>

                <dl>
                <dt><label for="de_pagina"> De pagina </label></dt>
                <dd> <input type="text" class="number" id="de_pagina" />  </dd>
                </dl> 

                <dl>
                <dt><label for="a_pagina"> A pagina </label></dt> 
                <dd> <input type="text" class="number" id="a_pagina" /> </dd>
                </dl>

            </fieldset>

            <fieldset class="action">
                <button onclick="seteoImpresion(document.getElementById('de_pagina').value, document.getElementById('a_pagina').value)"> Aceptar </button>
            </fieldset>

        </div>	

    </body>

</html>