<?php
namespace Domain\Entity;

class Biker
{
    private $id;
    private $name;
    private $email;
    
    public function __construct($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
    }
}
