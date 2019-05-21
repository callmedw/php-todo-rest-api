<?php

use Slim\App;

return function (App $app) {
  $app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response->withHeader('Access-Control-Allow-Origin', '*');
  });
};
