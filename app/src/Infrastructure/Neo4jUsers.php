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
        $query = "MATCH (n:Person) WHERE (n.id = \"{$id->toString()}\") DELETE n";
        $this->neo4j->run($query);
    }

    /**
     * @param User $user1
     * @param User $user2
     */
    public function matchAsFriends(User $user1, User $user2)
    {
        $query = "MATCH (user1:Person) WHERE (user1.id = \"{$user1->getUuid()->toString()}\")" .
            "MATCH (user2:Person) WHERE (user2.id = \"{$user2->getUuid()->toString()}\")" .
            "CREATE (user1)-[:KNOWS]->(user2)";
            "CREATE (user1)<-[:KNOWS]-(user2)";
        $this->neo4j->run($query);
    }

    /**
     * @param Uuid $id
     * @return User
     * @throws \GraphAware\Neo4j\Client\Exception\Neo4jException
     */
    public function findUser(Uuid $id) : User
    {
        $query = "MATCH (n:Person) WHERE (n.id = \"{$id->toString()}\") RETURN n LIMIT 1";
        $result = $this->neo4j->run($query);

        $record = $result->getRecord();
        $user = User::fromScalars(
            $record->get('n')->value('firstname'),
            $record->get('n')->value('lastname'),
            Uuid::fromString($record->get('n')->value('id'))
        );

        return $user;
    }
}