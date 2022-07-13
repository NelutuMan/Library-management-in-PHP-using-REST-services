<?php 
   header('Access-Control-Allow-Origin: *');
   header('Content-Type: application/json');

   include_once '../../config/Database.php';
   include_once '../../models/User.php';

   $database = new Database();
   $db = $database->connect();

   $user = new User($db);
   #$user->user_id = isset($_GET['user_id']) ? $_GET['user_id'] : die();

   $name = isset($_GET['name']) ? $_GET['name'] : die();
   $split = explode(" ", $name);
   $user->first_name = $split[0];
   $user->last_name = $split[1];
   
   if ($user->read_name()){
      $user_arr = array(
         'user_id' => $user->user_id,
         'first_name' => $user->first_name,
         'last_name' => $user->last_name,
         'email' => $user->email,
         'created_date' => $user->created_date
      );
      print_r(json_encode($user_arr));
   } else {
      echo json_encode(['message' => 'Invalid name']);
   }
   
   