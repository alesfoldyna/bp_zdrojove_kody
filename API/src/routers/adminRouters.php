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
        if(getUserType($id_user, $pass) !== 'ADMIN') return $response->withStatus(401)->withJson(array('error'=> array('text'=> 'Uživatel nemá dostatečné oprávnění')));

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
        $secret = "heslo pro administrátora";
        $token = JWT::encode($payload, $secret, "HS256");
        $data["token"] = $token;
        $data["expires"] = $future->getTimeStamp();
        return $response->withStatus(201)->withJson($data);

    } catch (Exception $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});

/**
 * USERS
 */
//get all users
$app->get('/users', function (Request $request, Response $response){
    $sql = 'SELECT id_user, jmeno, prijmeni, email, telefon, user_type FROM public."userContainer"';
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
//get contrete users
$app->get('/user/{id_user}', function (Request $request, Response $response){
    $id_user = $request->getAttribute('id_user');
    /**
     * user container
     */
    $sql_userContainer = 'SELECT * FROM public."userContainer" WHERE id_user = '.$id_user;
    try{
        $db = new db();
        $db = $db->connect();
        $stmt_userContainer = $db->query($sql_userContainer);
        $data = $stmt_userContainer->fetch(PDO::FETCH_OBJ);
        $db = null;
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    } 
    
    /**
     * pravnicke osoby
     */
    if($data->is_po){
        $sql_PO = 'SELECT ic as po_ic, nazev as po_nazev, adresa as po_adresa, mesto as po_mesto, psc as po_psc, dic as po_dic FROM public."pravnickeOsoby" WHERE id_user = '.$id_user;
        try{
            $db = new db();
            $db = $db->connect();
            $stmt_PO = $db->query($sql_PO);
            $data_PO = $stmt_PO->fetch(PDO::FETCH_OBJ);
            $data = (object)array_merge((array)$data, (array)$data_PO); 
            $db = null;
        } catch(PDOException $e){
            return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
        } 
    }

    /**
     * Type of user
     */
    switch($data->user_type){
        case 'ADMIN':
            return $response->withJson($data);
        case 'PROFI':
            try{
                return $response->withJson(prepareResponseForProfi($data));
            } catch (Exception $e){
                return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
            }
        case 'NOTPROFI':
            try{
                return $response->withJson(prepareResponseForNotProfi($data));
            } catch (Exception $e){
                return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
            }
        default:
            return $response->withStatus(500)->withJson(array('error'=> array('text'=> 'SWITCH(user_type); default')));
    }

});
//Add PROFI or ADMIN user
$app->post('/user/add', function (Request $request, Response $response){
    /**
     * GET PARAM
     */
    $email = $request->getParam('email');
    $pass = $request->getParam('pass');
    $user_type = $request->getParam('user_type');
    $jmeno = $request->getParam('jmeno');
    $prijmeni = $request->getParam('prijmeni');
    $adresa = $request->getParam('adresa');
    $mesto = $request->getParam('mesto');
    $psc = $request->getParam('psc');
    $telefon = $request->getParam('telefon');
    $is_po = $request->getParam('is_po');
    // if is PO get param for PO
    if($is_po){
        $po_ic = $request->getParam('po_ic');
        $po_nazev = $request->getParam('po_nazev');
        $po_adresa = $request->getParam('po_adresa');
        $po_mesto = $request->getParam('po_mesto');
        $po_psc = $request->getParam('po_psc');
        $po_dic = $request->getParam('po_dic');
    }
    //if is PU get param for PU
    if($user_type === 'PROFI') $count_limit = $request->getParam('count_limit');

    /**
     * ADD to userContainer
     */
    $sql_userContainer = 'INSERT INTO public."userContainer"(
        email, pass, user_type, jmeno, prijmeni, adresa, mesto, psc, telefon, is_po)
        VALUES (:email, :pass, :user_type, :jmeno, :prijmeni, :adresa, :mesto, :psc, :telefon, :is_po)';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt_userContainer = $db->prepare($sql_userContainer);

        $stmt_userContainer->bindParam(':email', $email);
        $stmt_userContainer->bindParam(':pass', $pass);
        $stmt_userContainer->bindParam(':user_type', $user_type);
        $stmt_userContainer->bindParam(':jmeno', $jmeno);
        $stmt_userContainer->bindParam(':prijmeni', $prijmeni);
        $stmt_userContainer->bindParam(':adresa', $adresa);
        $stmt_userContainer->bindParam(':mesto', $mesto);
        $stmt_userContainer->bindParam(':psc', $psc);
        $stmt_userContainer->bindParam(':telefon', $telefon);
        $stmt_userContainer->bindParam(':is_po', $is_po);

        $stmt_userContainer->execute();

        $db = null;
    } catch (PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    } catch (Exception $e){
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
     * insert into pravnickeOsoby
     */
    if($is_po){
        $sql_PO = 'INSERT INTO public."pravnickeOsoby"(
            ic, nazev, adresa, mesto, psc, dic, id_user)
            VALUES (:ic, :nazev, :adresa, :mesto, :psc, :dic, :id_user)';
        try{
            $db = new db();
            $db = $db->connect();
            $stmt_PO = $db->prepare($sql_PO);
    
            $stmt_PO->bindParam(':ic', $po_ic);
            $stmt_PO->bindParam(':nazev', $po_nazev);
            $stmt_PO->bindParam(':adresa', $po_adresa);
            $stmt_PO->bindParam(':mesto', $po_mesto);
            $stmt_PO->bindParam(':psc', $po_psc);
            $stmt_PO->bindParam(':dic', $po_dic);
            $stmt_PO->bindParam(':id_user', $id_user);
    
            $stmt_PO->execute();

            $db = null;
        } catch(PDOException $e){
            return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
        }
    }

    /**
     * insert into professionalUser
     */
    if($user_type === 'PROFI'){
        $sql_PU = 'INSERT INTO public."professionalUser"(
            count_limit, id_user)
            VALUES (:count_limit, :id_user)';
        try{
            $db = new db();
            $db = $db->connect();
            $stmt_PU = $db->prepare($sql_PU);
    
            $stmt_PU->bindParam(':count_limit', $count_limit);
            $stmt_PU->bindParam(':id_user', $id_user);
    
            $stmt_PU->execute();

            $db = null;
        } catch(PDOException $e){
            return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
        }
    }

    // return success response
    return $response->withStatus(200)->withJson(array('note'=> array('text'=> 'User success added')));

});
//get user for edit
$app->get('/user/forEdit/{id_user}', function (Request $request, Response $response){
    $id_user = $request->getAttribute('id_user');

    $sql = 'SELECT id_user, email, user_type, jmeno, prijmeni, adresa, mesto, psc, telefon, is_po FROM public."userContainer" WHERE id_user = '.$id_user;
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $data = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }

    if($data->is_po){
        $sql_PO = 'SELECT ic as po_ic, nazev as po_nazev, adresa as po_adresa, mesto as po_mesto, psc as po_psc, dic as po_dic FROM public."pravnickeOsoby" WHERE id_user = '.$id_user;
        try{
            $db = new db();
            $db = $db->connect();
            $stmt_PO = $db->query($sql_PO);
            $data_PO = $stmt_PO->fetch(PDO::FETCH_OBJ);
            $data = (object)array_merge((array)$data, (array)$data_PO); 
            $db = null;
        } catch(PDOException $e){
            return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
        } 
    }

    if($data->user_type == 'PROFI'){
        $sql_professionalUser = 'SELECT count_limit FROM public."professionalUser" WHERE id_user = '.$id_user; 
        try{
            $db = new db();
            $db = $db->connect();
            $stmt_professionalUser = $db->query($sql_professionalUser);
            $data_professionalUser = $stmt_professionalUser->fetch(PDO::FETCH_OBJ);
            $data = (object)array_merge((array)$data, (array)$data_professionalUser); 
            $db = null;
        } catch(PDOException $e){
            throw new Exception('Exception; function: prepareResponseForProfi; PDOException: '.$e.getMessage());
        } 
    }

    return $response->withStatus(200)->withJson($data);
});
//edit PROFI or ADMIN user
$app->put('/user/edit/{id_user}', function (Request $request, Response $response){
    $id_user = $request->getAttribute('id_user');

    $email = $request->getParam('email');
    $user_type = $request->getParam('user_type');
    $jmeno = $request->getParam('jmeno');
    $prijmeni = $request->getParam('prijmeni');
    $adresa = $request->getParam('adresa');
    $mesto = $request->getParam('mesto');
    $psc = $request->getParam('psc');
    $telefon = $request->getParam('telefon');
    $is_po = $request->getParam('is_po');
    // if is PO get param for PO
    if($is_po){
        $po_ic = $request->getParam('po_ic');
        $po_nazev = $request->getParam('po_nazev');
        $po_adresa = $request->getParam('po_adresa');
        $po_mesto = $request->getParam('po_mesto');
        $po_psc = $request->getParam('po_psc');
        $po_dic = $request->getParam('po_dic');
    }
    //if is PU get param for PU
    if($user_type === 'PROFI') $count_limit = $request->getParam('count_limit');

    $beforeIsPO = isUserPo($id_user);

    /**
     * edit user
     */
    $sql_EUC = 'UPDATE public."userContainer"
    SET email=:email, jmeno=:jmeno, prijmeni=:prijmeni, adresa=:adresa, mesto=:mesto, psc=:psc, telefon=:telefon, is_po=:is_po
    WHERE id_user = '.$id_user;
    try{
        $db = new db();
        $db = $db->connect();
        $stmt_EUC = $db->prepare($sql_EUC);
        
        $stmt_EUC->bindParam(':email', $email);
        $stmt_EUC->bindParam(':jmeno', $jmeno);
        $stmt_EUC->bindParam(':prijmeni', $prijmeni);
        $stmt_EUC->bindParam(':adresa', $adresa);
        $stmt_EUC->bindParam(':mesto', $mesto);
        $stmt_EUC->bindParam(':psc', $psc);
        $stmt_EUC->bindParam(':telefon', $telefon);
        $stmt_EUC->bindParam(':is_po', $is_po);
        
        $stmt_EUC->execute();
        
        $db = null;
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }

    /**
     * edit PO
     */
    if($beforeIsPO && $is_po){
        $sql_EPO = 'UPDATE public."pravnickeOsoby"
        SET ic=:ic, nazev=:nazev, adresa=:adresa, mesto=:mesto, psc=:psc, dic=:dic
        WHERE id_user = '.$id_user;
        try{
            $db = new db();
            $db = $db->connect();
            $stmt_EPO = $db->prepare($sql_EPO);
    
            $stmt_EPO->bindParam(':ic', $po_ic);
            $stmt_EPO->bindParam(':nazev', $po_nazev);
            $stmt_EPO->bindParam(':adresa', $po_adresa);
            $stmt_EPO->bindParam(':mesto', $po_mesto);
            $stmt_EPO->bindParam(':psc', $po_psc);
            $stmt_EPO->bindParam(':dic', $po_dic);
    
            $stmt_EPO->execute();
            $db = null;
        } catch(PDOException $e){
            return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
        }
    } else if($beforeIsPO && !$is_po){
        $sql_DPO = 'DELETE FROM public."pravnickeOsoby" WHERE id_user = '.$id_user;
        try{
            $db = new db();
            $db = $db->connect();
            $stmt = $db->prepare($sql_DPO);
            $stmt->execute();
            $db = null;
        } catch(PDOException $e){
            return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
        }
    } else if(!$beforeIsPO && $is_po){
        $sql_PO = 'INSERT INTO public."pravnickeOsoby"(
            ic, nazev, adresa, mesto, psc, dic, id_user)
            VALUES (:ic, :nazev, :adresa, :mesto, :psc, :dic, :id_user)';
        try{
            $db = new db();
            $db = $db->connect();
            $stmt_PO = $db->prepare($sql_PO);
    
            $stmt_PO->bindParam(':ic', $po_ic);
            $stmt_PO->bindParam(':nazev', $po_nazev);
            $stmt_PO->bindParam(':adresa', $po_adresa);
            $stmt_PO->bindParam(':mesto', $po_mesto);
            $stmt_PO->bindParam(':psc', $po_psc);
            $stmt_PO->bindParam(':dic', $po_dic);
            $stmt_PO->bindParam(':id_user', $id_user);
    
            $stmt_PO->execute();
            $db = null;
        } catch(PDOException $e){
            return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
        }
    }

    /**
     * edit count limit if user_type = PROFI
     */
    if($user_type == 'PROFI'){
        $sql_PU = 'UPDATE public."professionalUser" SET count_limit=:count_limit WHERE id_user=:id_user';
        try{
            $db = new db();
            $db = $db->connect();
            $stmt_PU = $db->prepare($sql_PU);
            
            $stmt_PU->bindParam(':count_limit', $count_limit);
            $stmt_PU->bindParam(':id_user', $id_user);
            
            $stmt_PU->execute();
            
            $db = null;
        } catch(PDOException $e){
            return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
        }
    
    }
    return $response->withJson(array('note'=> array('text'=> 'User success edit')));
});
//delete user
$app->delete('/user/delete/{id_user}', function (Request $request, Response $response){
    $id_user = $request->getAttribute('id_user');
    $sql = 'DELETE FROM public."userContainer" WHERE id_user = '.$id_user;

    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
        return $response->withStatus(200)->withJson(array('note'=> array('text'=> 'User success delete')));
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});


