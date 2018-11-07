<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>DUMBU - Login error</title>
        <style>
            p{font-size:16px; text-align:justify; margin-top:5px}
        </style>
    </head>
    <body >
        <div style="text-align: center;">
            <div style="max-width: 580px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
                <div style="padding:24px 16px; border-top-left-radius:15px; border-top-right-radius:15px; text-align:center; background-color:#FF5733">
                    <img src="https://dumbu.one/follows/src/assets/images/logo.png"><br>
                    <h1 S>Error in Login, incorrect credentials!</h1>
                </div>
                <div style="padding:60px; background-color:#F5F8F6">
                    <div>
                        <p>Hello <strong><?php echo urldecode($_GET["username"]);?></strong>,</p>
                        <p>We had problems logging in with the Instagram credentials
                             authorized by you in our system. If you have changed your user name or password on Instagram,
                             please log in to our <a href="<?php echo urldecode($_GET["site"]);?>" target="_blank">site</a>
                            and your credentials will be updated automatically.
                        </p>
                        <p>Your user in our system is: <strong><?php echo $_GET["instaname"]; ?></strong></p>
                        <p>IMPORTANT: your credentials should be the same in both DUMBU and Instagram.</p>
                        
                        <p>If you'd like help setting up your account, or if you have questions, write to
                            <span style="color:blue"><?php echo urldecode($_GET["atendent_email"]);?></span>
                             and we are going to help you! :)
                        </p>
                        <p>Thanks!</p>
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
