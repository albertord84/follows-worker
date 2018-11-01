<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>User Contact</title>
</head>
<body>
<div style="width: 640px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
  <h1>Olá  <strong><?php echo $_GET["username"]; ?></strong>!!</h1>
  <p>Seu boleto fooi gerado com sucessso.</p>
  <p>Descarregue o boleto '<a  href="<?php echo $_GET["ticket_link"]; ?>"> AQUI</a>'</p>
  <p>Qualquer dúvida contate nosso atendiemnto acessando a través de www.dumbu.pro.</p>
  <p>Equipe DUMBU</p>
</div>
</body>
</html>
