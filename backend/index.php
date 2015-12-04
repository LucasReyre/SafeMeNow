<?php

ini_set("display_errors",1);
require 'Slim/Slim.php';
require 'confi.php';

//if($_POST)
//{
//    echo "asdfasdf";
//    exit;
//}

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

// Get
$app->get('/users/:id/:token', 'getUser');
$app->get('/users/status/:status', getUserStatus);
$app->get('/shelters/:id', getShelter);

// Post
$app->post('/users', addUser);
$app->post('/shelters', addShelter);

// Put
$app->put('users/:id/:status', updateStatus);
$app->put('/users/bind', 'bindUser');

$app->run();

function getUser($id, $token) {
	
    $sql_query = "SELECT user_id, user_nom, user_prenom, user_photo FROM user where user_id=:id and user_token=:token";
	
    try {
        $dbCon = getConnection();
        $stmt = $dbCon->prepare($sql_query);
		$stmt->bindParam("id", $id);
		$stmt->bindParam("token", $token);
        $user  = $stmt->fetchAll();
        $dbCon = null;
        echo '{"user": ' . json_encode($user, JSON_UNESCAPED_UNICODE) . '}';
    }
    catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
    
}

function bindUser() {

	global $app;
    $req = $app->request();
    $user_token_fb = $req->params('token');
	$user_nom = $req->params('nom');
	$user_prenom = $req->params('prenom');
	$user_photo = $req->params('photo');
	
    $sql_update_user = "UPDATE user SET user_token=:token, user_nom=:nom, user_prenom=:prenom, user_photo=:photo  WHERE user_id=:id";
	$sql_insert_user = "INSERT INTO (`user_id`, `user_token`,`user_nom`,`user_prenom`,`user_photo`) VALUES (:id, :token, :nom, :prenom, :photo)";
	$sql_find = "SELECT user_id, user_token WHERE user_id=:id";

    try {
        $dbCon = getConnection();  
		
        //On vérifie le token et on update ou creer l'utilisateur
        $user_token = md5(uniqid(mt_rand(), true));
		$json = file_get_contents('https://graph.facebook.com/app?access_token='.$user_token_fb);
		$obj = json_decode($json); 
		
		if($obj->id != null){
		   
		   $stmt = $dbCon->prepare($sql_find); 
		   $stmt->bindParam("id", $obj->id);
		   $stmt->execute();
		   $user = $stmt->fetchObject();
		   
		   if($user != null)
			$stmt = $dbCon->prepare($sql_insert_user); 
		   else
			$stmt = $dbCon->prepare($sql_update_user);
		   
		   $stmt->bindParam("id", $obj->id);
		   $stmt->bindParam("token", $user_token);
		   $stmt->bindParam("nom", $user_nom);
		   $stmt->bindParam("prenom", $user_prenom);
		   $stmt->bindParam("photo", $user_photo);
		   $stmt->execute();
		   
		   //Creation ou update
		   $stmt = $dbCon->prepare($sql_find);  
		   $stmt->bindParam("user_id", $obj->id);
		   $stmt->execute();
		   $user = $stmt->fetchObject();
	   }
	   else
		$user = false;
		
        $dbCon = null;
        echo json_encode($user, JSON_UNESCAPED_UNICODE);
        
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
    
}

function generateRandomString() {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < 6; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

?>

