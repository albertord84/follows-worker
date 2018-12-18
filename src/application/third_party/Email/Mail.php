<?php

namespace business\cls {
    //SMTP needs accurate times, and the PHP time zone MUST be set
    //This should be done in your php.ini, but this is how to do it if you don't have access to that
    date_default_timezone_set('Etc/UTC');
    require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/externals/PHPMailer-master/PHPMailerAutoload.php';
    class Mail {
        
        
    }
 }