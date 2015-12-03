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
$app->get('/users/:id', getUser);
$app->get('/users/status/:status', getUserStatus);
$app->get('/shelters/:id', getShelter);

// Post
$app->post('/users', addUser);
$app->post('/shelters', addShelter);

// Put
$app->put('users/:id/:status', updateStatus);

$app->run();



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

