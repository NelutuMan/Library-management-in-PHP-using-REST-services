<?php
   class Book {
      // Database
      private $conn;
      private $table = 'books';

      // Book Properties
      public $book_id;
      public $title;
      public $author;
      public $release_year;
      public $is_available;
      public $created_date;

      public function __construct($db) {
         $this->conn = $db;
      }
      
      // Get Books
      public function read() {
         $query = 
         "SELECT * FROM $this->table
          ORDER BY created_date DESC";
         
         $stmt = $this->conn->prepare($query);
         $stmt->execute();
         
         return $stmt;   
      }  
      
      // Get Single Book
      public function read_single() {
         $query = 
         "SELECT * FROM $this->table
         WHERE book_id = ?
         LIMIT 0,1";
      
         $stmt = $this->conn->prepare($query);
         $stmt->bindParam(1, $this->book_id);
         $stmt->execute(); 

         if ($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->title = $row['title'];
            $this->author = $row['author'];
            $this->release_year = $row['release_year'];
            $this->is_available = $row['is_available'];
            $this->created_date = $row['created_date'];

            return true;
         } 

         return false;
      }

      // Get books by name
      public function read_title() {
         $query = 
         "SELECT * from $this->table
         WHERE title = ?";

         $stmt = $this->conn->prepare($query);
         $stmt->bindParam(1, $this->title);
         $stmt->execute(); 

         if ($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->book_id = $row['book_id'];
            $this->author = $row['author'];
            $this->release_year = $row['release_year'];
            $this->is_available = $row['is_available'];
            $this->created_date = $row['created_date'];

            return true;
         } 

         return false;
      }

      // Create Book
      public function create() {
         $query = 
         "INSERT INTO $this->table
          SET 
          title = :title,
          author = :author,
          release_year = :release_year";

         $stmt = $this->conn->prepare($query);

         $this->title = htmlspecialchars(strip_tags($this->title));
         $this->author = htmlspecialchars(strip_tags($this->author));
         $this->release_year = htmlspecialchars(strip_tags($this->release_year));

         $stmt->bindParam(':title', $this->title);
         $stmt->bindParam(':author', $this->author);
         $stmt->bindParam(':release_year', $this->release_year);

         if ($stmt->execute()) {
            return true;
         }

         printf("Error: %s.\n", $stmt->error);

         return false;
      }

      // Update Book
      public function update() {
         $query = 
         "UPDATE $this->table
          SET 
          title = :title,
          author = :author,
          release_year = :release_year,
          is_available = :is_available
          WHERE
          book_id = :book_id";

         $stmt = $this->conn->prepare($query);

         $this->title = htmlspecialchars(strip_tags($this->title));
         $this->author = htmlspecialchars(strip_tags($this->author));
         $this->release_year = htmlspecialchars(strip_tags($this->release_year));
         $this->book_id = htmlspecialchars(strip_tags($this->book_id));

         $stmt->bindParam(':title', $this->title);
         $stmt->bindParam(':author', $this->author);
         $stmt->bindParam(':release_year', $this->release_year);
         $stmt->bindParam(':is_available', $this->is_available);
         $stmt->bindParam(':book_id', $this->book_id);

         if ($stmt->execute()) {
            return true;
         }
        
         printf("Error: %s.\n", $stmt->error);

         return false;
      }

      // Delete Book
      public function delete() {
         
         $check_id = "
         SELECT * FROM $this->table
         WHERE book_id = :book_id";
         
         $stmt = $this->conn->prepare($check_id); 
         $this->book_id = htmlspecialchars(strip_tags($this->book_id));
         $stmt->bindParam(':book_id', $this->book_id);
         $stmt->execute();

         if ($stmt->rowCount() > 0) {
            $query = 
            "DELETE FROM $this->table 
            WHERE book_id = :book_id";

            $stmt = $this->conn->prepare($query); 
            $stmt->bindParam(':book_id', $this->book_id);

            $stmt->execute();

            return true;
             
         }   

         return false;

      }
        
   }

   








