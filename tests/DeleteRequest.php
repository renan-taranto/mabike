<?php
namespace Tests;

interface DeleteRequest
{
    public function delete($uri, $headers);
    public function getAuthenticationHeader();
}
