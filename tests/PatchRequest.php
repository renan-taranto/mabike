<?php
namespace Tests;

interface PatchRequest
{
    public function patch($uri, array $bodyData = null, $apiKey = null, array $headers = null);
}
