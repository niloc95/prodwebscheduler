<?php

/* ----------------------------------------------------------------------------
 * @WebScheduler - Web Scheduler
 * @package     @WebScheduler
 * @author      SemeOnline(Pty)Ltd <info@semeonline.co.za>
 * @copyright   Copyright (c) 2019, SemeOnline (Pty)Ltd
 * @link        https://semeonline.co.za
 * @since       v1.0
 * ---------------------------------------------------------------------------- */

namespace WB\Engine\Api\V1\Parsers;

/**
 * Services Parser
 *
 * This class will handle the encoding and decoding from the API requests.
 */
class Services implements ParsersInterface {
    /**
     * Encode Response Array
     *
     * @param array &$response The response to be encoded.
     */
    public function encode(array &$response)
    {
        $encodedResponse = [
            'id' => $response['id'] !== NULL ? (int)$response['id'] : NULL,
            'name' => $response['name'],
            'duration' => (int)$response['duration'],
            'price' => (float)$response['price'],
            'currency' => $response['currency'],
            'description' => $response['description'],
            'availabilitiesType' => $response['availabilities_type'],
            'attendantsNumber' => (int)$response['attendants_number'],
            'categoryId' => $response['id_service_categories'] !== NULL ? (int)$response['id_service_categories'] : NULL
        ];

        $response = $encodedResponse;
    }

    /**
     * Decode Request
     *
     * @param array &$request The request to be decoded.
     * @param array $base Optional (null), if provided it will be used as a base array.
     */
    public function decode(array &$request, array $base = NULL)
    {
        $decodedRequest = $base ?: [];

        if ( ! empty($request['id']))
        {
            $decodedRequest['id'] = $request['id'];
        }

        if ( ! empty($request['name']))
        {
            $decodedRequest['name'] = $request['name'];
        }

        if ( ! empty($request['duration']))
        {
            $decodedRequest['duration'] = $request['duration'];
        }

        if ( ! empty($request['price']))
        {
            $decodedRequest['price'] = $request['price'];
        }

        if ( ! empty($request['currency']))
        {
            $decodedRequest['currency'] = $request['currency'];
        }

        if ( ! empty($request['description']))
        {
            $decodedRequest['description'] = $request['description'];
        }

        if ( ! empty($request['availabilitiesType']))
        {
            $decodedRequest['availabilities_type'] = $request['availabilitiesType'];
        }

        if ( ! empty($request['attendantsNumber']))
        {
            $decodedRequest['attendants_number'] = $request['attendantsNumber'];
        }

        if ( ! empty($request['categoryId']))
        {
            $decodedRequest['id_service_categories'] = $request['categoryId'];
        }

        $request = $decodedRequest;
    }
}
