<?php 
   header('Access-Control-Allow-Origin: *');
   header('Content-Type: application/json');
   header('Access-Control-Allow-Methods: DELETE');
  
   include_once '../../config/Database.php';
   include_once '../../models/Reservation.php';

   $database = new Database();
   $db = $database->connect();

   $reservation = new Reservation($db);
   $reservation->res_id = isset($_GET['res_id']) ? $_GET['res_id'] : die();
   
   if ($reservation->delete()) {
      echo json_encode(
         array('message' => 'Reservation deleted')
      );
   } else {
      echo json_encode(
         array('message' => 'Invalid id')
      );
   }

