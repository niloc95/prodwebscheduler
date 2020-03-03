<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Add custom values by settings them to the $config array.
// Example: $config['smtp_host'] = 'smtp.gmail.com';
// @link https://codeigniter.com/user_guide/libraries/email.html


// <Working Version> both notifications to Doctor and Customer
//$config['useragent'] = '@WebScheduler';
//$config['protocol'] = 'SMTP'; // or 'smtp'
//$config['mailtype'] = 'html'; // or 'text'
//$config['smtp_host'] = 'smtp.gmail.com';
//$config['smtp_user'] = 'nilo.cara@gmail.com';
//$config['smtp_pass'] = '!Shinesun12';
//$config['smtp_crypto'] = 'tls'; // or 'tls'
//$config['smtp_port'] = 587;

// <Working Version> both notifications to Doctor and Customer
//$config['useragent'] = '@WebScheduler';
$config['protocol'] = 'SMTP'; // or 'smtp'
$config['mailtype'] = 'html'; // or 'text'
$config['smtp_host'] = 'webscheduler.co.za';
//$config['smtp_user'] = 'bookings@webscheduler.co.za';
//$config['smtp_pass'] = 'LSxFKGX!x5hW2JNeB5!f!kp@7i';
$config['smtp_crypto'] = 'ssl'; // or 'ssl465'
$config['smtp_port'] = 465;



//<Working Version> Customer response only no notification to Doctor
//$config['useragent'] = '@WebScheduler';
//$config['protocol'] = 'smtp'; // or 'smtp'
//$config['mailtype'] = 'html/text/plain'; // or 'text'
//$config['smtp_host'] = 'smtp.sendgrid.net';
//$config['smtp_user'] = 'nilesh.cara@semeonline.co.za';
//$config['smtp_pass'] = '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';
//$config['smtp_crypto'] = 'ssl'; // or 'tls'
//$config['smtp_port'] = 465;
