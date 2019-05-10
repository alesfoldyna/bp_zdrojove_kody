<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Firebase\JWT\JWT;
use Tuupola\Base62;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * pro poslani tokenu emailem
 */
$app->post('/token', function (Request $request, Response $response){
    /**
     * is loggin success?
     */
    $email = $request->getParam('email');
    $sql = 'SELECT * FROM public."userContainer" WHERE email = \''.$email.'\'';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $DBdata = $stmt->fetch(PDO::FETCH_OBJ);
        
        if(empty($DBdata)) return $response->withStatus(500)->withJson(array('error'=> array('text'=> 'Účet není zaregistrován')));
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
    
    /**
     * make token
     */
    $now = new DateTime();
    $future = new DateTime("now +10 minutes");
    $server = $request->getServerParams();
    $jti = (new Base62)->encode(random_bytes(16));
    $payload = [
        "iat" => $now->getTimeStamp(),
        "exp" => $future->getTimeStamp(),
        "jti" => $jti
    ];
    $secret = "heslo pro resetovani hesla";
    $token = JWT::encode($payload, $secret, "HS256");
    
    //Create a new PHPMailer instance
    $mail = new PHPMailer;
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    $mail->isSendmail();
    //Set who the message is to be sent from
    $mail->setFrom('office@foldynatulbp.cloud', 'FOLDYNA TUL BP');
    //Set an alternative reply-to address
    $mail->addReplyTo('alesfoldyna@gmail.com', 'Aleš Foldyna');
    //Set who the message is to be sent to
    $mail->addAddress($email);
    //Set the subject line
    $mail->Subject = 'Resetování hesla';
    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    $mail->Body = 'Dobrý den,<br><br>pro resetování hesla prosím klikněte na odkaz <a href="https://admin.foldynatulbp.cloud/resetPass/'.$DBdata->id_user.'/token'.'/'.$token.'">zde</a>. Odkaz bude platný <b>10 minut</b><br><br>S pozdravem<br>Server Foldyna TUL BP';
    //Replace the plain text body with one created manually
    $mail->AltBody = 'Dobrý den,
    pro resetování hesla prosím klikněte na odkaz: https://admin.foldynatulbp.cloud/resetPass/'.$DBdata->id_user.'/token'.'/'.$token.'
        
    S pozdravem
    server FOLDYNA TUL BP';
    //send the message, check for errors
    if (!$mail->send()) {
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> 'email se nepodařilo odeslat')));
    } else {
        return $response->withStatus(201)->withJson(array('note'=> array('text'=> 'Na zadaný email vám byl poslán odkaz pro změnu hesla')));
    }

});
/**
 * pro vyžádání response tokenu
 */
$app->post('/change/pass/token', function (Request $request, Response $response){
    $id_user = $request->getParam('id_user');
    $pass = $request->getParam('pass');

    $sql = 'SELECT * FROM public."userContainer" WHERE id_user = '.$id_user.' AND pass = \''.$pass.'\'';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $DBdata = $stmt->fetch(PDO::FETCH_OBJ);
        
        if(empty($DBdata)) return $response->withStatus(401)->withJson(array('error'=> array('text'=> 'bad singin')));

    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
    /**
     * make token
     */
    $now = new DateTime();
    $future = new DateTime("now +2 minutes");
    $server = $request->getServerParams();
    $jti = (new Base62)->encode(random_bytes(16));
    $payload = [
        "iat" => $now->getTimeStamp(),
        "exp" => $future->getTimeStamp(),
        "jti" => $jti
    ];
    $secret = "heslo pro resetovani hesla";
    $token = JWT::encode($payload, $secret, "HS256");
    $data["token"] = $token;
    $data["expires"] = $future->getTimeStamp();
    return $response->withStatus(201)->withJson($data);
});

$app->put('/pass', function (Request $request, Response $response){
    $id_user = $request->getParam('id_user');
    $pass = $request->getParam('pass');

    $sql = 'UPDATE public."userContainer" SET pass= :pass WHERE id_user = :id_user';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':pass', $pass);
        $stmt->bindParam(':id_user', $id_user);

        $stmt->execute();

        return $response->withStatus(200)->withJson(array('note'=> array('text'=> 'Heslo změněno')));

    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});