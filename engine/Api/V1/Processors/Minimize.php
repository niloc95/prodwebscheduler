<?php

/* ----------------------------------------------------------------------------
 * @WebScheduler - Web Scheduler
 * @package     @WebScheduler
 * @author      SemeOnline(Pty)Ltd <info@semeonline.co.za>
 * @copyright   Copyright (c) 2019, SemeOnline (Pty)Ltd
 * @link        https://semeonline.co.za
 * @since       v1.0
 * ---------------------------------------------------------------------------- */


namespace WB\Engine\Api\V1\Processors;

/**
 * Minimize Processor
 *
 * This processor will check for the "fields" GET parameters and provide only the required fields in
 * every response entry. This might come in handy when the client needs specific information and not
 * the whole objects.
 *
 * Make sure that the response parameter is a sequential array and not a single entry by the time this
 * processor is executed.
 */
class Minimize implements ProcessorsInterface {
    /**
     * Process Response Array
     *
     * Example:
     *   http://caraconcepts.com/api/v1/appointments?fields=id,book,start,end
     *
     *
     * @param array &$response The response array to be processed.
     */
    public static function process(array &$response)
    {
        if ( ! isset($_GET['fields']) || empty($response))
        {
            return;
        }

        $fields = explode(',', $_GET['fields']);

        $temporaryResponse = [];

        foreach ($response as &$entry)
        {
            $temporaryEntry = [];

            foreach ($fields as $field)
            {
                $field = trim($field);
                if (isset($entry[$field]))
                {
                    $temporaryEntry[$field] = $entry[$field];
                }
            }

            $temporaryResponse[] = $temporaryEntry;
        }

        $response = $temporaryResponse;
    }
}
