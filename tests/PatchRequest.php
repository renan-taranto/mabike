<?php
namespace Tests;

interface PatchRequest
{
    public function patch($uri, $headers, array $data);
}
