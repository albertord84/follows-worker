<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>DUMBU - Confirmación de email</title>
        <style>
            p{font-size:16px; text-align:justify; margin-top:5px}
        </style>
    </head>
    <body >
        <div style="text-align: center;">
            <div style="max-width: 580px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
                <div style="padding:24px 16px; border-top-left-radius:15px; border-top-right-radius:15px; text-align:center; background-color:#CBD640;">
                    <img src="https://dumbu.pro/follows/src/assets/images/logo.png"><br>
                    <h1 S>Validación de autenticidad</h1>
                </div>
                <div style="padding:60px; background-color:#F5F8F6">
                    <div>
                        <p>Hola <strong><?php echo urldecode($_GET["username"]);?></strong>,</p>
                        <p>Usted acaba de hazer el primer paso para registrarse en nuestra herramenta.</p>
                        <p>Su nombre de usuario en nuestro sistema es: <strong><?php echo $_GET["instaname"]; ?></strong></p>
                        <p>Para concluir el proceso de validación de su email, por favor, utilize el seguinte código de 4 dígitos y continue con el registro:</p>
                        <h1><strong><?php echo $_GET["purchase_access_token"]; ?></strong></h1>
                        <p>
                            <br><br><i>Att. Equipo Dumbu</i><br><br>
                        </p>
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