/**
 * FAKTURA
 */
//add
$app->post('/invoice/add', function (Request $request, Response $response){
    // get Param
    $id_user = $request->getParam('id_user');
    $vystavena = $request->getParam('vystavena');
    $splatna = $request->getParam('splatna');
    $obdobi_od = $request->getParam('obdobi_od');
    $obdobi_do = $request->getParam('obdobi_do');
    $zaplacena = $request->getParam('zaplacena');
    $castka = floatval($request->getParam('castka'));
    $variabilni_symbol = $request->getParam('variabilni_symbol');

    $sql = 'INSERT INTO public.faktura(
        id_user, vystavena, splatna, obdobi_od, obdobi_do, zaplacena, castka, variabilni_symbol)
        VALUES (:id_user, :vystavena, :splatna, :obdobi_od, :obdobi_do, :zaplacena, :castka::float::numeric::money, :variabilni_symbol)';

    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id_user', $id_user);
        $stmt->bindParam(':vystavena', $vystavena);
        $stmt->bindParam(':splatna', $splatna);
        $stmt->bindParam(':obdobi_od', $obdobi_od);
        $stmt->bindParam(':obdobi_do', $obdobi_do);
        $stmt->bindParam(':zaplacena', $zaplacena);
        $stmt->bindParam(':castka', $castka);
        $stmt->bindParam(':variabilni_symbol', $variabilni_symbol);

        $stmt->execute();

        $db = null;
        return $response->withStatus(200)->withJson(array('note'=> array('text'=> 'Invoice success added')));
    } catch (PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});
