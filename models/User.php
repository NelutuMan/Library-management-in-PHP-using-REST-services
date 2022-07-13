<?php
   class User {
      // Database
      private $conn;
      private $table = 'users';

      // User Properties
      public $user_id;
      public $first_name;
      public $last_name;
      public $email;
      public $created_date;

      public function __construct($db) {
         $this->conn = $db;
      }
      
      // Get Users
      public function read() {
         $query = 
         "SELECT * FROM $this->table
          ORDER BY created_date DESC";
         
         $stmt = $this->conn->prepare($query);
         $stmt->execute();

         return $stmt;
      }  
      
      // Get Single User
      public function read_single() {
         $query = 
         "SELECT * FROM $this->table
         WHERE  user_id = ?
         LIMIT 0,1";
         
         $stmt = $this->conn->prepare($query);         
         $stmt->bindParam(1, $this->user_id);   
         $stmt->execute();

         if ($stmt->rowCount() != 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->first_name = $row['first_name'];
            $this->last_name = $row['last_name'];
            $this->email = $row['email'];
            $this->created_date = $row['created_date'];

            return true;
         } 

         return false; 
      }

      // Get User by name
      public function read_name() {
         $query = 
         "SELECT * FROM $this->table
         WHERE first_name = ? AND last_name = ?";

         $stmt = $this->conn->prepare($query);         
         $stmt->bindParam(1, $this->first_name);   
         $stmt->bindParam(2, $this->last_name); 
         $stmt->execute();

         if ($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->user_id = $row['user_id'];
            $this->email = $row['email'];
            $this->created_date = $row['created_date'];

            return true;
         } 

         return false; 
      }

      // Create User
      public function create() {
         $query = 
         "INSERT INTO $this->table
          SET 
          first_name = :first_name,
          last_name = :last_name,
          email = :email";

         $stmt = $this->conn->prepare($query);

         $this->first_name = htmlspecialchars(strip_tags($this->first_name));
         $this->last_name = htmlspecialchars(strip_tags($this->last_name));
         $this->email = htmlspecialchars(strip_tags($this->email));

         $stmt->bindParam(':first_name', $this->first_name);
         $stmt->bindParam(':last_name', $this->last_name);
         $stmt->bindParam(':email', $this->email);

         
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
          first_name = :first_name,
          last_name = :last_name,
          email = :email
          WHERE
          user_id = :user_id";

         $stmt = $this->conn->prepare($query);

         $this->first_name = htmlspecialchars(strip_tags($this->first_name));
         $this->last_name = htmlspecialchars(strip_tags($this->last_name));
         $this->email = htmlspecialchars(strip_tags($this->email));
         $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        
         $stmt->bindParam(':first_name', $this->first_name);
         $stmt->bindParam(':last_name', $this->last_name);
         $stmt->bindParam(':email', $this->email);
         $stmt->bindParam(':user_id', $this->user_id);
      
         if ($stmt->execute()) {
            return true;
         }

         printf("Error: %s.\n", $stmt->error);

         return false;
      }

      // Delete User
      public function delete() {
         
         $check_id = "
         SELECT * FROM $this->table
         WHERE user_id = :user_id";
         
         $stmt = $this->conn->prepare($check_id); 
         $this->user_id = htmlspecialchars(strip_tags($this->user_id));
         $stmt->bindParam(':user_id', $this->user_id);
         $stmt->execute();

         if ($stmt->rowCount() > 0) {
            $query = 
            "DELETE FROM $this->table 
            WHERE user_id = :user_id";

            $stmt = $this->conn->prepare($query); 
            $stmt->bindParam(':user_id', $this->user_id);

            $stmt->execute();

            return true;
             
         }   

         return false;

      }
      
   }

   