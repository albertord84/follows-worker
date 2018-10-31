<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>DUMBU - Assinatura satisfatória</title>
        <style>
            p{font-size:16px; text-align:justify; margin-top:5px}
        </style>
    </head>
    <body >
        <div style="text-align: center;">
            <div style="max-width: 580px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
                <div style="padding:24px 16px; border-top-left-radius:15px; border-top-right-radius:15px; text-align:center; background-color:#1BB370; background-image:url('http://192.168.25.3/follows-worker/worker/resources/EMAILS/images/bk-success-puchase-texture.jpeg')">
                    <img src="https://dumbu.pro/follows/src/assets/images/logo.png"><br>
                    <h1 S>Seja bem-vindo!</h1>
                </div>
                <div style="padding:60px; background-color:#F5F8F6">
                    <div>
                        <p>Olá <strong><?php echo urldecode($_GET["username"]);?></strong>,</p>
                        <p>Sua assinatura foi realizada com sucesso, agora você precissa 
                            acessar sua conta em nosso <a href="<?php echo urldecode($_GET["site"]);?>" target="_blank">site</a>.
                            Para isso, utilize sempre seu usuário + senha de Instagram no campo de Login.
                        </p>
                        <p>Aqui você pode encontar dicas de como configurar e administrar sua conta para ter um bom desempenho.</p>
                        <p>Se quiser ajuda para configurar sua conta, ou se tiver dúvidas, escreva para 
                            <span style="color:blue"><?php echo urldecode($_GET["atendent_email"]);?></span>
                             e vamos a ajuda-lo! :)
                        </p>
                        <p>
                            <br><br><i>Att. Equipe Dumbu</i><br><br>
                        </p>
                    </div>        
                    <div style="text-align: center">        
                        <a href="<?php echo urldecode($_GET["site"]);?>" target="_blank">
                            <button style="font: bold 1.4em sans-serif; color:white; width:220px; height:40px; border-radius:20px;background-color:blue">IR PARA O SITE</button>
                        </a>
                    </div>        
                </div>
                <div style="padding:24px 16px; border-bottom-left-radius:15px; border-bottom-right-radius:15px; text-align:center; background-color:#7E7D7D;">
                    <img src="https://dumbu.pro/follows/src/assets/images/logo-footer.png"><br>
                    <h5>DUMBU - <?php echo date("Y",time());?> - TODOS OS DIREITOS RESERVADOS</h5>
                </div>
            </div>
        </div>
    </body>
</html>


