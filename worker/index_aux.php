<?PHP

require_once 'class/Worker.php';
require_once 'class/system_config.php';
require_once 'class/Gmail.php';
require_once 'class/Payment.php';

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
$Worker = new follows\cls\Worker(NULL,999);
$Worker->truncate_daily_work();
$Worker->prepare_daily_work_not_mail();
$Gmail = new follows\cls\Gmail();
$Gmail->send_mail("josergm86@gmail.com", "Jose Ramon ",'DUMBU prepare daily work done!!! ','DUMBU prepare daily work done!!! ');
$Gmail->send_mail("jangel.riveaux@gmail.com", "Jose Angel Riveaux ",'DUMBU prepare daily work done!!! ','DUMBU prepare daily work done!!! ');
                        
//----------------------------------------------------------------

echo "\n<br>" . date("Y-m-d h:i:sa") . "\n\n";
