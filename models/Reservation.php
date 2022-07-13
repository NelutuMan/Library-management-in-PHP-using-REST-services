<?php
   class Reservation {
      // Database 
      private $conn;
      private $table = 'reservations';

      // Reservation Properties
      public $res_id;
      public $book;
      public $user;
      public $borrow_date;
      public $return_date;
      public $book_id;
      public $user_id;

      public function __construct($db) {
         $this->conn = $db;
      }
      
      // Get Reservations
      public function read() {
         $query = 
         "SELECT reservations.res_id, reservations.book_id, books.title as book, reservations.user_id,  
          CONCAT(users.first_name, ' ', users.last_name) as user,
          reservations.borrow_date, reservations.return_date
          FROM reservations
          INNER JOIN books ON books.book_id = reservations.book_id
          INNER JOIN users ON users.user_id = reservations.user_id
          ORDER BY reservations.res_id DESC";
         
         $stmt = $this->conn->prepare($query);

         $stmt->execute();

         return $stmt;
      }  
      
      // Get Single Reservation
      public function read_single() {
         $query = 
         "SELECT reservations.res_id, books.title as book, reservations.book_id, books.title as book, reservations.user_id,
          CONCAT(users.first_name, ' ', users.last_name) as user,
          reservations.borrow_date, reservations.return_date
          FROM reservations
          INNER JOIN books ON books.book_id = reservations.book_id
          INNER JOIN users ON users.user_id = reservations.user_id
          WHERE  reservations.res_id = ?
          LIMIT 0,1";
           
         $stmt = $this->conn->prepare($query); 
         $stmt->bindParam(1, $this->res_id);
         $stmt->execute();

         if ($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->book_id = $row['book_id'];
            $this->user_id = $row['user_id'];
            $this->book = $row['book'];
            $this->user = $row['user'];
            $this->borrow_date = $row['borrow_date'];
            $this->return_date = $row['return_date'];

            return true;
         } 

         return false;      
      }

      // Update is_available in book
      public function updateBook() {
         
         if ($this->return_date <= date("Y-m-d")) {
            $is_available = true;

         }  else {
            $is_available = false;
         }
          $query = "UPDATE books SET is_available = ? WHERE book_id = ?";
          $stmt = $this->conn->prepare($query); 
          $stmt->bindParam(1, $is_available);
          $stmt->bindParam(2, $this->book_id);
          if ($stmt->execute()) {
            return true;
         }
         return false;
         
      }
      
      // Create Reservation
      public function create() {
         $query = 
         "INSERT INTO $this->table
          SET 
          book_id = :book_id,
          user_id = :user_id,
          borrow_date = :borrow_date,
          return_date = :return_date";

         $stmt = $this->conn->prepare($query);
         
         $this->book_id = htmlspecialchars(strip_tags($this->book_id));
         $this->user_id = htmlspecialchars(strip_tags($this->user_id));
         $this->borrow_date = htmlspecialchars(strip_tags($this->borrow_date));
         $this->return_date = htmlspecialchars(strip_tags($this->return_date));

         $stmt->bindParam(':book_id', $this->book_id);
         $stmt->bindParam(':user_id', $this->user_id);
         $stmt->bindParam(':borrow_date', $this->borrow_date);
         $stmt->bindParam(':return_date', $this->return_date);

         if ($stmt->execute() && $this->updateBook()) {
            return true;
         }

         printf("Error: %s.\n", $stmt->error);

         return false;
      }

      // Update Reservation
      public function update() {
         $query = 
         "UPDATE $this->table
          SET 
          book_id = :book_id,
          user_id = :user_id,
          borrow_date = :borrow_date,
          return_date = :return_date
          WHERE
          res_id = :res_id";

         $stmt = $this->conn->prepare($query);

         $this->res_id = htmlspecialchars(strip_tags($this->res_id));
         $this->book_id = htmlspecialchars(strip_tags($this->book_id));
         $this->user_id = htmlspecialchars(strip_tags($this->user_id));
         $this->borrow_date = htmlspecialchars(strip_tags($this->borrow_date));
         $this->return_date = htmlspecialchars(strip_tags($this->return_date));

         $stmt->bindParam(':book_id', $this->book_id);
         $stmt->bindParam(':user_id', $this->user_id);
         $stmt->bindParam(':borrow_date', $this->borrow_date);
         $stmt->bindParam(':return_date', $this->return_date);
         $stmt->bindParam(':res_id', $this->res_id);
         
         if ($stmt->execute() && $this->updateBook()) {
            return true;
         }

         printf("Error: %s.\n", $stmt->error);

         return false;
      }


      // Delete Reservation
      public function delete() {
         
         $check_id = "
         SELECT * FROM $this->table
         WHERE res_id = :res_id";
         
         $stmt = $this->conn->prepare($check_id); 
         $this->res_id = htmlspecialchars(strip_tags($this->res_id));
         $stmt->bindParam(':res_id', $this->res_id);
         $stmt->execute();
         
         if ($stmt->rowCount() > 0) {

            // Set the book availabale
            $id = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->book_id = $id['book_id'];
            $query = "UPDATE books SET is_available = 1 WHERE book_id = :book_id";
            $stmt = $this->conn->prepare($query); 
            $stmt->bindParam(':book_id', $this->book_id);
            $stmt->execute();

            $query = 
            "DELETE FROM $this->table 
            WHERE res_id = :res_id";

            $stmt = $this->conn->prepare($query); 
            $stmt->bindParam(':res_id', $this->res_id);

            $stmt->execute();

            return true;   
         }   

         return false;
      }
      
   }

   