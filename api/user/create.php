<?php 
   header('Access-Control-Allow-Origin: *');
   header('Content-Type: application/json');
   header('Access-Control-Allow-Methods: POST');
  
   include_once '../../config/Database.php';
   include_once '../../models/User.php';

   $database = new Database();
   $db = $database->connect();

   $user = new User($db);

   $data = json_decode(file_get_contents("php://input"));

   $user->first_name = $data->first_name;
   $user->last_name = $data->last_name;
   $user->email = $data->email;

   if ($user->create()) {
      echo json_encode(
         array('message' => 'User created')
      );
   } else {
      echo json_encode(
         array('message' => 'User not created')
      );
   }
























