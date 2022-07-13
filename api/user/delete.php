<?php 
   header('Access-Control-Allow-Origin: *');
   header('Content-Type: application/json');
   header('Access-Control-Allow-Methods: DELETE');
  
   include_once '../../config/Database.php';
   include_once '../../models/User.php';

   $database = new Database();
   $db = $database->connect();

   $user = new User($db);

   $user->user_id = isset($_GET['user_id']) ? $_GET['user_id'] : die();
   
   if ($user->delete()) {
      echo json_encode(
         array('message' => 'User deleted')
      );
   } else {
      echo json_encode(
         array('message' => 'Invalid id')
      );
   }

