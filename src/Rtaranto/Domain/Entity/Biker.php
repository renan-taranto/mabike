<?php
namespace Rtaranto\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\Exclude;

class Biker
{
    private $id;
    private $name;
    private $email;
    /**
     * @Exclude
     */
    private $motorcycles;
    /**
     * @Exclude
     */
    private $user;
    
    public function __construct($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
        $this->motorcycles = new ArrayCollection();
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

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }
    
    public function setUser(User $user)
    {
        $this->user = $user;
    }
    
    public function getMotorcycles()
    {
        return $this->motorcycles;
    }
    
    public function addMotorcycle(Motorcycle $motorcycle)
    {
        $motorcycle->setBiker($this);
        $this->motorcycles->add($motorcycle);
    }
}    
