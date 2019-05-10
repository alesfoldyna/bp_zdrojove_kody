<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\UploadedFile;
use \Firebase\JWT\JWT;
use Tuupola\Base62;


$app->post('/token', function (Request $request, Response $response){
    /**
     * is loggin success?
     */
    $email = $request->getParam('email');
    $pass = $request->getParam('pass');
    $sql = 'SELECT * FROM public."userContainer" WHERE email = \''.$email.'\' AND pass = \''.$pass.'\'';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $DBdata = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        if(empty($DBdata)) return $response->withStatus(401)->withJson(array('error'=> array('text'=> 'bad signing')));
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
    /**
     * add count limit if user is PU
     */
    if($DBdata->user_type == 'PROFI'){
        $sql_PU = 'SELECT count_limit FROM public."professionalUser" WHERE id_user = '.$DBdata->id_user;
        try{
            $db = new db();
            $db = $db->connect();
            $stmt_PU = $db->query($sql_PU);
            $data_PU = $stmt_PU->fetch(PDO::FETCH_OBJ);
            $DBdata = (object) array_merge((array) $DBdata, (array)$data_PU);
        } catch(PDOException $e){
            return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
        }
    }
    /**
     *FOR NOTPROFI
     */
    if($DBdata->user_type == 'NOTPROFI'){
        /**
        * upadate last login NPU
        */
        $last_login = date("Y-m-d");
        $sql_upadtaLastLogin = 'UPDATE public."notProfessionalUser" SET last_login=:last_login
            WHERE id_user = :id_user';
        try{
            $db = new db();
            $db = $db->connect();
            $stmt = $db->prepare($sql_upadtaLastLogin);
    
            $stmt->bindParam(':last_login', $last_login);
            $stmt->bindParam(':id_user', $DBdata->id_user);

            $stmt->execute();
    
            $db = null;
        } catch (PDOException $e){
            return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
        }
        /**
         * get id_podminka
         */
        $sql_vseobecnaPodminka = 'SELECT id_podminka FROM public."notProfessionalUser" WHERE id_user = '.$DBdata->id_user;
        try{
            $db = new db();
            $db = $db->connect();
            $stmt_NPU = $db->query($sql_vseobecnaPodminka);
            $data_NPU = $stmt_NPU->fetch(PDO::FETCH_OBJ);
            $DBdata = (object) array_merge((array) $DBdata, (array)$data_NPU);
        } catch(PDOException $e){
            return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
        }

    }
    
    /**
     * make token
     */
    $now = new DateTime();
    $future = new DateTime("now +120 minutes");
    $server = $request->getServerParams();
    $jti = (new Base62)->encode(random_bytes(16));
    $payload = [
        "iat" => $now->getTimeStamp(),
        "exp" => $future->getTimeStamp(),
        "jti" => $jti
    ];
    $secret = "heslo pro prihlasene uzivatele";
    $token = JWT::encode($payload, $secret, "HS256");
    $data["token"] = $token;
    $data["expires"] = $future->getTimeStamp();
    $dataAll = (object) array_merge((array) $DBdata, $data);
    return $response->withStatus(201)->withJson($dataAll);
});

