<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>DUMBU - Cliente sin Perfiles de Referencia</title>
        <style>
            p{font-size:16px; text-align:justify; margin-top:5px}
        </style>
    </head>
    <body >
        <div style="text-align: center;">
            <div style="max-width: 580px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
                <div style="padding:24px 16px; border-top-left-radius:15px; border-top-right-radius:15px; text-align:center; background-color:#FF5733">
                    <img src="https://dumbu.pro/follows/src/assets/images/logo.png"><br>
                    <h1 S>Perfiles de Referencia no encontrados!</h1>
                </div>
                <div style="padding:60px; background-color:#F5F8F6">
                    <div>
                        <p>Hola <strong><?php echo urldecode($_GET["username"]);?></strong>,</p>
                        <p> Detectamos que usted no tiene Perfiles de Referencia, Geolocalización ni Hashtag activos en su cuenta. Dumbu no deposita seguidores en su cuenta. 
                            Los seguidores son captados diariamente a través de Perfiles, Geolocalización e/o Hashtag de interés para usted, posibilitando asi una mayor segmentación 
                            de sus seguidores. Haga login en nuestro <a href="<?php echo urldecode($_GET["site"]);?>" target="_blank">sitio</a>
                            y adicione.
                        </p>
                                                
                        <p><strong>Algunos consejos importantes para aumentar su desempenho son:</strong></p>
                        <div style="padding-left:30px;padding-right:30px">
                            <p>-Poste regularmente;</p>
                            <p>-No deje la cuenta privada pues los seguidores siguen por identificación com su página;</p>
                            <p>-Nunca escoja perfiles pequeños o privados com menos de 5 mil seguidores;</p>
                            <p>-Si nota que el desempeño no está muy bueno, experimente cambiar el perfil de referencia que tiene menos desenpeño, 
                                o sea, aquel perfil que tiene muchos seguidores pero pocos likes.</p>
                        </div>
                                                
                        <p>Su usuario el nuestro sistema es: <strong><?php echo $_GET["instaname"]; ?></strong></p>
                        
                        <p>Si necesita ayuda para configurar su cuenta, o s tuviera dudas, escriva para 
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