//edit
$app->put('/invoice/edit/{id_faktura}', function (Request $request, Response $response){
    $id_faktura = $request->getAttribute('id_faktura');
    
    $vystavena = $request->getParam('vystavena');
    $splatna = $request->getParam('splatna');
    $obdobi_od = $request->getParam('obdobi_od');
    $obdobi_do = $request->getParam('obdobi_do');
    $zaplacena = $request->getParam('zaplacena');
    $castka = floatval($request->getParam('castka'));
    $variabilni_symbol = $request->getParam('variabilni_symbol');

    $sql = 'UPDATE public.faktura
	SET  vystavena=:vystavena, splatna=:splatna, obdobi_od=:obdobi_od, obdobi_do=:obdobi_do, zaplacena=:zaplacena, castka=:castka::float::numeric::money, variabilni_symbol=:variabilni_symbol
    WHERE id_faktura = :id_faktura';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':vystavena', $vystavena);
        $stmt->bindParam(':splatna', $splatna);
        $stmt->bindParam(':obdobi_od', $obdobi_od);
        $stmt->bindParam(':obdobi_do', $obdobi_do);
        $stmt->bindParam(':zaplacena', $zaplacena);
        $stmt->bindParam(':castka', $castka);
        $stmt->bindParam(':variabilni_symbol', $variabilni_symbol);
        $stmt->bindParam(':id_faktura', $id_faktura);

        $stmt->execute();

        $db = null;
        return $response->withStatus(200)->withJson(array('note'=> array('text'=> 'Invoice success edit')));
    } catch (PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});
