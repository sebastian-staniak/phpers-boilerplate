<?php
declare(strict_types = 1);

require_once __DIR__.'/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$exchange = \Infrastructure\Neo4JWritePostHandler::EXCHANGE_NAME;
$queue = 'refresh_users_feed';
$consumerTag = 'consumer' . getmypid();

$connection = new AMQPStreamConnection("rabbit.phpers.dev", "5672", "guest", "guest", "/");
$channel = $connection->channel();

$channel->queue_declare($queue, false, true, false, false);
$channel->exchange_declare($exchange, 'fanout', true, true, true);


$channel->queue_bind($queue, $exchange);

/**
 * @param \PhpAmqpLib\Message\AMQPMessage $message
 */
function process_message($message)
{
    echo $message->body;
    $users = new \Infrastructure\Neo4jUsers();
    $userId = json_decode($message->body, true)["userId"];
    $user = $users->findUser(\Ramsey\Uuid\Uuid::fromString($userId));
    $friends = $users->getFriends($user);

    $redis = new \Redis();

    $redis->connect('redis.phpers.dev', 6379);
    foreach ($friends as $friend) {
        $feed = $redis->get("user_feed_{$friend->getUuid()->toString()}");
        $feed .= json_decode($message->body, true)["content"];
        $redis->set("user_feed_{$friend->getUuid()->toString()}", $feed);
        echo "User with id {$friend->getUuid()->toString()} has been updated \n";
    }
    $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
}

$channel->basic_consume($queue, $consumerTag, false, false, false, false, 'process_message');

/**
 * @param \PhpAmqpLib\Channel\AMQPChannel $channel
 * @param \PhpAmqpLib\Connection\AbstractConnection $connection
 */
function shutdown($channel, $connection)
{
    $channel->close();
    $connection->close();
}

register_shutdown_function('shutdown', $channel, $connection);

// Loop as long as the channel has callbacks registered
while (count($channel->callbacks)) {
    $channel->wait();
}