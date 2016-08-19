<?php
declare(strict_types=1);

namespace Domain;

use Ramsey\Uuid\Uuid;

class User
{
    /** 
     * @var Uuid  
     */
    private $uuid;

    /**
     * @var string
     */
    private $firstname;

    /**
     * @var string
     */
    private $lastname;

    /**
     * User constructor.
     * @param string $firstname
     * @param string $lastname
     */
    private function __construct($firstname, $lastname)
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->uuid = Uuid::uuid1();
    }


    /**
     * @param string $firstname
     * @param string $lastname
     * @return User
     */
    public static function fromScalars(string $firstname, string $lastname) : User
    {
        if ($firstname === "") {
            throw new \DomainException("Firstname cannot be empty");
        }
        
        if ($lastname === "") {
            throw new \DomainException("Firstname cannot be empty");
        }
        
        return new self($firstname, $lastname);
    }
    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @return Uuid
     */
    public function getUuid()
    {
        return $this->uuid;
    }
}