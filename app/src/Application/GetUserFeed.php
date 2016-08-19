<?php
declare(strict_types = 1);

namespace Application;
use Domain\User;

/**
 * Class GetUserFeed
 */
class GetUserFeed
{
    /**
     * @var User
     */
    private $user;

    /**
     * GetUserFeed constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}