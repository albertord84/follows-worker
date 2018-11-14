<?PHP

require_once 'class/Worker.php';
require_once 'class/system_config.php';
require_once 'class/Gmail.php';
require_once 'class/Payment.php';

echo "Worker Inited...!<br>\n";
echo date("Y-m-d h:i:sa");

$GLOBALS['sistem_config'] = new follows\cls\system_config();

$Worker = new follows\cls\Worker(NULL,999);
$Worker->truncate_daily_work();
$Worker->prepare_daily_work();
echo "\n<br>" . date("Y-m-d h:i:sa") . "\n\n";
