<?php
declare(strict_types=1);

namespace Application;

use Domain\Skill;

interface Skills
{
    /**
     * @param Skill $skill
     */
    public function createSkill(Skill $skill);

    /**
     * @param Skill $skill
     */
    public function deleteUser(Skill $skill);

    /**
     * @param string $name
     * @return Skill
     */
    public function findSkill(string $name) : Skill;
}