<?php

use Slim\App;

return function (App $app) {
  $app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
  });

  $app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
      ->withHeader('Access-Control-Allow-Origin', '*' )
      ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
      ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
  });
};
