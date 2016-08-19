<?php
declare(strict_types=1);

namespace Domain;

use Ramsey\Uuid\Uuid;

class Post
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var Uuid
     */
    private $uuid;

    /**
     * Post constructor.
     * @param Uuid $uuid
     * @param string $content
     */
    public function __construct(Uuid $uuid, string $content)
    {
        $this->uuid = $uuid;
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return Uuid
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    

    /**
     * @param string $content
     * @param Uuid|null $uuid
     * @return Post
     */
    public static function fromScalar(string $content, Uuid $uuid = null) : Post
    {
        if ($uuid === null) {
            $uuid = Uuid::uuid1();
        }

        return new self($uuid, $content);
    }
}