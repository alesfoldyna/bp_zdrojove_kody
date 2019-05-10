<?php

function isMailInDB($email){
    $sql = 'SELECT email FROM public."userContainer" WHERE email = \''.$email.'\'';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $data = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        if(empty($data)) return false;
        return true;
        
    }catch(PDOException $e){
        return $response->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
}

function isICInDB($ic){
    $sql = 'SELECT ic FROM public."pravnickeOsoby"';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        foreach($data as $d){
            if($ic == $d->ic){
                return true;
            }
        }
        return false;
        
    }catch(PDOException $e){
        return $response->withJson(array('error'=> array('text'=> $e->getMessage())));
    }
}

function selectICfromUser($email){
    $sql = 'SELECT ic FROM public."userContainer" WHERE email = \''.$email.'\'';

    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $data = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        
        return $data->ic;
        
    }catch(PDOException $e){
        return -1;
    }
    
}

function prepareSqlForSearch($id_znacka, $id_model, $id_vybava, $cena_od, $cena_do, $vyrobeno_od, $vyrobeno_do, $najeto_od, $najeto_do){
    $sql = 'SELECT zn.nazev as znacka,mo.nazev as model, adv.id_adver, adv.cena, adv.najeto as najeto, adv.fotogalerie[1], adv.vyrobeno, adv.popisek, adv.text_inzeratu, adv.odpocet_dph, adv.cena_bez_dph FROM inzeraty adv INNER JOIN znacka zn ON adv.id_znacka = zn.id_znacka INNER JOIN model mo ON adv.id_model = mo.id_model WHERE adv.zverejneny = true';


    if(is_null($id_znacka) && is_null($id_model) && is_null($id_vybava) 
        && is_null($cena_od) && is_null($cena_do) && is_null($vyrobeno_od) 
        && is_null($vyrobeno_do) && is_null($najeto_od) && is_null($najeto_do)) return $sql;



    // znacka
    if(!is_null($id_znacka)){ 
        $sql .= ' AND adv.id_znacka = '.$id_znacka;
    }
    // model
    if(!is_null($id_model)){ 
        $sql .= ' AND adv.id_model = '.$id_model;
        $first = false;
    }
    // vybava
    if(!is_null($id_vybava)){ 
        $sql .= ' AND adv.id_vybava @> \'{'.$id_vybava.'}\'';
    }

    //cena
    if(!is_null($cena_od) && !is_null($cena_do)){
        // od - do 
        $sql .= ' AND adv.cena BETWEEN '.$cena_od.'::float8::numeric::money AND '.$cena_do.'::float8::numeric::money';
    } else if(!is_null($cena_od) && is_null($cena_do)){
        // od 
        $sql .= ' AND adv.cena >= '.$cena_od.'::float8::numeric::money';
    } else if(is_null($cena_od) && !is_null($cena_do)){
        // do 
        $sql .= ' AND adv.cena <= '.$cena_do.'::float8::numeric::money';
    }

    //vyrobeo
    if(!is_null($vyrobeno_od) && !is_null($vyrobeno_do)){
        // od - do 
        $sql .= ' AND adv.vyrobeno BETWEEN '.$vyrobeno_od.' AND '.$vyrobeno_do;
    } else if(!is_null($vyrobeno_od) && is_null($vyrobeno_do)){
        // od 
        $sql .= ' AND adv.vyrobeno >= '.$vyrobeno_od;
    } else if(is_null($vyrobeno_od) && !is_null($vyrobeno_do)){
        // do 
        $sql .= ' AND adv.vyrobeno <= '.$vyrobeno_do;
    }


    //najeto
    if(!is_null($najeto_od) && !is_null($najeto_do)){
        // od - do 
        $sql .= ' AND najeto BETWEEN '.$najeto_od.' AND '.$najeto_do;
    } else if(!is_null($najeto_od) && is_null($najeto_do)){
        // od 
        $sql .= ' AND najeto >= '.$najeto_od;
    } else if(is_null($najeto_od) && !is_null($najeto_do)){
        // do 
        $sql .= ' AND najeto <= '.$najeto_do;
    }

    return $sql;
}


