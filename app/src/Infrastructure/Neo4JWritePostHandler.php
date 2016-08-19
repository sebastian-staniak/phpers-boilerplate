<?php
declare(strict_types = 1);

namespace Infrastructure;

use Application\WritePost;
use GraphAware\Neo4j\Client\ClientBuilder;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class Neo4JWritePostHandler
 */
class Neo4JWritePostHandler
{

    const EXCHANGE_NAME = "user_published_post";
    /**
     * @var \GraphAware\Neo4j\Client\Client
     */
    private $neo4j;

    /**
     * @var AMQPChannel
     */
    private $channel;

    /**
     * Neo4jUsers constructor.
     */
    public function __construct()
    {
        $this->neo4j = ClientBuilder::create()
            ->addConnection('default', 'http://neo4j.phpers.dev:7474')
            ->build();

        $exchange = self::EXCHANGE_NAME;
        $connection = new AMQPStreamConnection("rabbit.phpers.dev", "5672", "guest", "guest", "/");
        $this->channel = $connection->channel();

        $this->channel->exchange_declare($exchange, 'fanout', true, true, true);
    }

    /**
     * @param WritePost $command
     * @throws \GraphAware\Neo4j\Client\Exception\Neo4jException
     */
    public function handle(WritePost $command)
    {
        $query = "CREATE (post:Post {id: \"{$command->getPost()->getUuid()}\", content: \"{$command->getPost()->getContent()}\"})";
        $this->neo4j->run($query);

        $query = "MATCH (post:Post) WHERE (post.id = \"{$command->getPost()->getUuid()}\")" .
            "MATCH (user:Person) WHERE (user.id = \"{$command->getUser()->getUuid()->toString()}\") " .
            "CREATE (user)-[:WRITES]->(post)";
        $this->neo4j->run($query);

        $message = new AMQPMessage(json_encode(["userId" => $command->getUser()->getUuid()]));
        $this->channel->basic_publish($message, self::EXCHANGE_NAME);
    }

    /**
     * close connection.
     */
    public function __destruct()
    {
        $this->channel->close();
    }
}