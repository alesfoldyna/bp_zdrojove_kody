<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/open/znacka',function(Request $request, Response $response){
    $sql = 'SELECT * FROM public."znacka" ORDER BY (nazev)';
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

// vsechny modely dane znacky
$app->get('/open/models/{id_znacka}',function(Request $request, Response $response){
    $id_znacka = $request->getAttribute('id_znacka');
    $sql = 'SELECT * FROM public."model" WHERE id_znacka =' .$id_znacka. ' ORDER BY (nazev)';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($data);
    } catch(PDOException $e){
        return $response->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});

// roky výbavy danemého modelu
$app->get('/open/model/years/{id_model}',function(Request $request, Response $response){
    $id_model = $request->getAttribute('id_model');
    $sql = 'SELECT vyrobeno_od, vyrobeno_do FROM public."model" WHERE id_model =' .$id_model;
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $data = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($data);
    } catch(PDOException $e){
        return $response->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});

// seznam vybavy daeho modelu
$app->get('/open/model/vybava/{id_model}',function(Request $request, Response $response){
    $id_model = $request->getAttribute('id_model');
    $sql = 'SELECT id_vybava FROM public."model" WHERE id_model =' .$id_model;
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $data = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($data);
    } catch(PDOException $e){
        return $response->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});

// vybrat vesekerou možnou vybavu
$app->get('/open/vybava',function(Request $request, Response $response){
    $sql = 'SELECT * FROM public."vybava"';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($data);
    } catch(PDOException $e){
        return $response->withJson(array('error'=> array('text'=> $e->getMessage())));
    }  
});

// vybrana vybava podle pole id - napr /api/gear/1,2,3
$app->get('/open/vybava/{id_vybava}',function(Request $request, Response $response){
    $id_vybava = $request->getAttribute('id_vybava');
    $sql = 'SELECT * FROM public."vybava" WHERE id_vybava IN ('. $id_vybava .')';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($data);
    } catch(PDOException $e){
        return $response->withJson(array('error'=> array('text'=> $e->getMessage())));
    }  
});

// vrati vsechny kategorie vybav
$app->get('/open/vybava/info/kategorie',function(Request $request, Response $response){
    $sql = 'SELECT DISTINCT kategorie FROM public."vybava"';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($data);
    } catch(PDOException $e){
        return $response->withJson(array('error'=> array('text'=> $e->getMessage())));
    }  
});

// dodatz na vysledky vyhledávání inzerátů
$app->post('/open/adv/content', function (Request $request, Response $response){
    
    $id_znacka = $request->getParam('id_znacka');
    $id_model = $request->getParam('id_model');
    $id_vybava = $request->getParam('id_vybava');
    $cena_od = $request->getParam('cena_od');
    $cena_do = $request->getParam('cena_do');
    $vyrobeno_od = $request->getParam('vyrobeno_od');
    $vyrobeno_do = $request->getParam('vyrobeno_do');
    $najeto_od = $request->getParam('najeto_od');
    $najeto_do = $request->getParam('najeto_do');

    /*
    var_dump($id_znacka);
    var_dump($id_model);
    var_dump($id_vybava);
    return;
    */
    $sql = prepareSqlForSearch($id_znacka, $id_model, $id_vybava, $cena_od, $cena_do, $vyrobeno_od, $vyrobeno_do, $najeto_od, $najeto_do);

    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        if($_SERVER['REMOTE_ADDR'] !== '127.0.0.1'){
            foreach($data as $key=>$value){
                $directory = DIRECTORY_SEPARATOR . 'var'. DIRECTORY_SEPARATOR . 'www' . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $data[$key]->fotogalerie;
                $data[$key]->imageUrl =  encodeImageToBase64($directory);
            }
        } else {
            foreach($data as $key=>$value){
                $data[$key]->imageUrl =  'fromLocalHost';
            }
        }
        return $response->withJson($data);
    } catch(PDOException $e){
        return $response->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
    
});
$app->get('/open/adv/details/{id_adver}', function (Request $request, Response $response){
    $id_adver = $request->getAttribute('id_adver');

    $sql = 'SELECT zn.nazev as znacka, mo.nazev as model, adv.* FROM inzeraty adv INNER JOIN znacka zn ON adv.id_znacka = zn.id_znacka INNER JOIN model mo ON adv.id_model = mo.id_model WHERE adv.id_adver = '.$id_adver;

    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $data = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($data);
    } catch(PDOException $e){
        return $response->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
    
});

