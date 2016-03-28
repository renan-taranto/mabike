<?php
namespace Tests;

interface PutRequest
{
    public function put($uri, $headers, array $data);
    public function getStandardHeaders();
    public function getStandardHeadersWithAuthentication();
}
