<?php 
   header('Access-Control-Allow-Origin: *');
   header('Content-Type: application/json');
   header('Access-Control-Allow-Methods: PUT');
  
   include_once '../../config/Database.php';
   include_once '../../models/Reservation.php';

   $database = new Database();
   $db = $database->connect();

   $reservation = new Reservation($db);

   $data = json_decode(file_get_contents("php://input"));

   $reservation->res_id = $data->res_id;
   $reservation->book_id = $data->book_id;
   $reservation->user_id = $data->user_id;
   $reservation->borrow_date = $data->borrow_date;
   $reservation->return_date = $data->return_date;
   
   if ($reservation->update()) {
      echo json_encode(
         array('message' => 'Reservation updated')
      );
   } else {
      echo json_encode(
         array('message' => 'Reservation not updated')
      );
   }