function findSeller($id_adver){
    $sql = 'SELECT id_user FROM public."inzeraty" WHERE id_adver = '.$id_adver;

    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $data = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        
        return $data->id_user;
        
    }catch(PDOException $e){
        return -1;
    }
}

function getUserTypeAndIC($email){
    $sql = 'SELECT user_typ, ic FROM public."userContainer" WHERE email =  \''.$email.'\'';

    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $data = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        
        return $data;
        
    }catch(PDOException $e){
        return -1;
    }

}

function removeDirectory($path) {
    $files = glob($path . '/*');
    foreach ($files as $file) {
        is_dir($file) ? removeDirectory($file) : unlink($file);
    }
    rmdir($path);
     return;
 }
 
 
/**
 * @param string $directory
 * @return string img string base64
 */
function encodeImageToBase64($directory){
    $type = pathinfo($directory, PATHINFO_EXTENSION);
    $data = file_get_contents($directory);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    return $base64;
}

function base64ToImage($base64_string, $output_file) {
    $file = fopen($output_file, "wb");

    $data = explode(',', $base64_string);

    fwrite($file, base64_decode($data[1]));
    fclose($file);

    return $output_file;
}

/**
 * @param int $id user id
 * @return boolean can be advertising public?
 * @throws Exception
 */
function isRedyToPublic($id){
    $sql = 'SELECT user_type FROM public."userContainer" WHERE id_user ='.$id;

    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $data = $stmt->fetch(PDO::FETCH_OBJ);
    } catch(PDOException $e){
        throw new Exception('Error: API/addAdvertising; function: isRedyToPublic; get: user_type; PDOException: '.$e->getMessage());
    }

    switch($data->user_type){
        case 'ADMIN': return true;
        case 'PROFI': return true;
        case 'NOTPROFI': return false;
        default: throw new Exception('Error: API/addAdvertising; function: isRedyToPublic; switch - default');
    }
}

/**
 * @param string $vin Vin code for select adv id
 * @return int advertising id
 * @throws Exception
 */
function getAdvertisingID($vin){
    $sql = 'SELECT id_adver FROM public."inzeraty" WHERE vin = \''.$vin.'\'';
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $data = $stmt->fetch(PDO::FETCH_OBJ);
        return $data->id_adver;
    } catch(PDOException $e){
        throw new Exception('Error: API/addAdvertising; function: getAdvertisingID; get: advertising id; PDOException: '.$e->getMessage());
    }
}

/**
 * @param string $dataURL base64
 * @return string type of file
 */
function getImgType($dataURL){
    $header = explode(';',$dataURL);
    $format = explode('/',$header[0]);
    return $format[1];
}

/**
 * @param int $id_user
 * @return boolean true - if user is NPU, Otherwise false
 * @throws Exception
 */
function isNPU($id_user){
    $sql = 'SELECT user_type FROM public."userContainer" WHERE id_user = '.$id_user;
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $data = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        if(empty($data)) throw new Exception('uzivatel neexistuje; function: isNPU');
        if($data->user_type !== 'NOTPROFI') return false;
        return true;
    }catch(PDOException $e){
        throw new Exception('chyba při ověřování typu uživatele; function: isNPU; PDOException: '.$e.getMessage());
    }
}

/**
 * @param int $id_user
 * @return boolean true - is user PO, Otherwise false
 * @throws Exception
 */
function isUserPo($id_user){
    $sql = 'SELECT is_po FROM public."userContainer" WHERE id_user = '.$id_user;
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $data = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        if(empty($data)) throw new Exception('uzivatel neexistuje; function: isUserPo');
        return $data->is_po;
    }catch(PDOException $e){
        throw new Exception('function: isUserPo; PDOException: '.$e.getMessage());
    }
}

/**
 * Get user type
 * @param int $id_user 
 * @param string $pass
 * @return string user_type
 * @throws Exception
 */
function getUserType($id_user, $pass){
    $sql = 'SELECT user_type FROM public."userContainer" WHERE pass =\''.$pass.'\' AND id_user = '.$id_user;
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $data = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        if(empty($data)) throw new Exception('uzivatel neexistuje; function: getUserType');
        return $data->user_type;
    }catch(PDOException $e){
        throw new Exception('function: getUserType; PDOException: '.$e.getMessage());
    }
}