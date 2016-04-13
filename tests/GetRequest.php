<?php
namespace Tests;

interface GetRequest
{
    public function get($uri, $apiKey = null, $headers = null);
}
