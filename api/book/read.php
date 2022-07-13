<?php 
   header('Access-Control-Allow-Origin: *');
   header('Content-Type: application/json');

   include_once '../../config/Database.php';
   include_once '../../models/Book.php';
 
   $database = new Database();
   $db = $database->connect();

   $book = new Book($db);
   $result = $book->read();
   $num = $result->rowCount();
   
   if($num > 0) {
      $books_arr = array();
      foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row){
         extract($row);
         $book_item = array(
            'book_id' => $book_id,
            'title' => $title,
            'author' => $author,
            'release_year' => $release_year,
            'is_available' => $is_available,
            'created_date' => $created_date
         );
               
         array_push($books_arr, $book_item);
      }
      
      echo json_encode($books_arr);
      
   } else {
      echo json_encode(
         array('message' => 'No books found')
      );
   }


   