$app->post('/addNewAdv', function (Request $request, Response $response){
    /**
     * get param
     */
    $id_znacka = $request->getParam('id_znacka');
    $id_model = $request->getParam('id_model');
    $id_user = $request->getParam('id_user');
    $id_vybava = $request->getParam('id_vybava');
    $popisek = $request->getParam('popisek');
    $text_inzeratu = $request->getParam('text_inzeratu');
    $cena = $request->getParam('cena');
    $cena_bez_dph = $request->getParam('cena_bez_dph');
    $vyrobeno = $request->getParam('vyrobeno');
    $provozu_od = $request->getParam('provozu_od');
    $najeto = $request->getParam('najeto');
    $vin = $request->getParam('vin');
    $odpocet_dph = $request->getParam('odpocet_dph');
    //foto
    $photo = $request->getParam('photo');
    $isNotPhoto = is_null($photo);
    /**
     * set data
     */
    $expirace_zverejneni = null;
    $expirace_smazani = null;
    $datum_vytvoreni = date("Y-m-d");
    $direc = 'photo/non_photo.png';
    $fotogalerie = [$direc];
    $fotogalerie = implode(",",$fotogalerie);
    $fotogalerie = '{'.$fotogalerie.'}';

    if(!is_null($id_vybava)) $id_vybava = '{'.$id_vybava.'}';

    //zverejney
    try{
        $zverejneny = isRedyToPublic($id_user)? 1 : 0;
    } catch(Exception $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
    /**
     * addAdvertising without photo
     */
    $sqlAddAdvWithoutPhoto = 'INSERT INTO public.inzeraty(
        id_znacka, id_model, id_user, id_vybava, popisek, text_inzeratu, cena, cena_bez_dph, zverejneny, vyrobeno, provozu_od, najeto, vin, fotogalerie, expirace_zverejneni, expirace_smazani, datum_vytvoreni, odpocet_dph)
        VALUES (:id_znacka, :id_model, :id_user, :id_vybava, :popisek, :text_inzeratu, :cena::float::numeric::money, :cena_bez_dph::float::numeric::money, :zverejneny, :vyrobeno, :provozu_od, :najeto, :vin, :fotogalerie, :expirace_zverejneni, :expirace_smazani, :datum_vytvoreni, :odpocet_dph)';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt_wop = $db->prepare($sqlAddAdvWithoutPhoto);

        $stmt_wop->bindParam(':id_znacka', $id_znacka);
        $stmt_wop->bindParam(':id_model', $id_model);
        $stmt_wop->bindParam(':id_user', $id_user);
        $stmt_wop->bindParam(':id_vybava', $id_vybava);
        $stmt_wop->bindParam(':popisek', $popisek);
        $stmt_wop->bindParam(':text_inzeratu', $text_inzeratu);
        $stmt_wop->bindParam(':cena', $cena);
        $stmt_wop->bindParam(':cena_bez_dph', $cena_bez_dph);
        $stmt_wop->bindParam(':zverejneny', $zverejneny);
        $stmt_wop->bindParam(':vyrobeno', $vyrobeno);
        $stmt_wop->bindParam(':provozu_od', $provozu_od);
        $stmt_wop->bindParam(':najeto', $najeto);
        $stmt_wop->bindParam(':vin', $vin);
        $stmt_wop->bindParam(':fotogalerie', $fotogalerie);
        $stmt_wop->bindParam(':expirace_zverejneni', $expirace_zverejneni);
        $stmt_wop->bindParam(':expirace_smazani', $expirace_smazani);
        $stmt_wop->bindParam(':datum_vytvoreni', $datum_vytvoreni);
        $stmt_wop->bindParam(':odpocet_dph', $odpocet_dph);

        $stmt_wop->execute();
        $db = null;
        //return if noPhoto
        if($isNotPhoto){
            return $response->withStatus(200)->withJson(array('not'=> array('text'=> 'Advertising success added')));
        }

    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage(), 'code'=> $e->getCode())));
    }
    // get advertising id
    try{
        $idAdvertising = getAdvertisingID($vin);
    } catch(Exception $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
    /**
     * save image
     */
    $directory = DIRECTORY_SEPARATOR . 'var'. DIRECTORY_SEPARATOR . 'www' . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'photo' . DIRECTORY_SEPARATOR . $idAdvertising;
    if(!is_dir($directory)){
        mkdir($directory, 0777);
    }
    $PhotoDbDirectory = [];
    for($i = 0; $i<count($photo);$i++){
        try{
            $file = base64ToImage($photo[$i]["dataUrl"], $directory. DIRECTORY_SEPARATOR .$photo[$i]["index"].'.'.getImgType($photo[$i]["dataUrl"]));
            // add part to dbstring
            array_push($PhotoDbDirectory,'photo'.DIRECTORY_SEPARATOR.$idAdvertising.DIRECTORY_SEPARATOR.$photo[$i]["index"].'.'.getImgType($photo[$i]["dataUrl"]));
        } catch(Exception  $e) {
            return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage(), 'directory' => $directory)));
        } 
    }
    $PhotoDbDirectory = implode(",",$PhotoDbDirectory);
    $PhotoDbDirectory = '{'.$PhotoDbDirectory.'}';

    /**
     * add photo array to db
     */
    $sqlPhotoToDB = 'UPDATE public.inzeraty SET  fotogalerie=:fotogalerie WHERE id_adver = '.$idAdvertising;
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sqlPhotoToDB);

        $stmt->bindParam(':fotogalerie', $PhotoDbDirectory);

        $stmt->execute();
        return $response->withStatus(200)->withJson(array('note'=> array('text'=> 'Advertising success added')));
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});

