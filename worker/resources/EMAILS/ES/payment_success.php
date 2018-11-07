<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>DUMBU - Registro satisfactorio</title>
        <style>
            p{font-size:16px; text-align:justify; margin-top:5px}
        </style>
    </head>
    <body >
        <div style="text-align: center;">
            <div style="max-width: 580px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
                <div style="padding:24px 16px; border-top-left-radius:15px; border-top-right-radius:15px; text-align:center; background-color:#1BB370; background-image:url('http://192.168.25.3/follows-worker/worker/resources/EMAILS/images/bk-success-puchase-texture.jpeg')">
                    <img src="https://dumbu.one/follows/src/assets/images/logo.png"><br>
                    <h1 S>Sea bienvenido!</h1>
                </div>
                <div style="padding:60px; background-color:#F5F8F6">
                    <div>
                        <p>Hola <strong><?php echo urldecode($_GET["username"]);?></strong>,</p>
                        <p>Su registro fue realizado satisfactoriamente, ahora ustd necesita 
                            acdeder su cuenta en nuestro <a href="<?php echo urldecode($_GET["site"]);?>" target="_blank">sitio</a>.
                            Para eso, utilize siempre su usuario + contraseña de Instagram en el campo de Login.
                        </p>
                        <p>Aqui usted puede encontar sugerencias de cómo configurar y administrar su cuenta para tener un buen desempeño.</p>
                        <p>Si necesita ayuda para configurar su cuenta, o si tuviera dudas, escriva para 
                            <span style="color:blue"><?php echo urldecode($_GET["atendent_email"]);?></span>
                             y vamos a ajudarlo! :)
                        </p>
                        <p>
                            <br><br><i>Att. Equipo Dumbu</i><br><br>
                        </p>
                    </div>        
                    <div style="text-align: center">        
                        <a href="<?php echo urldecode($_GET["site"]);?>" target="_blank">
                            <button style="font: bold 1.4em sans-serif; color:white; width:220px; height:40px; border-radius:20px;background-color:blue">IR PARA EL SITIO</button>
                        </a>
                    </div>        
                </div>
                <div style="padding:24px 16px; border-bottom-left-radius:15px; border-bottom-right-radius:15px; text-align:center; background-color:#7E7D7D;">
                    <img src="https://dumbu.one/follows/src/assets/images/logo-footer.png"><br>
                    <h5>DUMBU - <?php echo date("Y",time());?> - TODOS LOS DIRECHOS RESERVADOS</h5>
                </div>
            </div>
        </div>
    </body>
</html>


