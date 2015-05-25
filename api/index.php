<?php

require 'Slim/Slim.php';
require 'database.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$app->get('/session', function() {
    $session = getSession();
    $response["name"] = $session['name'];
    echoResponse(200, $response);
});

$app->get('/logout', function() {
    if (!isset($_SESSION)) {
        session_start();
    }
    if(isSet($_SESSION['name']))
    {
        unset($_SESSION['name']);
        $info='info';
        if(isSet($_COOKIE[$info]))
        {
            setcookie ($info, '', time() - $cookie_time);
        }
        $msg="Logged Out Successfully...";
    }
    else
    {
        $msg = "Not logged in...";
    }
    $response["message"] = $msg;
    echoResponse(200, $response);
});

$app->post('/login', function() use ($app){
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('userName', 'password'),$r->customer);
    $response = array();
    $db = database::getInstance();
    $userName = $r->customer->userName;
    $password = $r->customer->password;
    $validated = $db->validate(md5($userName), md5($password));
    if ($validated != NULL) {
        $response['status'] = "success";
        $response['message'] = 'Logged in successfully.';
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['name'] = $userName;
    } else {
        $response['status'] = "error";
        $response['message'] = 'Login failed. Incorrect credentials';
    }
    echoResponse(200, $response);
});

$app->post('/signUp', function() use($app){
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('userName', 'password'), $r->customer);
    $response = array();
    $db = database::getInstance();
    $userName = $r->customer->userName;
    $password = $r->customer->password;
    $created = $db->createUser(md5($userName), md5($password));
    if($created){
        $response['status'] = "success";
        $response['message'] = "User account created successfully.";
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['name'] = $userName;
    } else {
        $response['status'] = "error";
        $response['message'] = 'Account creation failed.  Try another username';
    }
    echoResponse(200, $response);
});

$app->post('/editCustomer', function() use($app){
    $r = json_decode($app->request->getBody());
    $response = array();
    $db = database::getInstance();
    
    $customerID = $r->customer->customerID;
    
    $firstName = $r->customer->firstName;
    $lastName = $r->customer->lastName;
    $street = $r->customer->street;
    $city = $r->customer->city;
    $state = $r->customer->state;
    $zip = $r->customer->zip;
    $homePhone = $r->customer->homePhone;
    $cellPhone = $r->customer->cellPhone;
    $email = $r->customer->email;
    
    
    $result = $db->editCustomer($firstName, $lastName, $street, $city, $state, 
                                  $zip, $homePhone, $cellPhone, $email, $customerID);
    if($result == true){
        $response['status'] = "success";
        $response['message'] = 'Customer update succeeded.';
    } else {
        $response['status'] = "error";
        $response['message'] = 'Customer update failed. Try again.';
    }
    echoResponse(200, $response);
});

$app->post('/search', function() use($app){
    $r = json_decode($app->request->getBody());
    $response = array();
    $db = database::getInstance();
    $firstName = ""; $lastName = "";
    if(!empty($r->customer->firstName)){
        $firstName = $r->customer->firstName;
    }
    if(!empty($r->customer->lastName)){
        $lastName = $r->customer->lastName;
    }
    $result = $db->findCustomer($firstName, $lastName);
    if(!empty($result)){
        $response['status'] = "success";
        $response['records'] = $result;
    } else {
        $response['status'] = "error";
        $response['message'] = 'No matching users found. Please try again.';
    }
    echoResponse(200, $response);
});

$app->post('/newCustomer', function() use($app){
    $r = json_decode($app->request->getBody());
    $response = array();
    $db = database::getInstance();
    $firstName = $r->customer->firstName;
    $lastName = $r->customer->lastName;
    $street = $r->customer->street;
    $city = $r->customer->city;
    $state = $r->customer->state;
    $zip = $r->customer->zip;
    $homePhone = $r->customer->homePhone;
    $cellPhone = $r->customer->cellPhone;
    $email = $r->customer->email;
    $result = $db->createCustomer($firstName, $lastName, $street, $city, $state, 
                                  $zip, $homePhone, $cellPhone, $email);
    if($result != 'failed'){
        $response['status'] = "success";
        $response['customerID'] = $result;
    } else {
        $response['status'] = "error";
        $response['message'] = 'Customer creation failed. Try again.';
    }
    echoResponse(200, $response);
});

