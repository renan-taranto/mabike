<?php
namespace Tests;

interface GetRequest
{
    public function get($uri, $headers);
    public function getStandardHeaders();
    public function getStandardHeadersWithAuthentication();
}
