<?php 
   header('Access-Control-Allow-Origin: *');
   header('Content-Type: application/json');
   header('Access-Control-Allow-Methods: PUT');
  
   include_once '../../config/Database.php';
   include_once '../../models/Book.php';

   $database = new Database();
   $db = $database->connect();

   $book = new Book($db);

   $data = json_decode(file_get_contents("php://input"));

   $book->book_id = $data->book_id;
   $book->title = $data->title;
   $book->author = $data->author;
   $book->release_year = $data->release_year;
   $book->is_available = $data->is_available;
   
   if ($book->update()) {
      echo json_encode(
         array('message' => 'Book updated')
      );
   } else {
      echo json_encode(
         array('message' => 'Book not updated')
      );
   }

