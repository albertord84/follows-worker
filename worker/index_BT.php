<?PHP

require_once 'class/Worker.php';
require_once 'class/system_config.php';
require_once 'class/Gmail.php';
require_once 'class/Payment.php';

echo "Worker Inited...!<br>\n";
echo date("Y-m-d h:i:sa");

$GLOBALS['sistem_config'] = new follows\cls\system_config();




$Worker = new follows\cls\Worker(NULL,999);
$DB = new follows\cls\DB();
$clients = $DB->get_clients_by_status(follows\cls\user_status::BLOCKED_BY_TIME);
while($client_data = $clients->fetch_object())
{
    $Worker->prepare_daily_work($client_data->user_id,TRUE);
    $DB->set_client_status($client_data->user_id, follows\cls\user_status::ACTIVE);
}

echo "\n<br>" . date("Y-m-d h:i:sa") . "\n\n";
