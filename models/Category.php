<?php
   class Category {
      private $table = 'categories';
      private $conn;

      public $id;
      public $name;
      public $created_at;
      
      public function __construct($db) {
         $this->conn = $db;
      }
      
      // Read all data
      public function read() {
         $query = 'SELECT id, name, created_at from myblog.' . $this->table . ' ORDER BY created_at DESC';
         $stmt = $this->conn->prepare($query);
         $stmt->execute();
         return $stmt;
      }

      // Read single data from categories table
      public function read_single() {
         $query = 'SELECT id, name from myblog.' . $this->table . ' WHERE id = ? LIMIT 0,1';
         $stmt = $this->conn->prepare($query);
         $stmt->bindParam(1, $this->id);
         $stmt->execute();
         if($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->name = $row['name'];
         }
      }

      // POST category
      public function create() {
         $query = 'INSERT INTO myblog.' . $this->table . '
         SET name = :name
         ';

         $stmt = $this->conn->prepare($query);

         $this->name = htmlspecialchars(strip_tags($this->name));

         $stmt->bindParam(':name', $this->name);

         $stmt->execute();

         if($stmt->execute()) {
            return true;
         }
         printf('Error: \n' . $stmt->error);
         return false;
      }

      // Update category
      public function update() {
         $query = "UPDATE myblog." . $this->table . 
         " SET 
         name = :name WHERE id = :id";
         $stmt = $this->conn->prepare($query);

         // Cleaning data
         $this->id = htmlspecialchars(strip_tags($this->id));
         $this->name = htmlspecialchars(strip_tags($this->name));

         // Binding data
         $stmt->bindParam(':name', $this->name);
         $stmt->bindParam(':id', $this->id);

         $stmt->execute();

         if($stmt->execute()) {
            return true;
         }
         
         printf('Error: \n' . $stmt->error);
         return false;

      }

      // Delete category
      public function delete() {
         $query = "DELETE FROM myblog." . $this->table . 
         " WHERE id = :id";
         // Prepare query
         $stmt = $this->conn->prepare($query);

         // Clean data
         $this->id = htmlspecialchars(strip_tags($this->id));

         // Bind data
         $stmt->bindParam(':id', $this->id);

         $stmt->execute();

         if($stmt->execute()) {
            return true;
         }

         printf('Error: \n' . $stmt->error);
         return false;

      }

   }
?>