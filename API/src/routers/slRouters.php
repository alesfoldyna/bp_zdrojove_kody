<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\UploadedFile;
use \Firebase\JWT\JWT;
use Tuupola\Base62;

$app->post('/token', function (Request $request, Response $response){
    $id_user = $request->getParam('id_user');
    $pass = $request->getParam('pass');
    try{
        $user_type = getUserType($id_user, $pass);

        if($user_type !== 'ADMIN' && $user_type !== 'PROFI') return $response->withStatus(401)->withJson(array('error'=> array('text'=> 'Uživatel nemá dostatečné oprávnění')));

        // generate token
        $now = new DateTime();
        $future = new DateTime("now +1 minutes");
        $server = $request->getServerParams();
        $jti = (new Base62)->encode(random_bytes(16));
        $payload = [
            "iat" => $now->getTimeStamp(),
            "exp" => $future->getTimeStamp(),
            "jti" => $jti
        ];
        $secret = "heslo pro profesionalniho uzivatele";
        $token = JWT::encode($payload, $secret, "HS256");
        $data["token"] = $token;
        $data["expires"] = $future->getTimeStamp();
        return $response->withStatus(201)->withJson($data);

    } catch (Exception $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});

$app->get('/my/{id_user}/invoices', function (Request $request, Response $response){
    $id_user = $request->getAttribute('id_user');
    $sql = 'SELECT * FROM public."faktura" WHERE id_user = '.$id_user;
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($data);
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }    
});
$app->get('/my/{id_user}/contracts', function (Request $request, Response $response){
    $id_user = $request->getAttribute('id_user');
    $sql = 'SELECT * FROM public."smlouva" WHERE id_user = '.$id_user;
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($data);
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }    
});