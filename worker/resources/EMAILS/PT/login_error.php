<table width="650" cellspacing="0" cellpadding="0" border="0" bgcolor="#fff" align="center" style="@import url('https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900');font-family: 'Roboto', sans-serif;font-size:13px;color:#3e3e3e;">
	<tbody>
	    <tr>
	        <td colspan="3" height="180" bgcolor="#fffd00" align="center">
                    <h1 style="font-weight: 600;font-size: 30px;line-height: 30px">Suas fotos não <br>foram aprovadas <br>
                        <small style="font-size:18px">Veja o passo a passo abaixo:</small>
                    </h1>
                </td>
	    </tr>
	    <tr>
	        <td width="105"></td>
	        <td width="440" valign="top" align="center" height="40">
                    <br><br>
                    <img src="../../assets/img/icones/09 - fotos erradas.png" alt="">   
                    <br><br>
                    <div style="text-align: left;">
                        <h2 style="color: #20a7d3;">Oi <?php echo urldecode($_GET["name"]);?>!</h2>
                        <p>
                            Infelizmente suas fotos não estão legíveis ou os dados não batem com a conta informada. <br><br>
                            <b style="background-color:#00ff66;">Você só precisa reenviar novas fotos.</b> <br><br>
                            <b>ATENÇÃO:</b> <br><br>
                            <b>1.</b> Você precisa ser o titular da conta que receberá o valor do crédito. <br><br>
                            <b>2.</b> As fotos precisam estar em boa qualidade e em local iluminado. <br>
                        </p> 
                        <br>
                        <a href="<?php echo urldecode($_GET["link"]);?>" style="background-color: #00ff66;padding: 10px 50px !important;font-weight: 600;color: black;text-align: center;border: none;border-radius: 30px !important;text-decoration: none;display: block;">
                            Enviar novas fotos
                        </a>
                        <p>
                            Se estiver tudo certo seu crédito será liberado. <br><br> <b>Vamos enviar e-mails de atualizações sobre o seu pedido.</b> Até já!
                        </p>
                    </div> 
                    <div>
                        <br><br>
                        <h2 style="color: #20a7d3;">Está com dúvidas?</h2>
                        <p>
                            Fale com a gente pelo e-mail: <br><b>seja@livre.digital</b>
                        </p> 
                    </div>      
	        </td>
	        <td width="105"></td>
	    </tr>
	    <tr>
	      	<td colspan="3" width="650" valign="middle" align="center" height="120">
                    <img src="../../assets/img/icones/logo-mkt.png">
                </td>
	    </tr>
	</tbody>
</table>