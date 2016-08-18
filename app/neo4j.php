<?php
require 'vendor/autoload.php';

use GraphAware\Neo4j\Client\ClientBuilder;

//web interface: http://192.168.99.100:7474 neo4j/neo4j
$client = ClientBuilder::create()
    ->addConnection('default', 'http://192.168.99.100:7474')
    ->build();

$client->run("CREATE (n:Person)");
$client->run("CREATE (n:Person) SET n += {infos}", ['infos' => ['name' => 'Ales', 'age' => 34]]);
$result = $client->run("MATCH (n:Person) RETURN n");

echo '<pre>';
var_dump($result);
echo '</pre>';

die('-- END --');