<?php
namespace Rtaranto\Application\Dto\Biker;

class BikerDTO
{
    private $name;
    private $email;
    
    /**
     * @param string $name
     * @param string $email
     */
    public function __construct($name = null, $email = null)
    {
        $this->name = $name;
        $this->email = $email;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
}
