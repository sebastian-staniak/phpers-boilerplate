<?php
declare(strict_types=1);

namespace Infrastructure;

use Application\Skills;
use Domain\Skill;
use GraphAware\Neo4j\Client\ClientBuilder;

class Neo4jSkills implements Skills
{

    /**
     * @var \GraphAware\Neo4j\Client\Client
     */
    private $neo4j;

    /**
     * Neo4jUsers constructor.
     */
    public function __construct()
    {
        $this->neo4j = ClientBuilder::create()
            ->addConnection('default', 'http://neo4j.phpers.dev:7474')
            ->build();
    }

    /**
     * @param Skill $skill
     * @throws \GraphAware\Neo4j\Client\Exception\Neo4jException
     */
    public function createSkill(Skill $skill)
    {
        $query = "CREATE (n:Skill {name: \"{$skill->getName()}\"})";
        $this->neo4j->run($query);
    }

    /**
     * @param Skill $skill
     * @throws \GraphAware\Neo4j\Client\Exception\Neo4jException
     */
    public function deleteUser(Skill $skill)
    {
        $query = "MATCH (n:Skill) WHERE (n.name = \"{$skill->getName()}\") DELETE n";
        $this->neo4j->run($query);
    }

    /**
     * @param string $name
     * @return Skill
     * @throws \GraphAware\Neo4j\Client\Exception\Neo4jException
     */
    public function findSkill(string $name) : Skill
    {
        $query = "MATCH (n:Skill) WHERE (n.name = \"{$name}\") RETURN n LIMIT 1";
        $result = $this->neo4j->run($query);

        $record = $result->getRecord();

        if ($record === null) {
            return null;
        }

        $skill = Skill::fromScalars(
            $record->get('n')->value('name')
        );

        return $skill;
    }
}