<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>DUMBU - Cliente sem Perfis de Referência</title>
        <style>
            p{font-size:16px; text-align:justify; margin-top:5px}
        </style>
    </head>
    <body >
        <div style="text-align: center;">
            <div style="max-width: 580px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
                <div style="padding:24px 16px; border-top-left-radius:15px; border-top-right-radius:15px; text-align:center; background-color:#FF5733">
                    <img src="https://dumbu.pro/follows/src/assets/images/logo.png"><br>
                    <h1 S>Perfis de Referência não encontrados!</h1>
                </div>
                <div style="padding:60px; background-color:#F5F8F6">
                    <div>
                        <p>Olá <strong><?php echo urldecode($_GET["username"]);?></strong>,</p>
                        <p> Detectamos que você não tem Perfis de Referência, Geolocalização nem Hashtag ativos na sua conta. A Dumbu não deposita seguidores na sua conta. 
                            Os seguidores são captados diariamente a través de Perfis, Geolocalização e/ou Hashtag de interesse pra você, possibilitando assim uma maior segmentação 
                            dos seus seguidores. Faça login no nosso <a href="<?php echo urldecode($_GET["site"]);?>" target="_blank">site</a>
                            e adicione.
                        </p>
                                                
                        <p><strong>Algumas dicas importantes para aumentar seu engajamento são:</strong></p>
                        <div style="padding-left:30px;padding-right:30px">
                            <p> -Poste regularmente;</p>
                            <p>-Não deixe a conta privada pois os seguidores seguem por identificação com a página;</p>
                            <p>-Nunca escolha perfis pequenos ou privados com menos de 5 mil seguidores;</p>
                            <p>-Se notar que o desempenho não está muito bom, experimente trocar o perfil de referência que tem menos engajamento, 
                                ou seja, aqueles perfis que tem muitos seguidores mas poucos likes.</p>
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
