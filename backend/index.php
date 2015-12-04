<?php

ini_set("display_errors",1);
require 'Slim/Slim.php';
require 'user.php';
require 'shelter.php';


//if($_POST)
//{
//    echo "asdfasdf";
//    exit;
//}

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

// Get

$app->get('/user/:id', 'getUser');
$app->get('/user/status/:status', 'getUserStatus');
$app->get('/shelters', 'getShelters');
$app->get('/shelter/:id', 'getShelter');

// Post
$app->post('/user', 'addUser');
$app->post('/shelter', 'addShelter');
$app->post('/event', 'addEvent');

// Put
$app->put('user/:id/:status', 'updateStatus');

$app->run();

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

function getUser($login) {
    $sql = "SELECT * FROM utilisateur WHERE utilisateur_login=:login";
    try {
        $dbCon = getConnection();
        $stmt = $dbCon->prepare($sql);
        $stmt->bindParam("login", $login);
        $stmt->execute();
        $user = $stmt->fetchObject();
        $dbCon = null;
        echo json_encode($user, JSON_UNESCAPED_UNICODE);

    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function addShelters(){
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
		$sql_shelter = "INSERT INTO abri ('abri_id','abri_adresse','abri_ville','abri_place_dispo','abri_latitude','abri_longitude','user_id')
							VALUES (:abri_id, :abri_adresse, :abri_ville, :abri_place_dispo, :abri_latitude, :abri_longitude, :user_id)";
		try{
			$dbCon = getConnection();
		    $stmt = $dbCon->prepare($sql_shelter);
		    $stmt->bindParam("abri_adresse", $paramShelterAdress);
		    $stmt->bindParam("abri_ville", $paramShelterCity);
		    $stmt->bindParam("abri_longitude", $paramShelterXPosition);
		    $stmt->bindParam("abri_latitude", $paramShelterYPosition);
		    $stmt->bindParam("abri_place_dispo", $paramShelterDispo);
		    $stmt->bindParam("user_id", $paramIdUser);
		    $stmt->execute();
		}catch(PDOException $e) {
	        echo '{"error":{"text":'. $e->getMessage() .'}}';
	    }
	    $dbCon = null;
	}
}

function addEvent(){
	global $app;
	$paramEventEpicentreLatitude = $req->params('EvEpLat');
	$paramEventEpicentreLongitude = $req->params('EvEpLon');
	$paramEventRayon = $req->params('EvRay');
	$paramEventValide = $req->params('EvVal');
	$paramEventDescription = $req->params('EvDes');
	$paramEventStatut = $req->params('EvSta');
	$paramEventGravite = $req->params('EvGra');

	if(true){
		$sql_event = "INSERT INTO event ('event_id','event_epicentre_latitude','event_epicentre_longitude',
			'event_rayon','event_valide','event_description','event_statut','event_gravite')
							VALUES (:event_id, :event_epicentre_latitude, :event_epicentre_longitude, :event_rayon,
								:event_valide, :event_description, :event_statut,:event_gravite)";
		try{
			$dbCon = getConnection();
		    $stmt = $dbCon->prepare($sql_shelter);
		    $stmt->bindParam("event_id", $paramShelterAdress);
		    $stmt->bindParam("event_epicentre_latitude", $paramShelterAdress);
		    $stmt->bindParam("event_epicentre_longitude", $paramShelterAdress);
		    $stmt->bindParam("event_rayon", $paramShelterAdress);
		    $stmt->bindParam("event_valide", $paramShelterAdress);
		    $stmt->bindParam("event_description", $paramShelterAdress);
		    $stmt->bindParam("event_statut", $paramShelterAdress);
		    $stmt->bindParam("event_gravite", $paramShelterAdress);
		    $stmt->execute();
		}catch(PDOException $e) {
	        echo '{"error":{"text":'. $e->getMessage() .'}}';
	    }
	    $dbCon = null;
	}
}


?>
