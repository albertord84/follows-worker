<?php
/**
 * This example shows settings to use when sending via Google's Gmail servers.
 */
namespace business\cls {
    //SMTP needs accurate times, and the PHP time zone MUST be set
    //This should be done in your php.ini, but this is how to do it if you don't have access to that
    date_default_timezone_set('Etc/UTC');
    require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/externals/PHPMailer-master/PHPMailerAutoload.php';
    class Gmail extends Mail {
        public $mail = NULL;
        
        public function __construct() {
            //Create a new PHPMailer instance
            $this->mail = new \PHPMailer;
            //Tell PHPMailer to use SMTP
            $this->mail->isSMTP();
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $this->mail->SMTPDebug = 0;
            //$this->mail->SMTPDebug = 2;
            //Ask for HTML-friendly debug output
             $this->mail->Debugoutput = 'html';
            //Set the hostname of the mail server
            $this->mail->Host = 'smtp.gmail.com'; // dumbu.system
            //$this->mail->Host = 'imap.gmail.com'; // atendimento
            // use
            // $mail->Host = gethostbyname('smtp.gmail.com');
            // if your network does not support SMTP over IPv6
            //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
            $this->mail->Port = 587; // dumbu.system
            //$this->mail->Port = 993; // atendimento
            //Set the encryption system to use - ssl (deprecated) or tls
            $this->mail->SMTPSecure = 'tls'; // dumbu.system
            //$this->mail->SMTPSecure = 'ssl'; // atendimento
            //Whether to use SMTP authentication
            $this->mail->SMTPAuth = true; // dumbu.system
            //$this->mail->SMTPAuth = false; // atendimento
            //Username to use for SMTP authentication - use full email address for gmail
            //$this->mail->Username = $GLOBALS['sistem_config']->SYSTEM_USER_LOGIN;
            //$this->mail->Username = $GLOBALS['sistem_config']->ATENDENT_USER_LOGIN;
            //$this->mail->Username = $GLOBALS['sistem_config']->SYSTEM_USER_LOGIN3;
            $this->mail->Username = $GLOBALS['sistem_config']->SYSTEM_USER_LOGIN;
            //Password to use for SMTP authentication
            //$this->mail->Password = $GLOBALS['sistem_config']->SYSTEM_USER_PASS;
            //$this->mail->Password = $GLOBALS['sistem_config']->SYSTEM_USER_PASS2;
            //$this->mail->Password = $GLOBALS['sistem_config']->SYSTEM_USER_PASS3;
            $this->mail->Password = $GLOBALS['sistem_config']->SYSTEM_USER_PASS;
            //Set who the message is to be sent from
            //$this->mail->setFrom($GLOBALS['sistem_config']->SYSTEM_EMAIL, 'DUMBU');
            //$this->mail->setFrom($GLOBALS['sistem_config']->ATENDENT_EMAIL, 'DUMBU');
            //$this->mail->setFrom($GLOBALS['sistem_config']->SYSTEM_EMAIL3, 'DUMBU');
            $result = $this->mail->setFrom($GLOBALS['sistem_config']->SYSTEM_EMAIL, 'DUMBU');
            // @TUDO -> Trapo ate habilitar peticoes locais no immotion hosting...
            $_SERVER['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME === "ONE"? "dumbu.one" : $_SERVER['SERVER_NAME'];
        }
        
        //-------funciones para ser usados desde External_services------------------------------------
        public function send_user_to_purchase_step($subject, $useremail, $username, $instaname, $purchase_access_token) {
            $this->mail->clearAddresses();
            $this->mail->addAddress($useremail, $username);
            $this->mail->clearCCs();
            $this->mail->addReplyTo($GLOBALS['sistem_config']->ATENDENT_EMAIL, $GLOBALS['sistem_config']->ATENDENT_USER_LOGIN);
            $this->mail->CharSet = 'UTF-8';
            $this->mail->Subject = $subject;
            $username = urlencode($username);
            $instaname = urlencode($instaname);
            $purchase_access_token = urlencode($purchase_access_token);
            $lang = $GLOBALS['sistem_config']->LANGUAGE;
            $this->mail->msgHTML(@file_get_contents("http://" . $_SERVER['SERVER_NAME'] . "/follows-worker/worker/resources/EMAILS/$lang/link_purchase_step.php?username=$username&instaname=$instaname&purchase_access_token=$purchase_access_token"), dirname(__FILE__));
            if (!$this->mail->send()) {
                $result['success'] = false;
                $result['message'] = "Mailer Error: " . $this->mail->ErrorInfo;
            } else {
                $result['success'] = true;
                $result['message'] = "Message sent!" . $this->mail->ErrorInfo;
            }
            $this->mail->smtpClose();
            return $result;
        }
        
        public function send_link_ticket_bank_and_access_link($subject, $username, $useremail, $access_link, $ticket_link){
            $this->mail->clearAddresses();
            $this->mail->addAddress($useremail);
            $this->mail->addCC($GLOBALS['sistem_config']->ATENDENT_EMAIL, $GLOBALS['sistem_config']->ATENDENT_USER_LOGIN);
            $this->mail->addReplyTo($GLOBALS['sistem_config']->ATENDENT_EMAIL, $GLOBALS['sistem_config']->ATENDENT_USER_LOGIN);
            $this->mail->clearReplyTos();
            $this->mail->CharSet = 'UTF-8';
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $username = urlencode($username);
            $access_link = urlencode($access_link);
            $ticket_link = urlencode($ticket_link);
            $lang = $GLOBALS['sistem_config']->LANGUAGE;
            $atendent_email = $GLOBALS['sistem_config']->ATENDENT_EMAIL;
            $this->mail->msgHTML(@file_get_contents("http://". $_SERVER['SERVER_NAME'] ."/follows-worker/worker/resources/EMAILS/$lang/tiket_bank.php?username=$username&access_link=$access_link&ticket_link=$ticket_link&atendent_email=$atendent_email"), dirname(__FILE__));
            if (!$this->mail->send()) {
                $result['success'] = false;
                $result['message'] = "Mailer Error: " . $this->mail->ErrorInfo;
            } else {
                $result['success'] = true;
                $result['message'] = "Message sent!" . $this->mail->ErrorInfo;
            }
            $this->mail->smtpClose();
            return $result;
        }
        
        public function send_client_contact_form($subject, $username, $useremail, $usermsg, $usercompany = NULL, $userphone = NULL) {
            $this->mail->clearAddresses();
            $this->mail->addAddress($GLOBALS['sistem_config']->SYSTEM_EMAIL, $GLOBALS['sistem_config']->SYSTEM_USER_LOGIN);
            $this->mail->addCC($GLOBALS['sistem_config']->ATENDENT_EMAIL, $GLOBALS['sistem_config']->ATENDENT_USER_LOGIN);
            $this->mail->clearReplyTos();
            $this->mail->addReplyTo($useremail, $username);
            $this->mail->isHTML(true);
            $this->mail->CharSet = 'UTF-8';
            $this->mail->Subject = $subject.": $username";
            $username = urlencode($username);
            $usermsg = urlencode($usermsg);
            $usercompany = urlencode($usercompany);
            $userphone = urlencode($userphone);
            $site= "https://www.".$GLOBALS['sistem_config']->SERVER_URL;
            $atendent_email =$GLOBALS['sistem_config']->ATENDENT_EMAIL;
            $lang = $GLOBALS['sistem_config']->LANGUAGE;
            $this->mail->msgHTML(@file_get_contents("http://". $_SERVER['SERVER_NAME'] ."/follows-worker/worker/resources/EMAILS/$lang/contact_form.php?username=$username&useremail=$useremail&usercompany=$usercompany&userphone=$userphone&usermsg=$usermsg&site=$site&atendent_email=$atendent_email"), dirname(__FILE__));
            $this->mail->AltBody = "User Contact: $username";
            if (!$this->mail->send()) {
                $result['success'] = false;
                $result['message'] = "Mailer Error: " . $this->mail->ErrorInfo;
            } else {
                $result['success'] = true;
                $result['message'] = "Message sent!" . $this->mail->ErrorInfo;
            }
            $this->mail->smtpClose();
            return $result;
        }
        
        public function send_link_ticket_bank_in_update($subject, $useremail, $username, $ticket_link){      
            $this->mail->clearAddresses();
            $this->mail->addAddress($useremail);
            $this->mail->addCC($GLOBALS['sistem_config']->ATENDENT_EMAIL, $GLOBALS['sistem_config']->ATENDENT_USER_LOGIN);
            $this->mail->addReplyTo($GLOBALS['sistem_config']->ATENDENT_EMAIL, $GLOBALS['sistem_config']->ATENDENT_USER_LOGIN);
            $this->mail->clearReplyTos();
            $this->mail->isHTML(true);
            $this->mail->CharSet = 'UTF-8';
            $this->mail->Subject = $subject;
            $username = urlencode($username);
            $ticket_link = urlencode($ticket_link);
            $atendent_email = $GLOBALS['sistem_config']->ATENDENT_EMAIL;
            $lang = $GLOBALS['sistem_config']->LANGUAGE;
            $this->mail->msgHTML(@file_get_contents("http://". $_SERVER['SERVER_NAME'] ."/follows-worker/worker/resources/EMAILS/$lang/update_tiket_bank.php?username=$username&ticket_link=$ticket_link&atendent_email=$atendent_email"), dirname(__FILE__));
            if (!$this->mail->send()) {
                $result['success'] = false;
                $result['message'] = "Mailer Error: " . $this->mail->ErrorInfo;
            } else {
                $result['success'] = true;
                $result['message'] = "Message sent!";
            }
            $this->mail->smtpClose();
            return $result;
        }
        
        public function send_client_payment_success($subject, $useremail, $username, $instaname, $instapass) {
            $this->mail->clearAddresses();
            $this->mail->addAddress($useremail, $username);
            $this->mail->clearCCs();
            $this->mail->addCC($GLOBALS['sistem_config']->ATENDENT_EMAIL, $GLOBALS['sistem_config']->ATENDENT_USER_LOGIN);
            $this->mail->addReplyTo($GLOBALS['sistem_config']->ATENDENT_EMAIL, $GLOBALS['sistem_config']->ATENDENT_USER_LOGIN);
            $this->mail->CharSet = 'UTF-8';
            $this->mail->Subject = $subject;
            $username = urlencode($username);
            $instaname = urlencode($instaname);
            $instapass = urlencode($instapass);
            $site= "https://www.".$GLOBALS['sistem_config']->SERVER_URL;
            $atendent_email =$GLOBALS['sistem_config']->ATENDENT_EMAIL;
            $lang = $GLOBALS['sistem_config']->LANGUAGE;
            $this->mail->msgHTML(@file_get_contents("http://" . $_SERVER['SERVER_NAME'] . "/follows-worker/worker/resources/EMAILS/$lang/payment_success.php?username=$username&instaname=$instaname&site=$site&atendent_email=$atendent_email"), dirname(__FILE__));
            if (!$this->mail->send()) {
                $result['success'] = false;
                $result['message'] = "Mailer Error: " . $this->mail->ErrorInfo;
            } else {
                $result['success'] = true;
                $result['message'] = "Message sent!" . $this->mail->ErrorInfo;
            }
            $this->mail->smtpClose();
            return $result;
        }
                
        //-------funciones para ser usados desde lod ROBOTS------------------------------------
        public function send_mail($useremail, $username, $subject, $mail) {
            $this->mail->clearAddresses();
            $this->mail->addAddress($useremail, $username);
            $this->mail->clearCCs();
            $this->mail->CharSet = 'UTF-8';
            $this->mail->Subject = $subject;
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            $username = urlencode($username);
            //$instaname = urlencode($instaname);
            //$instapass = urlencode($instapass);
            //$this->mail->msgHTML(file_get_contents("http://localhost/follows-worker/worker/resources/emails/login_error.php?username=$username&instaname=$instaname&instapass=$instapass"), dirname(__FILE__));
            //echo "http://" . $_SERVER['SERVER_NAME'] . "<br><br>";
            $lang = $GLOBALS['sistem_config']->LANGUAGE;
            $this->mail->Body = $mail;
            //Attach an image file
            //$mail->addAttachment('images/phpmailer_mini.png');
            //send the message, check for errors
            if (!$this->mail->send()) {
                $result['success'] = false;
                $result['message'] = "Mailer Error: " . $this->mail->ErrorInfo;
            } else {
                $result['success'] = true;
                $result['message'] = "Message sent!" . $this->mail->ErrorInfo;
            }
            $this->mail->smtpClose();
            return $result;
        }
        
        public function send_client_login_error($useremail, $username, $instaname, $instapass = NULL) {
            $this->mail->clearAddresses();
            $this->mail->addAddress($useremail, $username);
            $this->mail->clearCCs();
            $this->mail->addCC($GLOBALS['sistem_config']->ATENDENT_EMAIL, $GLOBALS['sistem_config']->ATENDENT_USER_LOGIN);
            $this->mail->addReplyTo($GLOBALS['sistem_config']->ATENDENT_EMAIL, $GLOBALS['sistem_config']->ATENDENT_USER_LOGIN);
            $this->mail->CharSet = 'UTF-8';
            $lang = $GLOBALS['sistem_config']->LANGUAGE;
            if($lang=="PT")
                $this->mail->Subject = 'Verifique sua conta agora!';
            else
                $this->mail->Subject = 'DUMBU Problem with your login';
            $username = urlencode($username);
            $instaname = urlencode($instaname);
            $instapass = urlencode($instapass);
            $site= "https://www.".$GLOBALS['sistem_config']->SERVER_URL;
            $atendent_email =$GLOBALS['sistem_config']->ATENDENT_EMAIL;
            $this->mail->msgHTML(@file_get_contents("http://" . $_SERVER['SERVER_NAME'] . "/follows-worker/worker/resources/EMAILS/$lang/login_error.php?username=$username&instaname=$instaname&instapass=$instapass&site=$site&atendent_email=$atendent_email"), dirname(__FILE__));
            $this->mail->AltBody = 'DUMBU Problem with your login';
            if (!$this->mail->send()) {
                $result['success'] = false;
                $result['message'] = "Mailer Error: " . $this->mail->ErrorInfo;
            } else {
                $result['success'] = true;
                $result['message'] = "Message sent!" . $this->mail->ErrorInfo;
            }
            $this->mail->smtpClose();
            return $result;
        }
        
        public function send_client_not_rps($useremail, $username, $instaname, $instapass) {
            //Set an alternative reply-to address
            //$mail->addReplyTo('albertord@ic.uff.br', 'First Last');
            //Set who the message is to be sent to
            $this->mail->clearAddresses();
            $this->mail->addAddress($useremail, $username);
            $this->mail->clearCCs();
            //$this->mail->addCC($GLOBALS['sistem_config']->SYSTEM_EMAIL, $GLOBALS['sistem_config']->SYSTEM_USER_LOGIN);
            $this->mail->addCC($GLOBALS['sistem_config']->ATENDENT_EMAIL, $GLOBALS['sistem_config']->ATENDENT_USER_LOGIN);
            $this->mail->addReplyTo($GLOBALS['sistem_config']->ATENDENT_EMAIL, $GLOBALS['sistem_config']->ATENDENT_USER_LOGIN);
            $this->mail->CharSet = 'UTF-8';
            //Set the subject line
            //$this->mail->Subject = 'DUMBU Cliente sem perfis de referencia';
            $lang = $GLOBALS['sistem_config']->LANGUAGE;
            if($lang=="PT")
                $this->mail->Subject = 'Adicione perfis de referência :)';
            else
                $this->mail->Subject = 'DUMBU Client without reference profiles';
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            $username = urlencode($username);
            $instaname = urlencode($instaname);
            $instapass = urlencode($instapass);
            //$this->mail->msgHTML(file_get_contents("http://localhost/follows-worker/worker/resources/emails/login_error.php?username=$username&instaname=$instaname&instapass=$instapass"), dirname(__FILE__));
            //echo "http://" . $_SERVER['SERVER_NAME'] . "<br><br>";
            $site= "https://www.".$GLOBALS['sistem_config']->SERVER_URL;
            $atendent_email =$GLOBALS['sistem_config']->ATENDENT_EMAIL;
            $this->mail->msgHTML(@file_get_contents("http://" . $_SERVER['SERVER_NAME'] . "/follows-worker/worker/resources/EMAILS/$lang/not_reference_profiles.php?username=$username&instaname=$instaname&instapass=$instapass&site=$site&atendent_email=$atendent_email"), dirname(__FILE__));
            //Replace the plain text body with one created manually
            //$this->mail->AltBody = 'DUMBU Cliente sem perfis de referência';
            $this->mail->AltBody = 'DUMBU Client without reference profiles alert';
            //Attach an image file
            //$mail->addAttachment('images/phpmailer_mini.png');
            //send the message, check for errors
            if (!$this->mail->send()) {
                $result['success'] = false;
                $result['message'] = "Mailer Error: " . $this->mail->ErrorInfo;
            } else {
                $result['success'] = true;
                $result['message'] = "Message sent!" . $this->mail->ErrorInfo;
            }
            $this->mail->smtpClose();
            return $result;
        }
        
        public function send_client_payment_error($useremail, $username, $instaname, $instapass, $diff_days = 0) {
            //Set an alternative reply-to address
            //$mail->addReplyTo('albertord@ic.uff.br', 'First Last');
            //Set who the message is to be sent to
            $this->mail->clearAddresses();
            $this->mail->addAddress($useremail, $username);
            $this->mail->clearCCs();
            //$this->mail->addCC($GLOBALS['sistem_config']->SYSTEM_EMAIL, $GLOBALS['sistem_config']->SYSTEM_USER_LOGIN);
            $this->mail->addCC($GLOBALS['sistem_config']->ATENDENT_EMAIL, $GLOBALS['sistem_config']->ATENDENT_USER_LOGIN);
            $this->mail->addReplyTo($GLOBALS['sistem_config']->ATENDENT_EMAIL, $GLOBALS['sistem_config']->ATENDENT_USER_LOGIN);
            $this->mail->CharSet = 'UTF-8';
            //Set the subject line
            //$this->mail->Subject = "DUMBU Problemas de pagamento $diff_days dia(s)";
            $lang = $GLOBALS['sistem_config']->LANGUAGE;
            if($lang=="PT")
                $this->mail->Subject = "Pagamento não processado :(";
            else
                $this->mail->Subject = "Payment not processed :(";
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            $username = urlencode($username);
            $instaname = urlencode($instaname);
            $instapass = urlencode($instapass);
            //$this->mail->msgHTML(file_get_contents("http://localhost/follows-worker/worker/resources/emails/login_error.php?username=$username&instaname=$instaname&instapass=$instapass"), dirname(__FILE__));
            //echo "http://" . $_SERVER['SERVER_NAME'] . "<br><br>";
            $site= "https://www.".$GLOBALS['sistem_config']->SERVER_URL;
            $atendent_email =$GLOBALS['sistem_config']->ATENDENT_EMAIL;
            $_SERVER['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME === "ONE"? "dumbu.one" : $_SERVER['SERVER_NAME'];
            $this->mail->msgHTML(@file_get_contents("http://" . $_SERVER['SERVER_NAME'] . "/follows-worker/worker/resources/EMAILS/$lang/payment_error.php?username=$username&instaname=$instaname&instapass=$instapass&diff_days=$diff_days&site=$site&atendent_email=$atendent_email"), dirname(__FILE__));
            //Replace the plain text body with one created manually
            //$this->mail->AltBody = 'DUMBU Problemas de pagamento';
            $this->mail->Subject = "DUMBU Payment Issues";
            //Attach an image file
            //$mail->addAttachment('images/phpmailer_mini.png');
            //send the message, check for errors
            if (!$this->mail->send()) {
                $result['success'] = false;
                $result['message'] = "Mailer Error: " . $this->mail->ErrorInfo;
            } else {
                $result['success'] = true;
                $result['message'] = "Message sent!" . $this->mail->ErrorInfo;
                //print "<b>Informações do erro:</b> " . $this->mail->ErrorInfo;
            }
            $this->mail->smtpClose();
            return $result;
        }
        
        public function sendAuthenticationErrorMail($username, $useremail){
            
        }
                
        
        
    }
}