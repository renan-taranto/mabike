<?php
namespace Rtaranto\Domain\Entity;

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
    
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    
}
