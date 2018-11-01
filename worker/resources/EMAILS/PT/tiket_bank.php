<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>DUMBU - Boleto bancário</title>
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
                        <p>Parabéns <strong><?php echo urldecode($_GET["username"]);?></strong>,</p>
                        <p>Agora você só precisa finalizar o pagamento do seu boleto e seguir as instruções abaixo para que sua conta comece a receber o serviço contratado. Siga com atenção os seguintes passos:</p>
                        <div style="padding-left:30px; padding-right:30px">
                            <p>1) Baixe o boleto bancário clicando <a href="<?php echo urldecode($_GET["ticket_link"]); ?>"> AQUI</a> e efetue o pagamento antes do vencimento.</p>
                            <p>2) Você já pode acessar o sistema Dumbu para configurar sua conta e iniciar sua captação de seguidores. Após 2 dias, caso não haja a confirmação do pagamento, sua conta será pausada até que o pagamento seja reconhecido. </p>
                        </div>
                        <p><br>IMPORTANTE: O link para acessar o sistema só pode ser usado <b>UMA VEZ</b>. Para isso, clique <a  href="<?php echo urldecode($_GET["access_link"]);?>" target="_blank"> AQUI</a>.</p>
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



       