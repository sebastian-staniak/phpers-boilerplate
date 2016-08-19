<?php

namespace Application;

use Domain\User;
use Ramsey\Uuid\Uuid;

interface Users
{
    public function createUser(User $user);

    public function deleteUser(Uuid $id);

    public function matchAsFriends(User $user1, User $user2);

    public function findUser(Uuid $id);
}