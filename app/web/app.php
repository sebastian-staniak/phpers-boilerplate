<?php

require_once __DIR__.'/../vendor/autoload.php';

use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\JsonResponse;

$users = new \Infrastructure\Neo4jUsers();
$skills = new \Infrastructure\Neo4jSkills();

$app = new Silex\Application(["debug" => true]);

$app->post('/user', function (Request $request) use ($app, $users) {
    $data = json_decode($request->getContent(), true);

    $user = \Domain\User::fromScalars($data["firstname"], $data["lastname"]);
    $users->createUser($user);

    return new JsonResponse(["id" => $user->getUuid()->toString()], 201);
});

$app->delete('/user/{id}', function ($id) use ($app, $users) {
    $users->deleteUser(\Ramsey\Uuid\Uuid::fromString($id));

    return new JsonResponse(["deleted"], 200);
});

$app->post('/skill', function (Request $request) use ($app, $skills) {
    $data = json_decode($request->getContent(), true);

    $skill = \Domain\Skill::fromScalar($data["skillName"]);
    $skills->createSkill($skill);

    return new JsonResponse(["name" => $skill->getName()], 201);
});

$app->post('/user/{id}/friends', function ($id, Request $request) use ($app, $users) {
    $data = json_decode($request->getContent(), true);

    $user1 = $users->findUser(\Ramsey\Uuid\Uuid::fromString($id));
    $user2 = $users->findUser(\Ramsey\Uuid\Uuid::fromString($data["userId"]));
    $users->matchAsFriends($user1, $user2);

    return new JsonResponse(["id" => $user1->getUuid()->toString()], 201);
});

$app->post('/user/{id}/skills', function ($id, Request $request) use ($app, $users, $skills) {
    $data = json_decode($request->getContent(), true);

    $user1 = $users->findUser(\Ramsey\Uuid\Uuid::fromString($id));
    $skill = $skills->findSkill($data["skillName"]);
    $users->addSkillToUser($user1, $skill);

    return new JsonResponse(["id" => $user1->getUuid()->toString()], 201);
});


$app->run();