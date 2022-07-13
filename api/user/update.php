<?php 
   header('Access-Control-Allow-Origin: *');
   header('Content-Type: application/json');
   header('Access-Control-Allow-Methods: PUT');
  
   include_once '../../config/Database.php';
   include_once '../../models/User.php';

   $database = new Database();
   $db = $database->connect();

   $user = new User($db);

   $data = json_decode(file_get_contents("php://input"));

   $user->user_id = $data->user_id;
   $user->first_name = $data->first_name;
   $user->last_name = $data->last_name;
   $user->email = $data->email;
   
   if ($user->update()) {
      echo json_encode(
         array('message' => 'User updated')
      );
   } else {
      echo json_encode(
         array('message' => 'User not updated')
      );
   }

