<?php

namespace Infrastructure;

use Application\Users;
use Domain\User;
use GraphAware\Neo4j\Client\ClientBuilder;
use Ramsey\Uuid\Uuid;

class Neo4jUsers implements Users
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
     * @param User $user
     * @throws \GraphAware\Neo4j\Client\Exception\Neo4jException
     */
    public function createUser(User $user)
    {
        $query = "CREATE (n:Person {id: \"{$user->getUuid()->toString()}\", firstname: \"{$user->getFirstname()}\", lastname: \"{$user->getLastname()}\"})";
        $this->neo4j->run($query);
    }

    /**
     * @param Uuid $id
     * @throws \GraphAware\Neo4j\Client\Exception\Neo4jException
     */
    public function deleteUser(Uuid $id)
    {
        $query = "MATCH (n:Person {id =  \"{$id->toString()}\"}) DELETE n";
        $this->neo4j->run($query);
    }
}