//delete
$app->delete('/invoice/delete/{id_faktura}', function (Request $request, Response $response){
    $id_faktura = $request->getAttribute('id_faktura');

    $sql = 'DELETE FROM public."faktura" WHERE id_faktura = '.$id_faktura;
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
        return $response->withStatus(200)->withJson(array('note'=> array('text'=> 'Invoice success delete')));
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});

/**
 * SMLOUVA
 */
//add
$app->post('/contract/add', function (Request $request, Response $response){
    // get Param
    $id_user = $request->getParam('id_user');
    $platna_od = $request->getParam('platna_od');
    $platna_do = $request->getParam('platna_do');
    $platna = $request->getParam('platna');
    $vypovedni_obdobi = $request->getParam('vypovedni_obdobi');
    $vypovedni_lhuta = $request->getParam('vypovedni_lhuta');


    $sql = 'INSERT INTO public.smlouva(
        id_user, platna_od, platna_do, platna, vypovedni_obdobi, vypovedni_lhuta)
        VALUES (:id_user, :platna_od, :platna_do, :platna, :vypovedni_obdobi, :vypovedni_lhuta)';

    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id_user', $id_user);
        $stmt->bindParam(':platna_od', $platna_od);
        $stmt->bindParam(':platna_do', $platna_do);
        $stmt->bindParam(':platna', $platna);
        $stmt->bindParam(':vypovedni_obdobi', $vypovedni_obdobi);
        $stmt->bindParam(':vypovedni_lhuta', $vypovedni_lhuta);

        $stmt->execute();

        $db = null;
        return $response->withStatus(200)->withJson(array('note'=> array('text'=> 'Contract success added')));
    } catch (PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});
