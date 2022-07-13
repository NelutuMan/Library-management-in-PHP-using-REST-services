<?php 
   header('Access-Control-Allow-Origin: *');
   header('Content-Type: application/json');

   include_once '../../config/Database.php';
   include_once '../../models/Reservation.php';

   $database = new Database();
   $db = $database->connect();

   $reservation = new Reservation($db);
   $result = $reservation->read();
   $num = $result->rowCount();

   if($num > 0) {
      $reservations_arr = array();
      foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row){ 
         extract($row);
         $reservation_item = array(
            'res_id' => $res_id,
            'book_id' => $book_id,
            'book' => $book,
            'user_id' => $user_id,         
            'user' => $user,
            'borrow_date' => $borrow_date,
            'return_date' => $return_date
         );

         array_push($reservations_arr, $reservation_item);
      }

      echo json_encode($reservations_arr);

   } else {
      echo json_encode(
         array('message' => 'No reservations found')
      );

   }
