<?php

namespace Application;

use Domain\User;
use Ramsey\Uuid\Uuid;

interface Users
{
    public function createUser(User $user);

    public function deleteUser(Uuid $id);
}