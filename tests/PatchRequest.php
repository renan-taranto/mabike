<?php
namespace Tests;

interface PatchRequest
{
    public function patch($uri, array $data = null, $apiKey = null, array $headers = null);
}