$app->get('/adv/getMy/{id_user}', function (Request $request, Response $response){
    $id = $request->getAttribute('id_user');

    $sql = 'SELECT id_adver, Z.nazev AS znacka, M.nazev AS model, I.popisek, I.vyrobeno, I.datum_vytvoreni FROM znacka Z, model M, inzeraty I WHERE Z.id_znacka = I.id_znacka AND M.id_model = I.id_model AND id_user = '.$id;
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $response->withStatus(200)->withJson($data);
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }

});

$app->delete('/adv/delete/{id_adver}', function (Request $request, Response $response){
    $id_adver = $request->getAttribute('id_adver');

    $directory = DIRECTORY_SEPARATOR . 'var'. DIRECTORY_SEPARATOR . 'www' . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'photo' . DIRECTORY_SEPARATOR . $id_adver;
    removeDirectory($directory);

    $sql = 'DELETE FROM public.inzeraty WHERE id_adver = '. $id_adver;
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
        return $response->withStatus(200)->withJson(array('note'=> array('text'=> 'Advertising success delete')));
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});

$app->get('/adv/dataForEdit/{id_adver}', function (Request $request, Response $response){
    $id_adver = $request->getAttribute('id_adver');
    $directory = DIRECTORY_SEPARATOR . 'var'. DIRECTORY_SEPARATOR . 'www' . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'photo' . DIRECTORY_SEPARATOR . $id_adver;
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
    }

    $sql = 'SELECT cena::numeric::float8 AS cena_number, cena_bez_dph::numeric::float8 AS cena_bez_dph_number, * FROM public."inzeraty" WHERE id_adver = '.$id_adver;
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $data = $stmt->fetch(PDO::FETCH_OBJ);
        $data->cena_number = (float) $data->cena_number;
        $data->cena_bez_dph_number = (float) $data->cena_bez_dph_number;
        $data->imageSurces = $imagesData;
        return $response->withStatus(200)->withJson($data);
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});

$app->put('/adv/edit/{id_adver}', function (Request $request, Response $response){
    /**
     * get param
     */
    $id_adver = $request->getAttribute('id_adver');
    $id_znacka = $request->getParam('id_znacka');
    $id_model = $request->getParam('id_model');
    $id_vybava = $request->getParam('id_vybava');
    $popisek = $request->getParam('popisek');
    $text_inzeratu = $request->getParam('text_inzeratu');
    $cena = $request->getParam('cena');
    $cena_bez_dph = $request->getParam('cena_bez_dph');
    $vyrobeno = $request->getParam('vyrobeno');
    $provozu_od = $request->getParam('provozu_od');
    $najeto = $request->getParam('najeto');
    $vin = $request->getParam('vin');
    $odpocet_dph = $request->getParam('odpocet_dph');
    //foto
    $photo = $request->getParam('photo');
    $isNotPhoto = is_null($photo);
    /**
     * set data
     */
    $direc = 'photo/non_photo.png';
    $fotogalerie = '{'.$direc.'}';

    if(!is_null($id_vybava)) $id_vybava = '{'.$id_vybava.'}';

    /**
     * save image
     */
    $directory = DIRECTORY_SEPARATOR . 'var'. DIRECTORY_SEPARATOR . 'www' . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'photo' . DIRECTORY_SEPARATOR . $id_adver;
    if(!$isNotPhoto){
        if(!is_dir($directory)){
            mkdir($directory, 0777);
        } else {
            removeDirectory($directory);
            mkdir($directory, 0777);
        }
        $PhotoDbDirectory = [];
        for($i = 0; $i<count($photo);$i++){
            try{
                $file = base64ToImage($photo[$i]["dataUrl"], $directory. DIRECTORY_SEPARATOR .$photo[$i]["index"].'.'.getImgType($photo[$i]["dataUrl"]));
                // add part to dbstring
                array_push($PhotoDbDirectory,'photo'.DIRECTORY_SEPARATOR.$id_adver.DIRECTORY_SEPARATOR.$photo[$i]["index"].'.'.getImgType($photo[$i]["dataUrl"]));
            } catch(Exception  $e) {
                return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage(), 'directory' => $directory)));
            } 
        }
        $fotogalerie = [];
        $fotogalerie = implode(",",$PhotoDbDirectory);
        $fotogalerie = '{'.$fotogalerie.'}';

    } else if(is_dir($directory)){
        removeDirectory($directory);
    }

    $sql = 'UPDATE public.inzeraty
	SET id_znacka = :id_znacka, 
        id_model = :id_model,  
        id_vybava = :id_vybava, 
        popisek = :popisek, 
        text_inzeratu = :text_inzeratu, 
        cena = :cena, 
        cena_bez_dph = :cena_bez_dph, 
        vyrobeno = :vyrobeno, 
        provozu_od = :provozu_od, 
        najeto = :najeto, 
        vin = :vin, 
        fotogalerie = :fotogalerie, 
        odpocet_dph = :odpocet_dph
    WHERE id_adver = :id_adver';
    
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id_znacka', $id_znacka);
        $stmt->bindParam(':id_model', $id_model);
        $stmt->bindParam(':id_vybava', $id_vybava);
        $stmt->bindParam(':popisek', $popisek);
        $stmt->bindParam(':text_inzeratu', $text_inzeratu);
        $stmt->bindParam(':cena', $cena);
        $stmt->bindParam(':cena_bez_dph', $cena_bez_dph);
        $stmt->bindParam(':vyrobeno', $vyrobeno);
        $stmt->bindParam(':provozu_od', $provozu_od);
        $stmt->bindParam(':najeto', $najeto);
        $stmt->bindParam(':vin', $vin);
        $stmt->bindParam(':fotogalerie', $fotogalerie);
        $stmt->bindParam(':odpocet_dph', $odpocet_dph);
        $stmt->bindParam(':id_adver', $id_adver);

        $stmt->execute();
        $db = null;

        return $response->withStatus(200)->withJson(array('note'=> array('text'=> 'Advertising success edited')));

    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});

