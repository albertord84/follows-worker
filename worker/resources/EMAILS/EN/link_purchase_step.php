<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>DUMBU - Account validation</title>
        <style>
            p{font-size:16px; text-align:justify; margin-top:5px}
        </style>
    </head>
    <body >
        <div style="text-align: center;">
            <div style="max-width: 580px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
                <div style="padding:24px 16px; border-top-left-radius:15px; border-top-right-radius:15px; text-align:center; background-color:#CBD640;">
                    <img src="https://dumbu.pro/follows/src/assets/images/logo.png"><br>
                    <h1 S>Email validation</h1>
                </div>
                <div style="padding:60px; background-color:#F5F8F6">
                    <div>
                        <p>Hello <strong><?php echo urldecode($_GET["username"]);?></strong>,</p>
                        <p>You have just taken the first step to register with our tool.</p>
                        <p>Your username in our system is: <strong><?php echo $_GET["instaname"]; ?></strong></p>
                        <p>To complete the validation process of your email, please use the following 4-digit code and continue with the signature:</p>
                        <h1><strong><?php echo $_GET["purchase_access_token"]; ?></strong></h1>
                        <p>
                            <br><br><i>Att. Dumbu team</i><br><br>
                        </p>
                    </div>
                </div>
                <div style="padding:24px 16px; border-bottom-left-radius:15px; border-bottom-right-radius:15px; text-align:center; background-color:#7E7D7D;">
                    <img src="https://dumbu.pro/follows/src/assets/images/logo-footer.png"><br>
                    <h5>DUMBU - <?php echo date("Y",time());?> - ALL RIGHTS RESERVED RESERVED</h5>
                </div>
            </div>
        </div>
    </body>
</html>


