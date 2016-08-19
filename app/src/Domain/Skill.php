<?php
declare(strict_types=1);

namespace Domain;

class Skill
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Skill
     */
    private $parent;

    /**
     * Skill constructor.
     * @param string $name
     * @param Skill $parent
     */
    private function __construct($name, Skill $parent = null)
    {
        $this->name = $name;
        $this->parent = $parent;
    }

    public static function fromScalars(string $name, Skill $parent = null)
    {
        if ($name === "") {
            throw new \DomainException("Name cannot be null");
        }
        
        return new self($name, $parent);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Skill
     */
    public function getParent()
    {
        return $this->parent;
    }
}