$app->get('/open/adv/seller/{id_user}', function (Request $request, Response $response){
    $id_user = $request->getAttribute('id_user');
    $sql = 'SELECT email, jmeno, prijmeni, adresa, mesto, psc, telefon, is_po, user_type FROM public."userContainer" WHERE id_user = '.$id_user;
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $data = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        if ($data && $data->is_po){
            $sqlPO = 'SELECT ic, nazev as spolecnost, adresa as adresaPO, mesto as mestoPO, psc as pscPO, dic FROM public."pravnickeOsoby" WHERE id_user = '.$id_user;
            $db = new db();
            $db = $db->connect();
            $stmtPO = $db->query($sqlPO);
            $dataPO = $stmtPO->fetch(PDO::FETCH_OBJ);
            $data = (object)array_merge((array)$data, (array)$dataPO); 
        }
        $db = null;
        return $response->withJson($data);
    } catch(PDOException $e){
        return $response->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});

$app->post('/open/addNpUser', function (Request $request, Response $response){
    /**
     * get data
     */
    $email = $request->getParam('email');
    $pass = $request->getParam('pass');
    $jmeno = $request->getParam('jmeno');
    $prijmeni = $request->getParam('prijmeni');
    $adresa = $request->getParam('adresa');
    $mesto = $request->getParam('mesto');
    $psc = $request->getParam('psc');
    $telefon = $request->getParam('telefon');
    $is_po = $request->getParam('is_po');

    if($is_po){
        $ic = $request->getParam('ic');
        $po_nazev = $request->getParam('po_nazev');
        $po_adresa = $request->getParam('po_adresa');
        $po_mesto = $request->getParam('po_mesto');
        $po_psc = $request->getParam('po_psc');
        $po_dic = $request->getParam('po_dic');
    }

    $id_podminka = $request->getParam('id_podminka');

    /**
     * check uniqve email
     */
    $isMailOccupied = isMailInDB($email);
    if(!is_bool($isMailOccupied)) return $response->withStatus(500)->withJson(array('error'=> array('text'=> 'Chyba při zjišťování dostupnosti emailu'))); 

    if($isMailOccupied) return $response->withStatus(424)->withJson(array('error'=> array('text'=> 'Email je již používán')));

    $user_type = 'NOTPROFI';
    /**
     * insert into userContainer
     */

    $sql_UC = 'INSERT INTO public."userContainer" (email, pass, user_type, jmeno, prijmeni, adresa, mesto, psc, telefon, is_po) VALUES (:email, :pass, :user_type, :jmeno, :prijmeni, :adresa, :mesto, :psc, :telefon, :is_po)';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt_UC = $db->prepare($sql_UC);

        $stmt_UC->bindParam(':email', $email);
        $stmt_UC->bindParam(':pass', $pass);
        $stmt_UC->bindParam(':user_type', $user_type);
        $stmt_UC->bindParam(':jmeno', $jmeno);
        $stmt_UC->bindParam(':prijmeni', $prijmeni);
        $stmt_UC->bindParam(':adresa', $adresa);
        $stmt_UC->bindParam(':mesto', $mesto);
        $stmt_UC->bindParam(':psc', $psc);
        $stmt_UC->bindParam(':telefon', $telefon);
        $stmt_UC->bindParam(':is_po', $is_po);

        $stmt_UC->execute();

        $db = null;
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
    /**
     * get id of user
     */

    $sql_GID = 'SELECT id_user FROM public."userContainer" WHERE email =\''.$email.'\'';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql_GID);
        $id_user = $stmt->fetch(PDO::FETCH_OBJ);
        $id_user = intval($id_user->id_user);
        $db = null;
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }

    /**
     * insert into notProfessionalUser
     */

    $last_login = date("Y-m-d");


    $sql_NP = 'INSERT INTO public."notProfessionalUser"(id_user, id_podminka, last_login) VALUES (:id_user, :id_podminka, :last_login)';

    try{
        $db = new db();
        $db = $db->connect();
        $stmt_NP = $db->prepare($sql_NP);

        $stmt_NP->bindParam(':id_user', $id_user);
        $stmt_NP->bindParam(':id_podminka', $id_podminka);
        $stmt_NP->bindParam(':last_login', $last_login);

        $stmt_NP->execute();
        $db = null;
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }

    /**
     * insert into pravnickeOsoby
     */

    // return success response if isn't PO, else insert to DB pravnickeOsoby
 
    if(!$is_po){ 
        return $response->withJson(array('note'=> array('text'=> 'User success added')));
    } else {
        $sql_PO = 'INSERT INTO public."pravnickeOsoby"(
            ic, nazev, adresa, mesto, psc, dic, id_user)
            VALUES (:ic, :nazev, :adresa, :mesto, :psc, :dic, :id_user)';
        try{
            $db = new db();
            $db = $db->connect();
            $stmt_PO = $db->prepare($sql_PO);
    
            $stmt_PO->bindParam(':ic', $ic);
            $stmt_PO->bindParam(':nazev', $po_nazev);
            $stmt_PO->bindParam(':adresa', $po_adresa);
            $stmt_PO->bindParam(':mesto', $po_mesto);
            $stmt_PO->bindParam(':psc', $po_psc);
            $stmt_PO->bindParam(':dic', $po_dic);
            $stmt_PO->bindParam(':id_user', $id_user);
    
            $stmt_PO->execute();
            $db = null;
            return $response->withJson(array('note'=> array('text'=> 'User success added')));
        } catch(PDOException $e){
            return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
        }
    }
});

