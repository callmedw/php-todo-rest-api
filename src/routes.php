<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
  $container = $app->getContainer();

  $app->get('/', function (Request $request, Response $response, array $args) {

    $endpoints = [
      'all todos' => $this->api['api_url'].'/todos',
      'single todo' => $this->api['api_url'].'/todos/{todo_id}',
    ];
    $result = [
      'endpoints' => $endpoints,
      'version' => $this->api['version'],
      'timestamp' => time(),
    ];
    return $response->withJson($result, 200, JSON_PRETTY_PRINT);

  });

  $app->group('/api/v1/todos', function() use($app)  {

    $app->get('', function (Request $request, Response $response, array $args) {
      $result = $this->task->getTasks();
      return $response->withJson($result, 200, JSON_PRETTY_PRINT);
    });

    $app->get('/{todo_id}', function (Request $request, Response $response, array $args) {
      $result = $this->task->getTask($args['todo_id']);
      return $response->withJson($result, 200, JSON_PRETTY_PRINT);
    });

    $app->post('', function (Request $request, Response $response, array $args) {
      $result = $this->task->createTask($request->getParsedBody());
      return $response->withJson($result, 201, JSON_PRETTY_PRINT);
    });

    $app->put('/{todo_id}', function (Request $request, Response $response, array $args) {
      $data = $request->getParsedBody();
      $data['todo_id'] = $args['todo_id'];
      $result = $this->task->updateTask($data);
      return $response->withJson($result, 201, JSON_PRETTY_PRINT);
    });

    $app->delete('/{todo_id}', function (Request $request, Response $response, array $args) {
      error_log($args['id']);
      error_log($args['todo_id']);
      $result = $this->task->deleteTask($args['todo_id']);
      return $response->withJson($result, 200, JSON_PRETTY_PRINT);
    });
  });

    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function($req, $res) {
      $handler = $this->notFoundHandler;
      return $handler($req, $res);
    });
};
