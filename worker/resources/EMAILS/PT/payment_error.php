<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>DUMBU - Problemas de Pagamento</title>
        <style>
            p{font-size:16px; text-align:justify; margin-top:5px}
            @media (min-width: 200px) and (max-width: 580px){
                .lateral{
                    padding:20px
                }
            }
        </style>
    </head>
    <body >
        <div style="text-align: center;">
            <div style="max-width: 580px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
                <div style="padding:24px 16px; border-top-left-radius:15px; border-top-right-radius:15px; text-align:center; background-color:#FF5733">
                    <img src="https://dumbu.pro/follows/src/assets/images/logo.png"><br>
                    <h1 S>Pagamento não encontrado!</h1>
                </div>
                <div style="padding:60px; background-color:#F5F8F6" class="lateral">
                    <div>
                        <p>Olá <strong><?php echo urldecode($_GET["username"]);?></strong>, Tudo bem? </p>
                        <p><strong>Houve algum problema? :(</strong></p>
                        <p>Ainda não conseguimos registrar a cobrança do seu serviço na Dumbu.</p>
                        <?php
                            $diff_days = $_GET["diff_days"];
                            if($diff_days>0){?>
                                <p>Informamos que sua conta poderia ser bloqueada em até <?php echo $diff_days;?> dias. Apesar disso, fique tranquilo, sabemos que problemas acontecem e, por isso, vamos tentar de novo!</p>
                        <?php }else{?>
                                <p>Infelizmente sua conta foi bloqueada por pagamento! Mas não se preocupe, você pode entar na sua conta e atualizar seus dados de pagamento.</p>
                        <?php }?>
                          
                        <p><strong>Veja o que pode ter acontecido:</strong></p>
                        <div style="padding-left:30px;padding-right:30px">
                            <p>- Seus dados de cobrança estarem desatualizados.</p>
                            <p>- O cartão que você cadastrou ter expirado.</p>
                            <p>- Não haver saldo suficiente no cartão que você cadastrou.</p>
                        </div>
                                                
                        <p>Seu usuário no nosso sistema é: <strong><?php echo $_GET["instaname"]; ?></strong></p>
                        
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
