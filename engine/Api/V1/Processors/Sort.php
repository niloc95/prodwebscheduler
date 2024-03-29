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
 * Sort Processor
 *
 * This class will sort the response array with the provided GET parameters. Make sure that the
 * response parameter is a sequential array and not a single entry by the time this processor is
 * executed.
 */
class Sort implements ProcessorsInterface {
    /**
     * Supports up to 3 columns for sorting.
     *
     * Example:
     *   http://WebScheduler.com/api/v1/appointments?sort=-start,+notes,-hash
     *
     * @param array &$response The response array to be processed.
     */
    public static function process(array &$response)
    {
        if ( ! isset($_GET['sort']) || empty($response))
        {
            return;
        }

        $sort = explode(',', (string)$_GET['sort']);

        $sortDirection1 = substr($sort[0], 0, 1) === '-' ? SORT_DESC : SORT_ASC;

        if (isset($sort[1]))
        {
            $sortDirection2 = substr($sort[1], 0, 1) === '-' ? SORT_DESC : SORT_ASC;
        }
        else
        {
            $sortDirection2 = NULL;
        }

        if (isset($sort[2]))
        {
            $sortDirection3 = substr($sort[2], 0, 1) === '-' ? SORT_DESC : SORT_ASC;
        }
        else
        {
            $sortDirection3 = NULL;
        }

        foreach ($response as $index => $entry)
        {
            $sortOrder1[$index] = $entry[substr($sort[0], 1)];

            if ($sortDirection2)
            {
                $sortOrder2[$index] = $entry[substr($sort[1], 1)];
            }

            if ($sortDirection3)
            {
                $sortOrder3[$index] = $entry[substr($sort[2], 1)];
            }
        }

        $arguments = [
            &$sortOrder1,
            &$sortDirection1
        ];

        if ($sortDirection2)
        {
            $arguments[] = $sortOrder2;
            $arguments[] = $sortDirection2;
        }

        if ($sortDirection3)
        {
            $arguments[] = $sortOrder3;
            $arguments[] = $sortDirection3;
        }

        $arguments[] = &$response;

        call_user_func_array('array_multisort', $arguments);
    }
}
