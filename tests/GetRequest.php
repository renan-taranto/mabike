<?php
namespace Tests;

interface GetRequest
{
    public function get($uri, $headers);
    public function getStandardHeaders();
    public function getStandardHeadersWithAuthenticationforUser1();
    public function getStandardHeadersWithAuthenticationforUser2();
    public function getStandardHeadersWithAuthenticationforUser3();
}
