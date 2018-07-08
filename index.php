<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
            require_once(__DIR__ . "/ses_email.php");
            // This below code is for testing the application only
            // 
            
            $default_from_email = "info@domain.com";
            $aws_key = "INSERT AWS KEY";
            $aws_secret = "INSERT AWS SECRET";
            $aws_region = "INSERT REGION";
            
            echo "<pre>"; // just for formatting
            
            $email_app = new ses_email($default_from_email, $aws_key, $aws_secret, $aws_region);
            
            $recipients = array("web-hgnte@mail-tester.com");   # go to mail-tester.com and grab your test email address. 
            
            $email_app->send_email($recipients, "AWS Subject", "body, this is to check that the DKIM settings are working", "RETURN EMAIL ADDRESS HERE");
            echo "<br /><br />Done<br />";
            
            echo "</pre>";
        ?>
    </body>
</html>
