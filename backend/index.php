<?php

ini_set("display_errors",1);
require 'Slim/Slim.php';
require 'confi.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

// Get
$app->get('/users/:id/:token', 'getUser');
$app->get('/userStatus/:id', 'getUserStatus');
$app->get('/shelters', 'getShelters');
$app->get('/shelter/:id', 'getShelter');

// Post
$app->post('/shelter', 'addShelter');
$app->post('/event', 'addEvent');

// Put
$app->put('/updateUserStatus', 'updateStatus');
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

function getUserStatus($id){
  $sql = "SELECT l.localisation_statut FROM localisation l, user u WHERE u.user_id =:id and u.user_localisation_id_fk=l.localisation_id";
  try {
      $dbCon = getConnection();
      $stmt = $dbCon->prepare($sql);
      $stmt->bindParam("id", $id);
      $stmt->execute();
      $shelter = $stmt->fetchObject();
      $dbCon = null;
      echo json_encode($shelter, JSON_UNESCAPED_UNICODE);

  } catch(PDOException $e) {
      echo '{"error":{"text":'. $e->getMessage() .'}}';
  }
}

function getShelters() {
    $sql_query = "SELECT * from abri";
    try {
        $dbCon = getConnection();
        $stmt   = $dbCon->query($sql_query);
        $users  = $stmt->fetchAll(PDO::FETCH_OBJ);
        $dbCon = null;
        echo '{"shelter": ' . json_encode($users, JSON_UNESCAPED_UNICODE) . '}';
    }
    catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }

}

function getShelter($id){
  $sql = "SELECT * from abri WHERE abri_id=:idS";
  try {
      $dbCon = getConnection();
      $stmt = $dbCon->prepare($sql);
      $stmt->bindParam("idS", $id);
      $stmt->execute();
      $shelter = $stmt->fetchObject();
      $dbCon = null;
      echo json_encode($shelter, JSON_UNESCAPED_UNICODE);

  } catch(PDOException $e) {
      echo '{"error":{"text":'. $e->getMessage() .'}}';
  }
}

function addShelter(){
	global $app;
	$req = $app->request();
	$paramShelterAdress = $req->params('ShAdd');
	$paramShelterCity = $req->params('ShCit');
	$paramShelterXPosition = $req->params('ShXPo');
	$paramShelterYPosition = $req->params('ShYPo');
	$paramShelterDispo = $req->params('ShDis');
	$paramIdUser = $req->params('UsId');
	$paramTokenUser = $req->params('UsTok');

	if(true){
		$sql_shelter = "INSERT INTO abri (`abri_id`, `abri_adresse`, `abri_ville`, `abri_place_dispo`, `abri_latitude`, `abri_longitude`, `abri_user_id_fk`)
		VALUES (NULL, :abri_adresse, :abri_ville, :abri_longitude, :abri_latitude, :abri_place_dispo, :abri_user_id_fk)";
		try{
			$dbCon = getConnection();
		    $stmt = $dbCon->prepare($sql_shelter);
		    $stmt->bindParam("abri_adresse", $paramShelterAdress);
		    $stmt->bindParam("abri_ville", $paramShelterCity);
		    $stmt->bindParam("abri_longitude", $paramShelterXPosition);
		    $stmt->bindParam("abri_latitude", $paramShelterYPosition);
		    $stmt->bindParam("abri_place_dispo", $paramShelterDispo);
		    $stmt->bindParam("user_id", $paramIdUser);
		    $stmt->bindParam("abri_user_id_fk", $paramIdUser);
		    $stmt->execute();
		}catch(PDOException $e) {
	        echo '{"error":{"text":'. $e->getMessage() .'}}';
	    }
	    $dbCon = null;
	}
}

function addEvent(){
	global $app;
	$req = $app->request();
	$paramEventEpicentreLatitude = $req->params('EvEpLat');
	$paramEventEpicentreLongitude = $req->params('EvEpLon');
	$paramEventRayon = $req->params('EvRay');
	$paramEventValide = $req->params('EvVal');
	$paramEventDescription = $req->params('EvDes');
	$paramEventStatut = $req->params('EvSta');
	$paramEventGravite = $req->params('EvGra');

	if(true){
		$sql_event = "INSERT INTO event (`event_id`, `event_epicentre_latitude`, `event_epicentre_longitude`, `event_rayon`, `event_valide`, `event_description`, `event_statut`, `event_gravite`)
		VALUES (NULL, :event_epicentre_latitude, :event_epicentre_longitude, :event_rayon, :event_valide, :event_description, :event_statut, :event_gravite)";

		try{
			$dbCon = getConnection();
		    $stmt = $dbCon->prepare($sql_event);
		    $stmt->bindParam("event_epicentre_latitude", $paramEventEpicentreLatitude);
		    $stmt->bindParam("event_epicentre_longitude", $paramEventEpicentreLongitude);
		    $stmt->bindParam("event_rayon", $paramEventRayon);
		    $stmt->bindParam("event_valide", $paramEventValide);
		    $stmt->bindParam("event_description", $paramEventDescription);
		    $stmt->bindParam("event_statut", $paramEventStatut);
		    $stmt->bindParam("event_gravite", $paramEventGravite);
		    $stmt->execute();
		}catch(PDOException $e) {
	        echo '{"error":{"text":'. $e->getMessage() .'}}';
	    }
	    $dbCon = null;
	}
}

function updateStatus(){

  global $app;
    $req = $app->request();
    $paramId = $req->params('id');
    $paramLatitude = $req->params('lat');
    $paramLongitude = $req->params('lon');
    $paramStatus = $req->params('status');

    $sql_update_localisation = "UPDATE localisation l
                                INNER JOIN user u ON l.localisation_id=u.user_localisation_id_fk AND u.user_id =:id
                                SET l.localisation_latitude=:lat, l.localisation_longitude=:lon, l.localisation_horodatage=CURRENT_TIMESTAMP, l.localisation_statut=:status";

    try {
    $dbCon = getConnection();
		$stmt = $dbCon->prepare($sql_update_localisation);
		$stmt->bindParam("id", $paramId);
		$stmt->bindParam("lat", $paramLatitude);
		$stmt->bindParam("lon", $paramLongitude);
		$stmt->bindParam("status", $paramStatus);
		$stmt->execute();
    $dbCon = null;
    echo '{"ok":{"text":ok}}';
    } catch(PDOException $e) {
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

function getConnection() {
	    try {
	        $db_username = "root";
	        $db_password = "";
	        $conn = new PDO('mysql:host=localhost;dbname=savemenow', $db_username, $db_password); // to modify Dim
	        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	    } catch(PDOException $e) {
	        echo 'ERROR: ' . $e->getMessage();
	    }
	    return $conn;
	}

?>