//edit
$app->put('/contract/edit/{id_smlouva}', function (Request $request, Response $response){
    $id_smlouva = $request->getAttribute('id_smlouva');
    
    $platna_od = $request->getParam('platna_od');
    $platna_do = $request->getParam('platna_do');
    $platna = $request->getParam('platna');
    $vypovedni_obdobi = $request->getParam('vypovedni_obdobi');
    $vypovedni_lhuta = $request->getParam('vypovedni_lhuta');

    $sql = 'UPDATE public.smlouva
	SET  platna_od=:platna_od, platna_do=:platna_do, platna=:platna, vypovedni_obdobi=:vypovedni_obdobi, vypovedni_lhuta=:vypovedni_lhuta
    WHERE id_smlouva = :id_smlouva';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':platna_od', $platna_od);
        $stmt->bindParam(':platna_do', $platna_do);
        $stmt->bindParam(':platna', $platna);
        $stmt->bindParam(':vypovedni_obdobi', $vypovedni_obdobi);
        $stmt->bindParam(':vypovedni_lhuta', $vypovedni_lhuta);
        $stmt->bindParam(':id_smlouva', $id_smlouva);

        $stmt->execute();

        $db = null;
        return $response->withStatus(200)->withJson(array('note'=> array('text'=> 'Contract success edit')));
    } catch (PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});
//delete
$app->delete('/contract/delete/{id_smlouva}', function (Request $request, Response $response){
    $id_smlouva = $request->getAttribute('id_smlouva');

    $sql = 'DELETE FROM public."smlouva" WHERE id_smlouva = '.$id_smlouva;
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
        return $response->withStatus(200)->withJson(array('note'=> array('text'=> 'Contract success delete')));
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});

/**
 * INZERATY K POTVRZENI
 */