$app->get('/open/vseobecnePodminky/aktualni', function (Request $request, Response $response){

    $sql = 'SELECT * FROM public."vseobecnePodminky" WHERE platna = true ORDER BY platnost_od DESC LIMIT 1';

    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $data = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($data);
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
    
});

$app->get('/open/conditons/isValid/{id_podminka}', function (Request $request, Response $response){
    $id_podminka = $request->getAttribute('id_podminka');

    $sql = 'SELECT platna FROM public."vseobecnePodminky" WHERE id_podminka = '.$id_podminka;
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $data = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        return $response->withJson($data);
    } catch(PDOException $e){
        return $response->withJson(array('error'=> array('text'=> $e->getMessage())));
    }  
});

$app->get('/open/getImages/{id_adver}', function (Request $request, Response $response){
    $id_adver = $request->getAttribute('id_adver');
    $directory = DIRECTORY_SEPARATOR . 'var'. DIRECTORY_SEPARATOR . 'www' . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'photo' . DIRECTORY_SEPARATOR . $id_adver;
    $noPhotoDirectory = DIRECTORY_SEPARATOR . 'var'. DIRECTORY_SEPARATOR . 'www' . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'photo' . DIRECTORY_SEPARATOR .'non_photo.png';
    is_dir($directory) ? $files =  preg_grep('/^([^.])/',scandir($directory)) : $files = null;
    $images =  array();
    
    if(!is_null($files)){
        foreach($files as $file){
            array_push($images,$directory.DIRECTORY_SEPARATOR.$file);
        }
    }
    $imagesData = null;
    if(count($images) != 0){
        $imagesData = array();
        foreach($images as $image){
            $data = array();
            $data['dataUrl'] = encodeImageToBase64($image);
            array_push($imagesData,$data);
        }
    } else {
        $imagesData = array();
        $data['dataUrl'] = encodeImageToBase64($noPhotoDirectory);
        array_push($imagesData,$data);
    }
    return $response->withStatus(200)->withJson($imagesData);
});

$app->get('/open/server/time', function (Request $request, Response $response){
    $time = new DateTime();
    $data['time'] = $time->getTimeStamp();
    return $response->withStatus(200)->withJson($data);
});