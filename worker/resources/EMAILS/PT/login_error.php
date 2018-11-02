<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>DUMBU - Erro em Login</title>
        <style>
            p{font-size:16px; text-align:justify; margin-top:5px}
        </style>
    </head>
    <body >
        <div style="text-align: center;">
            <div style="max-width: 580px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
                <div style="padding:24px 16px; border-top-left-radius:15px; border-top-right-radius:15px; text-align:center; background-color:#FF5733">
                    <img src="https://dumbu.pro/follows/src/assets/images/logo.png"><br>
                    <h1 S>Erro no Login, credenciais incorretas!</h1>
                </div>
                <div style="padding:60px; background-color:#F5F8F6">
                    <div>
                        <p>Olá <strong><?php echo urldecode($_GET["username"]);?></strong>,</p>
                        <p>A ferramenta teve problemas para fazer o login com as credencias de Instagram 
                            autorizadas por você no nosso sistema. Caso você mudou seu usuário ou senha no Instagram, por
                            favor, faça login no nosso <a href="<?php echo urldecode($_GET["site"]);?>" target="_blank">site</a>
                            e suas credencias ficarão atualizadas automáticamente.
                        </p>
                        <p>Seu usuário no nosso sistema é: <strong><?php echo $_GET["instaname"]; ?></strong></p>
                        <p>IMPORTANTE: suas crendenciais devem ser as mesmas tanto em DUMBU quanto no Instagram.</p>
                        <p>Nossa ferramenta só conseguirá te entregar os seguidores se as credenciais estiverem corretas. Por isso,
                            sempre que mudar seu nome de usuário ou senha no Instagram, logue na Dumbu com as credenciais atualizadas.
                        </p>
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
