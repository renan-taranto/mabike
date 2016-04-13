<?php
namespace Tests;

interface PostRequest
{
    public function post($uri, array $data = null, $apiKey = null, array $headers = null);
}
