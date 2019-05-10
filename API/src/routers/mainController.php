<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Firebase\JWT\JWT;
use Tuupola\Base62;

$app->options('/{routes:.+', function ($request,$response,$args){
    return $response; 
 });
 
 /**
  * CROS
  */
 $app->add(function($req,$res,$next){
    $response = $next($req,$res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type,Accept,Origin,Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

/**
* Verze 1
*/
$app->group('/v1',function() use ($app){
    // import open routers
    include 'openRouters.php';
    /**
     * FIRST LEVEL Routes
     */
    $app->group('/fl',function() use ($app){
        //import first secure level routers
        include 'flRouters.php';
    })->add(new Tuupola\Middleware\JwtAuthentication([
        "secure" => false,
        "path" => "/v1/fl",
        "secret" => "heslo pro prihlasene uzivatele",
        "ignore" => "/v1/fl/token",
        "error" => function ($response, $arguments) {
            $data["status"] = "error";
            $data["message"] = $arguments["message"];
            return $response->withJson($data);
        }
    ]));

    /**
     * SECOND LEVEL Routes
     */
    $app->group('/sl', function() use ($app){
        //import admin routers
        include 'slRouters.php';
    })->add(new Tuupola\Middleware\JwtAuthentication([
        "secure" => false,
        "path" => "/v1/sl",
        "secret" => "heslo pro profesionalniho uzivatele",
        "ignore" => "/v1/sl/token",
        "error" => function ($response, $arguments) {
            $data["status"] = "error";
            $data["message"] = $arguments["message"];
            return $response->withJson($data);
        }
    ]));

    /**
     * ADMIN Routes
     */
    $app->group('/admin', function() use ($app){
        //import admin routers
        include 'adminRouters.php';
    })->add(new Tuupola\Middleware\JwtAuthentication([
        "secure" => false,
        "path" => "/v1/admin",
        "secret" => "heslo pro administrátora",
        "ignore" => "/v1/admin/token",
        "error" => function ($response, $arguments) {
            $data["status"] = "error";
            $data["message"] = $arguments["message"];
            return $response->withJson($data);
        }
    ]));

    /**
     * RESET PASS
     */
    $app->group('/reset',function() use ($app){
        //import first secure level routers
        include 'resetRouter.php';
    })->add(new Tuupola\Middleware\JwtAuthentication([
        "secure" => false,
        "path" => "/v1/reset",
        "secret" => "heslo pro resetovani hesla",
        "ignore" => ["/v1/reset/token","/v1/reset/change/pass/token"],
        "error" => function ($response, $arguments) {
            $data["status"] = "error";
            $data["message"] = $arguments["message"];
            return $response->withJson($data);
        }
    ]));
});