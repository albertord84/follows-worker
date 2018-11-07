<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>DUMBU - User contact</title>
        <style>
            p{font-size:16px; text-align:justify; margin-top:5px}
        </style>
    </head>
    <body>
        <div style="text-align: center;">
            <div style="max-width: 580px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
                <div style="padding:24px 16px; border-top-left-radius:15px; border-top-right-radius:15px; text-align:center; background-color:#ef00ef;">
                    <img src="https://dumbu.one/follows/src/assets/images/logo.png"><br>
                    <h1>User contact!</h1>
                </div>
                <div style="padding:60px; background-color:#F5F8F6">
                    <div>
                        <p>The user <strong><?php echo urldecode($_GET["username"]); ?></strong> send you the following message:</p>
                        <p style="font-style: italic; font-size: 13px;">"<?php echo urldecode($_GET["usermsg"]); ?>"</p>
                        <br>
                        <p>User personal information:</p>
                        <p>Email -> <strong><?php echo urldecode($_GET["useremail"]); ?></strong></p>
                        <p>Company -> <strong><?php echo urldecode($_GET["usercompany"]); ?></strong></p>
                        <p>Phone -> <strong><?php echo urldecode($_GET["userphone"]); ?></strong></p>
                    </div>
                </div>
                <div style="padding:24px 16px; border-bottom-left-radius:15px; border-bottom-right-radius:15px; text-align:center; background-color:#7E7D7D;">
                    <img src="https://dumbu.one/follows/src/assets/images/logo-footer.png"><br>
                    <h5>DUMBU - <?php echo date("Y", time()); ?> - ALL RIGHTS RESERVED</h5>
                </div>
            </div>
        </div>
    </body>
</html>


