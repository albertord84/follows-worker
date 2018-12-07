<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>DUMBU - Problems with your Payment</title>
        <style>
            p{font-size:16px; text-align:justify; margin-top:5px}
            @media (min-width: 200px) and (max-width: 580px){
                .lateral{
                    padding:20px
                }
            }
        </style>
    </head>
    <body >
        <div style="text-align: center;">
            <div style="max-width: 580px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
                <div style="padding:24px 16px; border-top-left-radius:15px; border-top-right-radius:15px; text-align:center; background-color:#FF5733">
                    <img src="https://dumbu.one/follows/src/assets/images/logo.png"><br>
                    <h1 S>Payment not found!</h1>
                </div>
                <div style="padding:60px; background-color:#F5F8F6" class="lateral">
                    <div>
                        <p>Hello <strong><?php echo urldecode($_GET["username"]);?></strong>, all right? </p>
                        <p><strong>You have any payment problem? :(</strong></p>
                        <p>We have not yet been able to record the charge for your Dumbu service.</p>
                        <?php
                            $diff_days = $_GET["diff_days"];
                            if($diff_days>0){?>
                                <p>We advised you that your account could be locked in up to <?php echo $diff_days;?> days. In spite of this, be calm, we know that problems happen and, therefore, we will try again!</p>
                        <?php }else{?>
                                <p>Unfortunately your account was blocked by payment! But do not worry, you can log into your account and update your payment information.</p>
                        <?php }?>
                          
                        <p><strong>See what could have happened:</strong></p>
                        <div style="padding-left:30px;padding-right:30px">
                            <p>- Your billing information is out of date.</p>
                            <p>- The credit card you registered has expired.</p>
                            <p>- There is not enough balance on the card that you registered.</p>
                        </div>
                                                
                        <p>Your user in our system is: <strong><?php echo $_GET["instaname"]; ?></strong></p>
                        
                        <p>If you'd like help setting up your account, or if you have questions, write to
                            <span style="color:blue"><?php echo urldecode($_GET["atendent_email"]);?></span>
                             and we are going to help you! :)
                        </p>
                        <p>
                            <br><br><i>Att. Dumbu team</i><br><br>
                        </p>
                    </div>        
                    <div style="text-align: center">        
                        <a href="<?php echo urldecode($_GET["site"]);?>" target="_blank">
                            <button style="font: bold 1.4em sans-serif; color:white; width:220px; height:40px; border-radius:20px;background-color:blue">GO TO THE SITE</button>
                        </a>
                    </div>        
                </div>
                <div style="padding:24px 16px; border-bottom-left-radius:15px; border-bottom-right-radius:15px; text-align:center; background-color:#7E7D7D;">
                    <img src="https://dumbu.one/follows/src/assets/images/logo-footer.png"><br>
                    <h5>DUMBU - <?php echo date("Y",time());?> - ALL RIGHTS RESERVED</h5>
                </div>
            </div>
        </div>
    </body>
</html>
