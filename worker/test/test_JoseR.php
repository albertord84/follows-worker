<?PHP
require_once '../class/Worker.php';
require_once '../class/system_config.php';
require_once '../class/Gmail.php';
require_once '../class/Payment.php';
require_once '../class/Client.php';
require_once '../class/Reference_profile.php';
require_once '../class/PaymentCielo3.0.php';
require_once '../class/InstaAPI.php';
require_once '../class/Robot.php';

//echo "Worker Inited...!<br>\n";
echo date("Y-m-d h:i:sa") . "<br>\n";

ini_set('xdebug.var_display_max_depth', 17);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 8024);


$GLOBALS['sistem_config'] = new follows\cls\system_config();

//$InstaAPI = new \follows\cls\InstaAPI();


//$result = $InstaAPI->login($username, $password);
//var_dump($result->Cookies);
//var_dump($result);




//--------------------------------------------------------------------------------
// Ref Prof
//$RP = new \follows\cls\Reference_profile();
//
//
//
//$DB = new \follows\cls\DB();
//
//
//$Client = new \follows\cls\Client();



//--------------------------------------------------------------------------------
// MUNDIPAGG
//$Payment = new follows\cls\Payment();

//$pay_day = strtotime('05/18/2018 00:18:37');
//$pay_day = strtotime("+30 days", $pay_day);

//$pay_day = time();
//$strdate = date("d-m-Y", $pay_day);

//$pay_day = strtotime("+1 months", time());

//$payment_data['credit_card_number'] = 'PEDRO BASTOS PETTI';
//$payment_data['credit_card_name'] = '5162202091174685';
//$payment_data['credit_card_exp_month'] = '04';
//$payment_data['credit_card_exp_year'] = '2021';
//$payment_data['credit_card_cvc'] = '302';
//$payment_data['amount_in_cents'] = 4990;
//$payment_data['pay_day'] = $pay_day;

//$resul = $Payment->create_payment($payment_data);
//var_dump($resul);
//$resul = $Payment->create_recurrency_payment($payment_data, 0, 20);
//var_dump($resul);
////$resul = $Payment->create_recurrency_payment($payment_data, 0, 42);
////var_dump($resul);

//var_dump($pay_day);

////--------------------------------------------------------------------------------


//$pay_day = time();
//$strdate = date("d-m-Y", $pay_day);
//$pay_day = strtotime("+1 months", time());
//
//$payment_data['credit_card_number'] = '5155901297908882';
//$payment_data['credit_card_name'] = 'GIANCARLO MENEGHINI';
//$payment_data['credit_card_exp_month'] = '01';
//$payment_data['credit_card_exp_year'] = '2024';
//$payment_data['credit_card_cvc'] = '339';
//$payment_data['amount_in_cents'] = 7990;
//$payment_data['pay_day'] = $pay_day;
//
//$resul = $Payment->create_payment($payment_data);
//var_dump($resul);
//$resul = $Payment->create_recurrency_payment($payment_data, 0, 20);
//var_dump($resul);
////$resul = $Payment->create_recurrency_payment($payment_data, 0, 42);
////var_dump($resul);

//var_dump($pay_day);


echo 'hola Jose';

//--------------------------------------------------------------------------------
// GMAIL
//$Gmail = new \follows\cls\Gmail();
//
$Robot = new \follows\cls\Robot();
$resp = $Robot->bot_login('companyate', 'vivi0405', FALSE);
var_dump($resp);
//
//
//$Robot = new follows\cls\Robot();
////$response = $Robot->get_insta_ref_prof_following('spadassobrancelhaszonasul');
////var_dump($response);
//
//$client = $Client->get_client(27509);
////var_dump($client);
//
//
//
//
//
//$DB =  new follows\cls\DB();
//$Worker =  new follows\cls\Worker();
//
//
//
//
//
//    $daily_work = $DB->get_follow_work_by_id(39366);
//    if ($daily_work) {
//        $daily_work->login_data = json_decode($daily_work->cookies);
//        if ($daily_work->login_data != NULL) {
//            $elapsed_time = time() - intval($daily_work->last_access); // sec
//            if ($elapsed_time < $GLOBALS['sistem_config']->MIN_NEXT_ATTEND_TIME * 60) {
//                $now = \DateTime::createFromFormat('U', time());
//                $last_access = \DateTime::createFromFormat('U', $daily_work->last_access);
//                print "<br>_________ELAPSED TIME ($elapsed_time): ";
//                sleep($GLOBALS['sistem_config']->MIN_NEXT_ATTEND_TIME * 60 - $elapsed_time); // secounds
//            }
//            $Worker->do_follow_unfollow_work($daily_work);
//            //die("Test End!!");
//        } else {
//            print "<br> Login data NULL!!!!!!!!!!!! <br>";
//        }
//    } else {
//        $has_work = FALSE;
//    }





















//$result = $Robot->bot_login("riveauxmerino", "Notredame88");
//var_dump($result);
//$result = $Robot->bot_login("ruslan.guerra88", "*R5sl@n#");
//var_dump($result);
//
//$mid = "WdJCIgAEAAH8jG4L-TEtJUTVmQpu";
//$csrftoken = "lT29VKGJfD2vbglPsLLKNfW22qDH1Pp5";
//
//$result = $Robot->str_login($mid, $csrftoken, "ruslan.guerra88", "*R5sl@n#");
//var_dump($result);
//$url = "https://www.instagram.com/";
//$ch = curl_init($url);
//$Client = NULL;
//$login = "leticiajural";
//$pass  = "estrelaguia";
//$csrftoken = "z2EF0sSQa0lAMzOmJ1JoVT7sJ3qsBi2q";
//$mid       = "WivT0QAEAAFuK04pKqHMX2UoUlV8";
//$result = $Robot->login_insta_with_csrftoken($ch, $login, $pass, $csrftoken, $mid, $Client);
//
//var_dump($result);
//var_dump(json_encode($result));
//print_r(json_encode($result));
//$result = $Robot->bot_login('amourzinah','reda1997');  //'julianabaraldi83','tininha1712'   'guilfontes','persian'
//print_r(json_encode($result));
//var_dump("" == NULL);
//$result = $Robot->bot_login("urpia", "romeus33");
//var_dump($result);
//$Gmail->send_client_login_error("ronefilho@gmail.com", 'Rone', "ronefilho", "renivalfilho");
//$result = $Robot->bot_login("vaniapetti", "202020");
//var_dump($result);
//$result = $Robot->bot_login("lambaosbeicos", "75005310");
//$result = $Robot->bot_login("alberto_dreyes", "albertord7");
//var_dump($result);
//$result = $Robot->bot_login("tompsonr", "sorvete6969");
//var_dump($result);


//--------------------------------------------------------------------------------
// WORKER
$Worker = new follows\cls\Worker();
//$daily_work = $Worker->get_work_by_id(2);
////$Worker->do_follow_unfollow_work($daily_work);
//$error = NULL; $page_info = NULL;
//var_dump($daily_work->rp_insta_id);
//$profiles = $Robot->get_profiles_to_follow($daily_work, $error, $page_info);
//var_dump($profiles);
//
////$Worker->check_daily_work();
//$Worker->truncate_daily_work();
//$Worker->prepare_daily_work();
//$Worker->do_work();
//----------------------------------------------------------------

echo "\n<br>" . date("Y-m-d h:i:sa") . "\n\n";
