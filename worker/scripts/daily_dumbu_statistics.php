<?php

require_once '../class/Client.php';
require_once '../class/system_config.php';

$GLOBALS['sistem_config'] = new follows\cls\system_config();

$Client = new follows\cls\Client();

$result = $Client->dumbu_statistics();

