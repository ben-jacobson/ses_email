<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once(__DIR__ . '/vendor/autoload.php');

use Aws\Ses\SesClient;

class ses_email
{
private $ses_client;

// email default settings
public $default_charset;
public $default_from_email; 
    
    public function __construct($default_from_email, $aws_key, $aws_secret, $aws_region)
    {     
        $this->default_from_email = $default_from_email;
        
        $ses_profile_details = array(
                'key'       => $aws_key,
                'secret'    => $aws_secret,
                'region'    => $aws_region
        );
 
        $this->ses_client = SesClient::factory($ses_profile_details);
        //echo "set up factory<br />";
        
        $this->set_email_defaults(); // this function takes a whole bunch of arguments and has default values. we want to run this to set basic defaults, but it can be run again by the user to change the settings later.
    }
    
    public function set_email_defaults($default_charset = "UTF-8")
    {
        $this->default_charset = $default_charset;
    }
    
    public function send_email($to_emails, $subject, $message, $reply_to = null) // to emails must be array
    {
        
        $to_emails = array_filter($to_emails); // strip any empty values from the array
        //print_r($to_emails);

        
        $message_destination = array(
            "ToAddresses"   => $to_emails, // this needs to be an array
            //"CcAddresses"   => array(),    // we don't use this for such a simple function, can be adapted later. Same as above, accepts arrays
            //"BccAddresses"  => array()     // we don't use this for such a simple function, can be adapted later. Same as above, accepts arrays
        );
        
        $message_data = array(
            "Subject"   => array(
                "Data"      => $subject,
                "Charset"   => $this->default_charset,                                
            ),
            "Body"      => array(
                "Text"      => array(
                    "Data"      => $message,
                    "Charset"   => $this->default_charset
                ),
                "Html"  => array(
                    "Data"      => $message,
                    "Charset"   => $this->default_charset                
                )
            )  
        );
        
        $email_data = array(
           "Source"             => $this->default_from_email,
           "Destination"        => $message_destination, 
           "Message"            => $message_data,
           "ReplyToAddresses"   => array($reply_to) // could also be an array
           //"ReturnPath"         => null, 
           //"SourceArn"          => null, // we do not have ARN activated for these messages
           //"ReturnPathArn"      => null  
        );
          
        //print_r($email_data);
        
        // Now send the email
            try
            {
                $result = $this->ses_client->sendEmail($email_data);
                return($result->get('MessageId'));
            }
            catch(Exception $e)
            {
                echo($e->getMessage()); 
                return -1;
            }  
    }
    
    public function __destruct()
    {
        
    }
}