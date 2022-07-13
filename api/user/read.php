<?php 
   header('Access-Control-Allow-Origin: *');
   header('Content-Type: application/json');

   include_once '../../config/Database.php';
   include_once '../../models/User.php';

   $database = new Database();
   $db = $database->connect();

   $user = new User($db);
   $result = $user->read();
   $num = $result->rowCount();

   if($num > 0) {
      $users_arr = array();
      foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row) { 
         extract($row);
         $user_item = array(
            'user_id' => $user_id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'created_date' => $created_date
         );

         array_push($users_arr, $user_item);
      }

   
      echo json_encode($users_arr);

   } else {
      echo json_encode(
         array('message' => 'No users found')
      );

   }