$app->put('/npu/edit/{id_user}', function (Request $request, Response $response){
    $id_user = $request->getAttribute('id_user');
    try{
        if(isNPU($id_user)){
            $email = $request->getParam('email');
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
            
                    $stmt_EPO->bindParam(':ic', $ic);
                    $stmt_EPO->bindParam(':nazev', $po_nazev);
                    $stmt_EPO->bindParam(':adresa', $po_adresa);
                    $stmt_EPO->bindParam(':mesto', $po_mesto);
                    $stmt_EPO->bindParam(':psc', $po_psc);
                    $stmt_EPO->bindParam(':dic', $po_dic);
            
                    $stmt_EPO->execute();
                    $db = null;
                    return $response->withJson(array('note'=> array('text'=> 'User success edit')));
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
                    return $response->withJson(array('note'=> array('text'=> 'User success edit')));
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
            
                    $stmt_PO->bindParam(':ic', $ic);
                    $stmt_PO->bindParam(':nazev', $po_nazev);
                    $stmt_PO->bindParam(':adresa', $po_adresa);
                    $stmt_PO->bindParam(':mesto', $po_mesto);
                    $stmt_PO->bindParam(':psc', $po_psc);
                    $stmt_PO->bindParam(':dic', $po_dic);
                    $stmt_PO->bindParam(':id_user', $id_user);
            
                    $stmt_PO->execute();
                    $db = null;
                    return $response->withJson(array('note'=> array('text'=> 'User success edit')));
                } catch(PDOException $e){
                    return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
                }
            } else return $response->withJson(array('note'=> array('text'=> 'User success edit')));

        } else return $response->withStatus(401)->withJson(array('error'=> array('text'=> 'Uživatel není NOTPROFI')));
    }catch (Exception $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});

$app->get('/adv/count/{id_user}', function (Request $request, Response $response){
    $id_user = $request->getAttribute('id_user');

    $sql = 'SELECT COUNT(id_user) AS pocet FROM public."inzeraty" WHERE id_user = :id_user GROUP BY (id_user)';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;

        return $response->withStatus(200)->withJson($data);

    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});

$app->put('/conditions/accept', function (Request $request, Response $response){
    $id_user = $request->getParam('id_user');
    $id_podminka = $request->getParam('id_podminka');
    $sql = 'UPDATE public."notProfessionalUser" SET id_podminka = :id_podminka WHERE id_user = :id_user';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id_podminka', $id_podminka);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->execute();
        $db = null;
        return $response->withStatus(200)->withJson(array('note'=> array('text'=> 'Condition was success set to user')));
    } catch (PDOException $e){
        return $response->withStatus(500)->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
});