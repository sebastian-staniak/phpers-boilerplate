<?php

require_once __DIR__.'/../vendor/autoload.php';

use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\JsonResponse;

$users = new \Infrastructure\Neo4jUsers();

$app = new Silex\Application(["debug" => true]);

$app->post('/user', function (Request $request) use ($app, $users) {
    $data = json_decode($request->getContent(), true);

    $user = \Domain\User::fromScalars($data["firstname"], $data["lastname"]);
    $users->createUser($user);

    return new JsonResponse(["id" => $user->getUuid()->toString()], 201);
});

$app->delete('/user/{id}', function ($id) use ($app, $users) {
    $users->deleteUser(\Ramsey\Uuid\Uuid::fromString($id));

    return new JsonResponse(null, 201);
});

$app->run();