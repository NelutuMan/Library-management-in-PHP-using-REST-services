<?php 
   header('Access-Control-Allow-Origin: *');
   header('Content-Type: application/json');
   header('Access-Control-Allow-Methods: DELETE');
  
   include_once '../../config/Database.php';
   include_once '../../models/Book.php';

   $database = new Database();
   $db = $database->connect();

   $book = new Book($db);
   $book->book_id = isset($_GET['book_id']) ? $_GET['book_id'] : die();
   
   if ($book->delete()) {
      echo json_encode(
         array('message' => 'Book deleted')
      );
   } else {
      echo json_encode(
         array('message' => 'Invalid id')
      );
   }

