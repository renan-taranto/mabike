<?php
namespace Tests;

interface PostRequest
{
    public function post($uri, $headers, array $data);
}