$app->post('/getBalls', function() use($app){
    $r = json_decode($app->request->getBody());
    $response = array();
    $db = database::getInstance();
    if(empty($r->customerID)){
        echoResponse(200, $response);
        return;
    }
    $id = $r->customerID;
    $result = $db->getBalls($id);
    if(!empty($result)){
        $response['status'] = "success";
        $response['balls'] = $result;
    } 
    echoResponse(200, $response);
});

$app->post('/deleteBall', function() use($app){
    $r = json_decode($app->request->getBody());
    $response = array();
    $db = database::getInstance();
    if(empty($r->id)){
        echoResponse(200, $response);
        return;
    }
    $id = $r->id;
    $result = $db->deleteBall($id);
    if($result){
        $response['status'] = "success";
        $response['message'] = "Ball deletion succeeded";
    } else{
        $response['status'] = "error";
        $response['message'] = "Ball deletion failed";
    }
    echoResponse(200, $response);
});

$app->post('/newball', function() use($app){
    $r = json_decode($app->request->getBody());
    $response = array();
    if(empty($r->ball)){
        $response['status']="error";
        $response['message']="Empty Form";
        echoResponse(200, $response);
        return;
    }
    if(empty($r->customerID)){
        $response['status']="error";
        $response['message']="No customer specified";
        echoResponse(200, $response);
        return;
    }
    $tempball = $r->ball;
    $db = database::getInstance();
    $ctcleft = true;
    $fsleft = true;
    $fsright= true;
    $ctcright = true;
    if(!empty($tempball->ctcleft))$ctcleft=true;
    //if checkbox is unchecked, it is empty, not value = "unchecked"
    else $ctcleft=false;
    if(!empty($tempball->ctcright))$ctcright=true;else $ctcright=false;
    if(!empty($tempball->fsleft))$fsleft=true;else $fsleft=false;
    if(!empty($tempball->fsright))$fsright=true;else $fsright=false;
    if(empty($tempball->comments))$tempball->comments="none";
    $result = $db->createBall(
        $r->customerID,
        $tempball->balldate,
        $tempball->conv,
        $tempball->FT,
        $tempball->weight,
        $tempball->pin,
        $tempball->layout,
        $tempball->surface,
        $tempball->bhp,
        $tempball->size,
        $tempball->depth,
        $tempball->paph,
        $tempball->papud,
        $tempball->papclt,
        $tempball->thumb,
        $tempball->fingers,
        $tempball->LH,
        $tempball->RH,
        $tempball->BH,
        $ctcleft,
        $ctcright,
        $fsleft,
        $fsright,
        $tempball->clerk,
        $tempball->comments
    );
     if($result == 'success'){
        $response['status'] = "success";
        $response['customerID'] = $result;
        $response['message'] ="Ball successfully added";
    } else {
        $response['status'] = "error";
        $response['message'] = "Ball Creation Failed";
    }
    echoResponse(200, $response);
});

$app->run();

function getSession(){
    if (!isset($_SESSION)) {
        session_start();
    }
    $sess = array();
    if(isset($_SESSION['name']))
    {
        $sess["name"] = $_SESSION['name'];
    }
    else
    {
        $sess["name"] = '';
    }
    return $sess;
}

function verifyRequiredParams($required_fields,$request_params) {
    $error = false;
    $error_fields = "";
    foreach ($required_fields as $field) {
        if (!isset($request_params->$field) || strlen(trim($request_params->$field)) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }

    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["status"] = "error";
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echoResponse(200, $response);
        $app->stop();
    }
}

function echoResponse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);

    // setting response content type to json
    $app->contentType('application/json');

    echo json_encode($response);
}

?>