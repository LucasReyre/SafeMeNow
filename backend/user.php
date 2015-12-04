<?php

function getUsers($login, $token) {
    $sql_query = "SELECT user_id, user_nom, user_prenom, user_photo FROM user WHERE user_id=:login AND user_token=:token";
    try {
        $dbCon = getConnection();
        $stmt   = $dbCon->query($sql_query);
        $users  = $stmt->fetchAll(PDO::FETCH_OBJ);
        $dbCon = null;
        echo '{"users": ' . json_encode($users, JSON_UNESCAPED_UNICODE) . '}';
    }
    catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
    
}

function bindUsers() {

	global $app;
    $req = $app->request();
    $user_token_fb = $req->params('token');
	$user_nom = $req->params('nom');
	$user_prenom = $req->params('prenom');
	$user_photo = $req->params('photo');
	
    $sql_user_info = "UPDATE user SET user_token=:token, user_nom=:nom, user_prenom=:prenom, user_photo=:photo  WHERE utilisateur_id=:id";
	
    try {
        $dbCon = getConnection();  
        //On vérifie le couple mail, password et on update le token de l'utilisateur
        $user_token = md5(uniqid(mt_rand(), true));
        if($user!=false){
			$json = file_get_contents('https://graph.facebook.com/app?access_token='.$user_token_fb);
			$obj = json_decode($json); 
			if($obj->id != null){
			   $stmt = $dbCon->prepare($sql_user_info);
			   $stmt->bindParam("id", $obj->id);
			   $stmt->bindParam("token", $user_token);
			   $stmt->bindParam("nom", $user_nom);
			   $stmt->bindParam("prenom", $user_prenom);
			   $stmt->bindParam("photo", $user_photo);
			   $stmt->execute();
			   $user->utilisateur_id=$user->utilisateur_id
			   $user->utilisateur_token=$token;
		   }
		   else
			$user = false
        }
		else
			$user = false;
	
        $dbCon = null;
        echo json_encode($user, JSON_UNESCAPED_UNICODE);
        
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
    
}

