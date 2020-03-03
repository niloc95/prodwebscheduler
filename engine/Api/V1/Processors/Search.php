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
 * Search Processor
 *
 * This class will search the response with the "q" GET parameter and only provide the entries
 * that match the keyword. Make sure that the response parameter is a sequential array and not
 * a single entry by the time this processor is executed.
 */
class Search implements ProcessorsInterface {
    /**
     * Process Response Array
     *
     * @param array &$response The response array to be processed.
     */
    public static function process(array &$response)
    {
        if ( ! isset($_GET['q']) || empty($response))
        {
            return;
        }

        $searchedResponse = [];
        $keyword = (string)$_GET['q'];

        foreach ($response as $entry)
        {
            if (self::_recursiveArraySearch($entry, $keyword) !== FALSE)
            {
                $searchedResponse[] = $entry;
            }
        }

        $response = $searchedResponse;
    }

    /**
     * Recursive Array Search
     *
     * @param array $haystack Array to search in.
     * @param string $needle Keyword to be searched.
     *
     * @return int|bool Returns the index of the search occurrence or false it nothing was found.
     */
    protected static function _recursiveArraySearch(array $haystack, $needle)
    {
        foreach ($haystack as $key => $value)
        {
            $currentKey = $key;

            if (stripos($value, $needle) !== FALSE || (is_array($value) && self::_recursiveArraySearch($value,
                        $needle) !== FALSE))
            {
                return $currentKey;
            }
        }

        return FALSE;
    }
}
