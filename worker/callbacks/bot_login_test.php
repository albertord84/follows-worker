<?php

require_once '../class/Worker.php';
require_once '../class/Robot.php';
require_once '../class/system_config.php';

$GLOBALS['sistem_config'] = new follows\cls\system_config();
$Robot = new follows\cls\Robot();

$a = $_GET;
$login = urldecode($_GET['login']);
$pass = urldecode($_GET['pass']);
$force = urldecode($_GET['force_login']);
($force=='')?$force=FALSE:$force=TRUE;
if($login!='' && $login!=FALSE && $login!=NULL && $pass!='' && $pass!=FALSE && $pass!=NULL)
    $result = $Robot->bot_login($login, $pass, $force);
else{
    $result->json_response->status = 'ok';
    $result->json_response->authenticated = FALSE;
}
echo json_encode($result);



//http://localhost/follows/worker/callbacks/bot_login_test.php?login=pampodelivery&pass=josepampo&force_login=