//get
$app->get('/adv/forCheck', function (Request $request, Response $response){
    $sql = 'SELECT I.id_adver, U.id_user, U.jmeno, U.prijmeni, Z.nazev as znacka, M.nazev as model, I.popisek 
    FROM public."inzeraty" I, public."userContainer" U, public."znacka" Z, public."model" M
    WHERE I.id_user = U.id_user AND I.id_znacka = Z.id_znacka AND I.id_model = M.id_model AND I.zverejneny = false';
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
//edit as zverejneny
$app->put('/adv/setAsPublic/{id_adver}', function (Request $request, Response $response){
    $id_adver = $request->getAttribute('id_adver');

    $zverejneny = true;

    $expirace_zverejneni = new DateTime();
    $expirace_zverejneni->add(new DateInterval('P30D'));
    $expirace_zverejneni = $expirace_zverejneni->format('Y-m-d');

    $expirace_smazani = new DateTime();
    $expirace_smazani->add(new DateInterval('P60D'));
    $expirace_smazani = $expirace_smazani->format('Y-m-d');

    $sql = 'UPDATE public.inzeraty SET zverejneny=:zverejneny, expirace_zverejneni=:expirace_zverejneni, expirace_smazani=:expirace_smazani WHERE id_adver = :id_adver';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        
        $stmt->bindParam(':zverejneny', $zverejneny);
        $stmt->bindParam(':expirace_zverejneni', $expirace_zverejneni);
        $stmt->bindParam(':expirace_smazani', $expirace_smazani);
        $stmt->bindParam(':id_adver', $id_adver);
        
        $stmt->execute();
        
        $db = null;
        return $response->withJson(array('note'=> array('text'=> 'Advertising success public')));
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});

/**
 * SPRÁVA VÝBAVY
 */
//get
$app->get('/gear', function (Request $request, Response $response){
    $sql = 'SELECT * FROM public."vybava"';
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
//add
$app->post('/gear/add', function (Request $request, Response $response){
    $nazev = $request->getParam('nazev');
    $kategorie = $request->getParam('kategorie');

    $sql = 'INSERT INTO public.vybava(
        nazev, kategorie)
        VALUES (:nazev, :kategorie)';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':nazev', $nazev);
        $stmt->bindParam(':kategorie', $kategorie);

        $stmt->execute();

        $db = null;
        return $response->withStatus(200)->withJson(array('note'=> array('text'=> 'Gear success added')));
    } catch (PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});
//edit
$app->put('/gear/edit/{id_vybava}', function (Request $request, Response $response){
    $id_vybava = $request->getAttribute('id_vybava');
    $nazev = $request->getParam('nazev');
    $kategorie = $request->getParam('kategorie');

    $sql = 'UPDATE public.vybava
        SET nazev=:nazev, kategorie=:kategorie
        WHERE id_vybava=:id_vybava';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':nazev', $nazev);
        $stmt->bindParam(':kategorie', $kategorie);
        $stmt->bindParam(':id_vybava', $id_vybava);

        $stmt->execute();

        $db = null;
        return $response->withStatus(200)->withJson(array('note'=> array('text'=> 'Gear success edited')));
    } catch (PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});
//delete
$app->delete('/gear/delete/{id_vybava}', function (Request $request, Response $response){
    $id_vybava = $request->getAttribute('id_vybava');
    /**
     * delete from inzeraty
     */
    $sql_I = 'UPDATE public.inzeraty
	    SET id_vybava=array_remove(id_vybava,:id_vybava)';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt_I = $db->prepare($sql_I);
        $stmt_I->bindParam(':id_vybava', $id_vybava);
        $stmt_I->execute();
        $db = null;
    } catch (PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
    /**
     * delete from model
     */
    $sql_M = 'UPDATE public.model
	    SET id_vybava=array_remove(id_vybava,:id_vybava)';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt_M = $db->prepare($sql_M);
        $stmt_M->bindParam(':id_vybava', $id_vybava);
        $stmt_M->execute();
        $db = null;
    } catch (PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
    /**
     * delete from vybava
     */
    $sql = 'DELETE FROM public.vybava
        WHERE id_vybava = '.$id_vybava; 
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
        return $response->withStatus(200)->withJson(array('note'=> array('text'=> 'Gear success delete')));
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});

/**
 * SPRAVA ZNACKA
 */
//add
$app->post('/carBrand/add', function (Request $request, Response $response){
    $nazev = $request->getParam('nazev');
    $sql = 'INSERT INTO public.znacka(nazev) VALUES (:nazev)';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':nazev', $nazev);
        $stmt->execute();
        $db = null;
        return $response->withStatus(200)->withJson(array('note'=> array('text'=> 'Car brand success added')));
    } catch (PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});
//edit
$app->put('/carBrand/edit/{id_znacka}', function (Request $request, Response $response){
    $id_znacka = $request->getAttribute('id_znacka');
    $nazev = $request->getParam('nazev');

    $sql = 'UPDATE public.znacka SET nazev=:nazev WHERE id_znacka=:id_znacka';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':nazev', $nazev);
        $stmt->bindParam(':id_znacka', $id_znacka);
        $stmt->execute();
        $db = null;
        return $response->withStatus(200)->withJson(array('note'=> array('text'=> 'Car brand success edited')));
    } catch (PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});
//delete
$app->delete('/carBrand/delete/{id_znacka}', function (Request $request, Response $response){
    $id_znacka = $request->getAttribute('id_znacka');

    $sql = 'DELETE FROM public."znacka" WHERE id_znacka = '.$id_znacka;
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
        return $response->withStatus(200)->withJson(array('note'=> array('text'=> 'Car brand success delete')));
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage(), 'code'=> $e->getCode())));
    }
});

