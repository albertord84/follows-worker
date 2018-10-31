<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>DUMBU - Problemas de Pago</title>
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
                    <h1 S>Pago no encontrado!</h1>
                </div>
                <div style="padding:60px; background-color:#F5F8F6" class="lateral">
                    <div>
                        <p>Olá <strong><?php echo urldecode($_GET["username"]);?></strong>, Tudo bem? </p>
                        <p><strong>Hubo algun problema? :(</strong></p>
                        <p>Aún no conseguimos registrar su pŕoximo pago en Dumbu.</p>
                        <?php
                            $diff_days = $_GET["diff_days"];
                            if($diff_days>0){?>
                                <p>Informamos que su cuenta podría ser bloqueada en <?php echo $diff_days;?> días. Apesar de eso, quede tranquilo, sabemos que problemas ocurren y, por eso, vamos a intentar de nuevo!</p>
                        <?php }else{?>
                                <p>Infelizmente su cuenta fue bloqueada por pago no realizado! Pero no se preocupe, usted puede entar en su cuenta y actualizar sus datos de pagamento.</p>
                        <?php }?>
                          
                        <p><strong>Vea lo que puede haber sucedido:</strong></p>
                        <div style="padding-left:30px;padding-right:30px">
                            <p>- Sus datos de cobro están desactualizados.</p>
                            <p>- La tarjeta de crédito que usted registró puede haber expirado.</p>
                            <p>- No tiene saldo suficiente en la tarjeta de crédito que usted registró.</p>
                        </div>
                                                
                        <p>Su usuario en nuestro sistema es: <strong><?php echo $_GET["instaname"]; ?></strong></p>
                        
                        <p>Se necesita ayuda para configurar su cuenta, o si tuviera dudas, escriva para 
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
