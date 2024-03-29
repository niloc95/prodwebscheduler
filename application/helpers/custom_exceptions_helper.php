<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 * @WebScheduler - Web Scheduler
 * @package     @WebScheduler
 * @author      SemeOnline(Pty)Ltd <info@semeonline.co.za>
 * @copyright   Copyright (c) 2019, SemeOnline (Pty)Ltd
 * @link        https://semeonline.co.za
 * @since       v1.8
 * ---------------------------------------------------------------------------- */

/**
 * Database Exception Class
 */
class DatabaseException extends Exception {
}

/**
 * Validation Exception Class
 */
class ValidationException extends Exception {
}

/**
 * Notification Exception Class
 */
class NotificationException extends Exception {
}

/**
 * Sync Exception Class
 */
class SyncException extends Exception {
}

/**
 * Print an exception to an HTML text.
 *
 * This method is used to display exceptions in a way that is useful and easy
 * for the user to see. It uses the Bootstrap CSS accordion markup to display
 * the message and when the user clicks on it the exception trace will be revealed.
 * We display only one exceptions at a time because the user needs to be able
 * to display the details of each exception seperately. (In contrast with js).
 *
 * @param Exception $exc The exception to be displayed.
 * @return string Returns the html markup of the exception.
 */
function exceptionToHtml($exc)
{
    return
        '<div class="accordion" id="error-accordion">
            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse"
                            data-parent="#error-accordion" href="#error-technical">' .
        $exc->getMessage() . '
                    </a>
                </div>
                <div id="error-technical" class="accordion-body collapse">
                    <div class="accordion-inner">
                        <pre>' . $exc->getTraceAsString() . '</pre>
                    </div>
                </div>
            </div>
        </div>';
}

/**
 * Get a javascript object of a given exception.
 *
 * This method is pretty useful whenever we need to pass an exception object
 * to javascript during ajax calls.
 *
 * @param Exception $exception The given exception object.
 * @return string Returns the json encoded object of the exception.
 */
function exceptionToJavaScript($exception)
{
    return json_encode([
        'code' => $exception->getCode(),
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
        'message' => $exception->getMessage(),
        'previous' => $exception->getPrevious(),
        'trace' => $exception->getTraceAsString()
    ]);
}