/**
 * SPRÁVA MODELŮ
 */
//get all models
$app->get('/models', function (Request $request, Response $response){
    $sql = 'SELECT Z.nazev as nazev_znacky, M.id_model, M.nazev FROM public."model" M, public."znacka" Z WHERE M.id_znacka = Z.id_znacka';
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
//get contrete model
$app->get('/model/{id_model}', function (Request $request, Response $response){
    $id_model = $request->getAttribute('id_model');
    $sql = 'SELECT Z.nazev as nazev_znacky, M.* FROM public."model" M, public."znacka" Z WHERE M.id_znacka = Z.id_znacka AND M.id_model = '.$id_model;
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
//add
$app->post('/models/add', function (Request $request, Response $response){
    $id_znacka = $request->getParam('id_znacka');
    $nazev = $request->getParam('nazev');
    $vyrobeno_od = $request->getParam('vyrobeno_od');
    $vyrobeno_do = $request->getParam('vyrobeno_do');
    $id_vybava = $request->getParam('id_vybava');
    if(!is_null($id_vybava)) $id_vybava = '{'.$id_vybava.'}';

    $sql = 'INSERT INTO public.model(
        id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava)
        VALUES (:id_znacka, :nazev, :vyrobeno_od, :vyrobeno_do, :id_vybava)';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id_znacka', $id_znacka);
        $stmt->bindParam(':nazev', $nazev);
        $stmt->bindParam(':vyrobeno_od', $vyrobeno_od);
        $stmt->bindParam(':vyrobeno_do', $vyrobeno_do);
        $stmt->bindParam(':id_vybava', $id_vybava);
        $stmt->execute();
        $db = null;
        return $response->withStatus(200)->withJson(array('note'=> array('text'=> 'Model success added')));
    } catch (PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});
//edit
$app->put('/models/edit/{id_model}', function (Request $request, Response $response){
    $id_model = $request->getAttribute('id_model');

    $id_znacka = $request->getParam('id_znacka');
    $nazev = $request->getParam('nazev');
    $vyrobeno_od = $request->getParam('vyrobeno_od');
    $vyrobeno_do = $request->getParam('vyrobeno_do');
    $id_vybava = $request->getParam('id_vybava');
    if(!is_null($id_vybava)) $id_vybava = '{'.$id_vybava.'}';

    $sql = 'UPDATE public.model
	SET id_znacka=:id_znacka, nazev=:nazev, vyrobeno_od=:vyrobeno_od, vyrobeno_do=:vyrobeno_do, id_vybava=:id_vybava
	WHERE id_model=:id_model';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id_znacka', $id_znacka);
        $stmt->bindParam(':nazev', $nazev);
        $stmt->bindParam(':vyrobeno_od', $vyrobeno_od);
        $stmt->bindParam(':vyrobeno_do', $vyrobeno_do);
        $stmt->bindParam(':id_vybava', $id_vybava);
        $stmt->bindParam(':id_model', $id_model);
        $stmt->execute();
        $db = null;
        return $response->withStatus(200)->withJson(array('note'=> array('text'=> 'Model success edited')));
    } catch (PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});
//delete
$app->delete('/models/delete/{id_model}', function (Request $request, Response $response){
    $id_model = $request->getAttribute('id_model');

    $sql = 'DELETE FROM public."model" WHERE id_model = '.$id_model;
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
        return $response->withStatus(200)->withJson(array('note'=> array('text'=> 'Model success delete')));
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage(), 'code'=> $e->getCode())));
    }
});

/**
 * SPRAVA VŠEOBECNÝCH PODMÍNEK
 */
