<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests;

/**
 * Description of HeadersFactory
 *
 * @author renantaranto
 */
class JsonHeadersFactory
{
    static private $HEADER_AUTH_TOKEN = 'HTTP_X-AUTH-TOKEN';
    static private $HEADER_CONTENT_TYPE = 'CONTENT_TYPE';
    static private $HEADER_ACCEPT = 'HTTP_ACCEPT';
    
    static public function createHeaders(array $headers = null, $apiKey = null)
    {
        if (is_null($headers)) {
            $headers = array();
        }
        
        $contentTypeHeader = array(self::$HEADER_CONTENT_TYPE => 'application/json');
        $authTokenHeader = array(self::$HEADER_AUTH_TOKEN => $apiKey);
        $acceptHeader = array(self::$HEADER_ACCEPT => 'application/json');
        
        return array_merge($contentTypeHeader, $acceptHeader, $authTokenHeader, $headers);
    }
}
