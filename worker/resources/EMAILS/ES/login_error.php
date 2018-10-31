<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>DUMBU - Error en el Login</title>
        <style>
            p{font-size:16px; text-align:justify; margin-top:5px}
        </style>
    </head>
    <body >
        <div style="text-align: center;">
            <div style="max-width: 580px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
                <div style="padding:24px 16px; border-top-left-radius:15px; border-top-right-radius:15px; text-align:center; background-color:#FF5733">
                    <img src="https://dumbu.pro/follows/src/assets/images/logo.png"><br>
                    <h1 S>Error en el Login, credenciales incorrectas!</h1>
                </div>
                <div style="padding:60px; background-color:#F5F8F6">
                    <div>
                        <p>Hola <strong><?php echo urldecode($_GET["username"]);?></strong>,</p>
                        <p>Nuestra herramienta ha encontrado problemas para realizar el login con las credenciales de Instagram 
                            autorizadas por usted en nuestro sistema. En caso de usted haber cambiado su usuario o contraseña en Instagram, por
                            favor, haga login en nuestro <a href="<?php echo urldecode($_GET["site"]);?>" target="_blank">sitio</a>
                            e sus credenciales serán actualizadas automáticamente.
                        </p>
                        <p>Su usuario en nuestro sistema es: <strong><?php echo $_GET["instaname"]; ?></strong></p>
                        <p>IMPORTANTE: sus crendenciales deben ser las mismas tanto en DUMBU cuanto en Instagram.</p>
                        <p>Nuestra herramienta solo conseguirá captar nuevos seguidores si las credenciales estuvieran correctas. Por eso,
                            siempre que cambiar su nombre de usuario o contraseã en Instagram, haga login en Dumbu con las credenciales actualizadas.
                        </p>
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
                    <img src="https://dumbu.pro/follows/src/assets/images/logo-footer.png"><br>
                    <h5>DUMBU - <?php echo date("Y",time());?> - TODOS LOS DIRECHOS RESERVADOS</h5>
                </div>
            </div>
        </div>
    </body>
</html>
