<?php
namespace Tests;

interface DeleteRequest
{
    public function delete($uri, $apiKey = null, array $headers = null);
}
