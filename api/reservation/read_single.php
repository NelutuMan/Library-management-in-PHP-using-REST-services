<?php 
   header('Access-Control-Allow-Origin: *');
   header('Content-Type: application/json');

   include_once '../../config/Database.php';
   include_once '../../models/Reservation.php';

   $database = new Database();
   $db = $database->connect();

   $reservation = new Reservation($db);
   $reservation->res_id = isset($_GET['res_id']) ? $_GET['res_id'] : die();
   
   if ($reservation->read_single()){
      $reservation_arr = array(
         'res_id' => $reservation->res_id,
         'book_id' => $reservation->book_id,
         'book' => $reservation->book,
         'user_id' => $reservation->user_id,         
         'user' => $reservation->user,
         'borrow_date' => $reservation->borrow_date,
         'return_date' => $reservation->return_date
      );
      print_r(json_encode($reservation_arr));
   } else {
      echo json_encode(['message' => 'Invalid id']);
   }
   
   
   