//get all conditions
$app->get('/conditions', function (Request $request, Response $response){
    $sql = 'SELECT id_podminka, platnost_od, platnost_do, platna FROM public."vseobecnePodminky"';
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
//get contrete condition
$app->get('/condition/{id_podminka}', function (Request $request, Response $response){
    $id_podminka = $request->getAttribute('id_podminka');
    $sql = 'SELECT * FROM public."vseobecnePodminky" WHERE id_podminka = '.$id_podminka;
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
//add
$app->post('/conditions/add', function (Request $request, Response $response){
    $zneni = $request->getParam('zneni');
    $platnost_od = $request->getParam('platnost_od');
    $platnost_do = $request->getParam('platnost_do');
    $platna = $request->getParam('platna');

    $sql = 'INSERT INTO public."vseobecnePodminky"(
        zneni, platnost_od, platnost_do, platna)
        VALUES (:zneni, :platnost_od, :platnost_do, :platna);';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':zneni', $zneni);
        $stmt->bindParam(':platnost_od', $platnost_od);
        $stmt->bindParam(':platnost_do', $platnost_do);
        $stmt->bindParam(':platna', $platna);
        $stmt->execute();
        $db = null;
        return $response->withStatus(200)->withJson(array('note'=> array('text'=> 'Condition success added')));
    } catch (PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});
//edit
$app->put('/conditions/edit/{id_podminka}', function (Request $request, Response $response){
    $id_podminka = $request->getAttribute('id_podminka');

    $platnost_do = $request->getParam('platnost_do');
    $platna = $request->getParam('platna');

    $sql = 'UPDATE public."vseobecnePodminky"
	SET platnost_do=:platnost_do, platna=:platna
	WHERE id_podminka=:id_podminka';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':platnost_do', $platnost_do);
        $stmt->bindParam(':platna', $platna);
        $stmt->bindParam(':id_podminka', $id_podminka);
        $stmt->execute();
        $db = null;
        return $response->withStatus(200)->withJson(array('note'=> array('text'=> 'Condition success edited')));
    } catch (PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});
//delete
$app->delete('/conditions/delete/{id_podminka}', function (Request $request, Response $response){
    $id_podminka = $request->getAttribute('id_podminka');

    $sql = 'DELETE FROM public."vseobecnePodminky" WHERE id_podminka = '.$id_podminka;
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
        return $response->withStatus(200)->withJson(array('note'=> array('text'=> 'Condition success delete')));
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage(), 'code'=> $e->getCode())));
    }
});

function prepareResponseForProfi($data){
    // get all from smlouva where id_user
    $sql_smlouva = 'SELECT * FROM public."smlouva" WHERE id_user = '.$data->id_user; 
    try{
        $db = new db();
        $db = $db->connect();
        $stmt_smlouva = $db->query($sql_smlouva);
        $data_smlouva = $stmt_smlouva->fetchAll(PDO::FETCH_OBJ);
        $data->smlouva = $data_smlouva;
        $db = null;
    } catch(PDOException $e){
        throw new Exception('Exception; function: prepareResponseForProfi; PDOException: '.$e.getMessage());
    } 

    // get all from faktura where id_user
    $sql_faktura = 'SELECT castka::numeric::float8 as castka_float, * FROM public."faktura" WHERE id_user = '.$data->id_user;
    try{
        $db = new db();
        $db = $db->connect();
        $stmt_faktura = $db->query($sql_faktura);
        $data_faktura = $stmt_faktura->fetchAll(PDO::FETCH_OBJ);
        $data->faktura = $data_faktura;
        $db = null;
    } catch(PDOException $e){
        throw new Exception('Exception; function: prepareResponseForProfi; PDOException: '.$e.getMessage());
    } 

    // get count limit from profissionalUser
    $sql_professionalUser = 'SELECT count_limit FROM public."professionalUser" WHERE id_user = '.$data->id_user; 
    try{
        $db = new db();
        $db = $db->connect();
        $stmt_professionalUser = $db->query($sql_professionalUser);
        $data_professionalUser = $stmt_professionalUser->fetch(PDO::FETCH_OBJ);
        $data = (object)array_merge((array)$data, (array)$data_professionalUser); 
        $db = null;
    } catch(PDOException $e){
        throw new Exception('Exception; function: prepareResponseForProfi; PDOException: '.$e.getMessage());
    } 
    return $data;
}

function prepareResponseForNotProfi($data){
    $sql_NPU = 'SELECT last_login, id_podminka FROM public."notProfessionalUser" WHERE id_user = '.$data->id_user;
    try{
        $db = new db();
        $db = $db->connect();
        $stmt_NPU = $db->query($sql_NPU);
        $data_NPU = $stmt_NPU->fetch(PDO::FETCH_OBJ);
        $data = (object)array_merge((array)$data, (array)$data_NPU); 
        $db = null;
    } catch(PDOException $e){
        throw new Exception('Exception; function: prepareResponseForProfi; PDOException: '.$e.getMessage());
    } 
    return $data;
}