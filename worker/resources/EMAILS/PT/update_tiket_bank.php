<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>DUMBU - Boleto bancário novo</title>
        <style>
            p{font-size:16px; text-align:justify; margin-top:5px}
        </style>
    </head>
    <body >
        <div style="text-align: center;">
            <div style="max-width: 580px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
                <div style="padding:24px 16px; border-top-left-radius:15px; border-top-right-radius:15px; text-align:center; background-color:#95DE24; ">
                    <img src="https://dumbu.pro/follows/src/assets/images/logo.png"><br>
                    <h1 S>Boleto bancário gerado com sucesso!</h1>
                </div>
                <div style="padding:60px; background-color:#F5F8F6">
                    <div>
                        <p>Olá <strong><?php echo urldecode($_GET["username"]);?></strong>,</p>
                        <p>Seu novo boleto bancário gerado com sucesso. Acesse <a  href="<?php echo $_GET["ticket_link"];?>" target="_blank"> AQUÍ</a> para descarregue-o.</p>
                        <p>Agora você só precisa realizar o pagamento do seu boleto.</p>
                        <p>Se tiver dúvidas, escreva para 
                            <span style="color:blue"><?php echo urldecode($_GET["atendent_email"]);?></span>
                            e vamos a ajuda-lo! :)
                        </p>
                        <p>
                            <br><br><i>Att. Equipe Dumbu</i><br><br>
                        </p>                        
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