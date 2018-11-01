<?PHP

require_once '../class/Worker.php';
require_once '../class/system_config.php';
require_once '../class/Gmail.php';
require_once '../class/Payment.php';

echo "Worker Inited...!<br>\n";
echo date("Y-m-d h:i:sa");

$GLOBALS['sistem_config'] = new follows\cls\system_config();




// MUNDIPAGG
//$Payment = new follows\cls\Payment();

//$Payment->check_payment("e0c0954a-dbd5-4e79-b513-0769d89bb490");

//----------------------------------------------------------------

// GMAIL
// 
//$Gmail = new follows\cls\Gmail();
//$Gmail->send_client_contact_form("Alberto Reyes", "albertord84@gmail.com", "Test contact formm msg", "DUMBU", "555-777-777");
//$Gmail->send_client_payment_error("josergm86@gmail.com", "Jose Ramon", "josergm86", "joseramon");

//$Robot = new follows\cls\Robot();
//
//$Robot->bot_login("josergm86", "joseramon");

//----------------------------------------------------------------

// WORKER

$id = filter_input(INPUT_GET, 'id',FILTER_VALIDATE_INT);
$client_id = filter_input(INPUT_GET, 'client_id',FILTER_VALIDATE_INT);
$n = filter_input(INPUT_GET, 'n',FILTER_VALIDATE_INT);
$rp  = filter_input(INPUT_GET, 'rp',FILTER_VALIDATE_INT);
if($n == NULL || $n < 0)
{
    $n = 3;
}
if($client_id != NULL)
{
    $DB = new follows\cls\DB();
    if($DB->has_work($client_id,$rp))
    {
        $hours = 24;
        $Client = (new follows\cls\Client())->get_client($client_id);
        $last_acces = $Client->last_access;
        if(time() < $last_acces)
            $hours = $hours - (($last_acces - time()) / 3600 % 24);
        echo "Increasing last acces to client: $client_id for $hours H";
        $DB->Increase_Client_Last_Access($client_id,$hours);
        $Worker = new follows\cls\Worker(NULL,$id);

        //$Worker->check_daily_work();
        //$Worker->truncate_daily_work();
        //$Worker->prepare_daily_work();
        if($client_id !== NULL && $client_id > 0)
            $Worker->do_work($client_id,$n,$rp);

        $DB->Set_Client_Last_Access($client_id, $last_acces);
        echo "Set last acess to time $last_acces";
    }
    else
        echo "<br>Client $client_id without work<\br>";
//----------------------------------------------------------------
}
echo "\n<br>" . date("Y-m-d h:i:sa") . "\n\n";
