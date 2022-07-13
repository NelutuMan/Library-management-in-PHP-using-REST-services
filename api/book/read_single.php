<?php 
   header('Access-Control-Allow-Origin: *');
   header('Content-Type: application/json');

   include_once '../../config/Database.php';
   include_once '../../models/Book.php';
 
   $database = new Database();
   $db = $database->connect();

   $book = new Book($db);
   $book->book_id = isset($_GET['book_id']) ? $_GET['book_id'] : die();
   
   if ($book->read_single()){
      $book_arr = array(
         'book_id' => $book->book_id,
         'title' => $book->title,
         'author' => $book->author,
         'release_year' => $book->release_year,
         'is_available' => $book->is_available,
         'created_date' => $book->created_date
   );
      print_r(json_encode($book_arr));
   } else {
      echo json_encode(['message' => 'Invalid id']);
   }
   
   
   


   

   