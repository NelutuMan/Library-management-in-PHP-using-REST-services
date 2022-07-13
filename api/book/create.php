<?php 
   header('Access-Control-Allow-Origin: *');
   header('Content-Type: application/json');
   header('Access-Control-Allow-Methods: POST');
  
   include_once '../../config/Database.php';
   include_once '../../models/Book.php';

   $database = new Database();
   $db = $database->connect();

   $book = new Book($db);

   $data = json_decode(file_get_contents("php://input"));

   $book->title = $data->title;
   $book->author = $data->author;
   $book->release_year = $data->release_year;

   if ($book->create()) {
      echo json_encode(
         array('message' => 'Book created')
      );
   } else {
      echo json_encode(
         array('message' => 'Book not created')
      );
   }
























