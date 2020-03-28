<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* ----------------------------------------------------------------------------

 * @WebScheduler - Web Scheduler

 * @package     @WebScheduler

 * @author      SemeOnline(Pty)Ltd <info@semeonline.co.za>

 * @copyright   Copyright (c) 2019, SemeOnline (Pty)Ltd

 * @link        https://semeonline.co.za

 * @since       v1.8

 * ---------------------------------------------------------------------------- */


use \Aws\Sns\SnsClient;
use \Aws\Exception\AwsException;


/**

 * Payments Controller

 *

 * @package Controllers

 */


class Payments extends CI_Controller {

    /**

     * Class Constructor

     */

    public function __construct()

    {

        parent::__construct();

        $this->load->library('session');

    }

    public function payment_initiate()

    {        
        $this->load->model('payments_model');

        $payment_data = $this->input->post('payment');

        $signature_hash_data = $this->input->post('generate_payment_signature');

        $response = $this->payments_model->add($payment_data,$signature_hash_data );

        $this->output

            ->set_content_type('application/json')

            ->set_output(json_encode([

                'captcha_verification' => $signature_hash_data,
                'payment_id' => $response['payment_id'],
                'signature' => $response['signature']

            ]));

    }

    public function payment_notify()

    {
        $config['csrf_protection'] = false;

        // Notify PayFast that information has been received
        header( 'HTTP/1.0 200 OK' );
        flush();
        $this->load->model('payments_model');

        define( 'PAYFAST_SERVER', 'TEST' ); // Whether to use "sandbox" test server or live server
        define( 'USER_AGENT', 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)' ); // User Agent for cURL

        // Error Messages
        define( 'PF_ERR_AMOUNT_MISMATCH', 'Amount mismatch' );
        define( 'PF_ERR_BAD_SOURCE_IP', 'Bad source IP address' );
        define( 'PF_ERR_CONNECT_FAILED', 'Failed to connect to PayFast' );
        define( 'PF_ERR_BAD_ACCESS', 'Bad access of page' );
        define( 'PF_ERR_INVALID_SIGNATURE', 'Security signature mismatch' );
        define( 'PF_ERR_CURL_ERROR', 'An error occurred executing cURL' );
        define( 'PF_ERR_INVALID_DATA', 'The data received is invalid' );
        define( 'PF_ERR_UNKNOWN', 'Unknown error occurred' );

        // General Messages
        define( 'PF_MSG_OK', 'Payment was successful' );
        define( 'PF_MSG_FAILED', 'Payment has failed' );


        // Variable initialization
        $pfError = false;
        $pfErrMsg = '';
        $filename = 'notify_me.txt'; // DEBUG
        $output = ''; // DEBUG
        $pfParamString = '';
        $pfHost = ( PAYFAST_SERVER == 'LIVE' ) ? 'www.payfast.co.za' : 'sandbox.payfast.co.za';
        $pfData = array();
        $output = "ITN Response Received\n\n";

        //// Dump the submitted variables and calculate security signature
        if ( !$pfError )
        {
            $output .= "Posted Variables:\n"; // DEBUG
            
            // Strip any slashes in data
            foreach ( $_POST as $key => $val )
            {
                $pfData[$key] = stripslashes( $val );
                $output .= "$key = $val\n";
            }

            // Dump the submitted variables and calculate security signature
            foreach ( $pfData as $key => $val )
            {
                if ( $key != 'signature' )
                {
                    $pfParamString .= $key . '=' . urlencode( $val ) . '&';
                }

            }

            // Remove the last '&' from the parameter string
            $pfParamString = substr( $pfParamString, 0, -1 );

            $pfTempParamString = $pfParamString;

            $signature = md5( $pfTempParamString );

            $result = ( $_POST['signature'] == $signature );

            $output .= "\nSecurity Signature:\n"; // DEBUG
            $output .= "- posted     = " . $_POST['signature'] . "\n"; // DEBUG
            $output .= "- calculated = " . $signature . "\n"; // DEBUG
            $output .= "- result     = " . ( $result ? 'SUCCESS' : 'FAILURE' ) . "\n"; // DEBUG

            if( !$result ){
                $pfError =  true;
                $pfErrMsg = PF_ERR_INVALID_SIGNATURE;
            }
        }

        //// Verify source IP
        if ( !$pfError )
        {
            $validHosts = array(
                'www.payfast.co.za',
                'sandbox.payfast.co.za',
                'w1w.payfast.co.za',
                'w2w.payfast.co.za',
            );

            $validIps = array();

            foreach ( $validHosts as $pfHostname )
            {
                $ips = gethostbynamel( $pfHostname );

                if ( $ips !== false )
                {
                    $validIps = array_merge( $validIps, $ips );
                }
            }

            // Remove duplicates
            $validIps = array_unique( $validIps );

            if ( !in_array( $_SERVER['REMOTE_ADDR'], $validIps ) )
            {
                $pfError = true;
                $pfErrMsg = PF_ERR_BAD_SOURCE_IP;
            }
        }

        //// Connect to server to validate data received
        if ( !$pfError )
        {
            // Use cURL (If it's available)
            if ( function_exists( 'curl_init' ) )
            {
                $output .= "\nUsing cURL\n"; // DEBUG

                // Create default cURL object
                $ch = curl_init();

                // Base settings
                $curlOpts = array(
                    // Base options
                    CURLOPT_USERAGENT => USER_AGENT, // Set user agent
                    CURLOPT_RETURNTRANSFER => true, // Return output as string rather than outputting it
                    CURLOPT_HEADER => false, // Don't include header in output
                    CURLOPT_SSL_VERIFYHOST => 2,
                    CURLOPT_SSL_VERIFYPEER => 1,

                    // Standard settings
                    CURLOPT_URL => 'https://' . $pfHost . '/eng/query/validate',
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $pfParamString,
                );
                curl_setopt_array( $ch, $curlOpts );

                // Execute CURL
                $res = curl_exec( $ch );
                curl_close( $ch );

                if ( $res === false )
                {
                    $pfError = true;
                    $pfErrMsg = PF_ERR_CURL_ERROR;
                }
            }
            // Use fsockopen
            else
            {
                $output .= "\nUsing fsockopen\n"; // DEBUG

                // Construct Header
                $header = "POST /eng/query/validate HTTP/1.0\r\n";
                $header .= "Host: " . $pfHost . "\r\n";
                $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
                $header .= "Content-Length: " . strlen( $pfParamString ) . "\r\n\r\n";

                // Connect to server
                $socket = fsockopen( 'ssl://' . $pfHost, 443, $errno, $errstr, 10 );

                // Send command to server
                fputs( $socket, $header . $pfParamString );

                // Read the response from the server
                $res = '';
                $headerDone = false;

                while ( !feof( $socket ) )
                {
                    $line = fgets( $socket, 1024 );

                    // Check if we are finished reading the header yet
                    if ( strcmp( $line, "\r\n" ) == 0 )
                    {
                        // read the header
                        $headerDone = true;
                    }
                    // If header has been processed
                    else if ( $headerDone )
                    {
                        // Read the main response
                        $res .= $line;
                    }
                }
            }
        }

        //// Get data from server
        if ( !$pfError )
        {
            // Parse the returned data
            $lines = explode( "\n", $res );

            $output .= "\nValidate response from server:\n"; // DEBUG

            foreach ( $lines as $line ) // DEBUG
            {
                $output .= $line . "\n";
            }
            // DEBUG
        }

        //// Interpret the response from server
        if ( !$pfError )
        {
            // Get the response from PayFast (VALID or INVALID)
            $result = trim( $lines[0] );

            $output .= "\nResult = " . $result; // DEBUG

            // If the transaction was valid
            if ( strcmp( $result, 'VALID' ) == 0 )
            {
                // Update payment as complete

                $this->payments_model->update($_POST['m_payment_id'] );
                $this->send_sms($_POST['custom_str1'], $_POST['custom_str2']);
            }
            // If the transaction was NOT valid
            else
            {
                // Log for investigation
                $pfError = true;
                $pfErrMsg = PF_ERR_INVALID_DATA;
            }
        }

        // If an error occurred
        if ( $pfError )
        {
            $output .= "\n\nAn error occurred!";
            $output .= "\nError = " . $pfErrMsg;
        }

        //// Write output to file // DEBUG
        file_put_contents( $filename, $output ); // DEBUG
    }

    public function payment_cancel()

    {

        var_dump($_GET);
        die();

    }


    public function send_sms($phone_number, $appointment_date) {
        $SnSclient = new SnsClient([
            'profile' => 'default',
            'region' => 'us-east-1',
            'version' => 'latest',
            'credentials' => ['key' => 'AKIASIY2HWNAZVVL4Z5U', 'secret' => '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']
        ]);

        $message = 'You appointment has been booked for '. $appointment_date;
        $phone = '+27'.$phone_number;

        try {
            $SnSclient->publish([
                'Message' => $message,
                'PhoneNumber' => $phone,
                'MessageAttributes' => ['AWS.SNS.SMS.SenderID' => [
                    'DataType' => 'String',
                    'StringValue' => 'WebScheduler'
                ]]
            ]);
        } catch (AwsException $e) {
            // output error message if fails
            error_log($e->getMessage());
        }
    }
}