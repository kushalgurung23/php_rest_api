<?php
   class Post {
      // DB stuff
      private $conn;
      private $table = 'posts';

      // Post table's properties
      public $id;
      public $category_id;

      // We will get category_name by using join query between categories and posts table
      public $category_name;
      public $title;
      public $body;
      public $author;
      public $created_at;

      // Constructor with DB
      public function __construct($db) 
      {
         $this->conn = $db;
      }

      // Method to get or read post
      public function read() {
         // Create our query where c and p are aliases of categories and posts tables
         $query = 'SELECT 
         c.name as category_name, 
         p.id, p.category_id, p.title, p.body, p.author, p.created_at 
         FROM myblog.
         ' . $this->table . ' p 
         LEFT JOIN
         myblog.categories c ON p.category_id = c.id
         ORDER BY
         p.created_at DESC';

         // Prepare statement
         $stmt = $this->conn->prepare($query);

         // Execute query
         $stmt->execute();

         return $stmt;
      }

      // Get Single post

      public function read_single() {
         // ? is used because we are using PDO's bindParam() method in place of p.id
         $query = 'SELECT 
         c.name as category_name, 
         p.id, p.category_id, p.title, p.body, p.author, p.created_at 
         FROM myblog.
         ' . $this->table . ' p 
         LEFT JOIN
         myblog.categories c ON p.category_id = c.id
         WHERE p.id = ? LIMIT 0,1';

         // Prepare statement
         $stmt = $this->conn->prepare($query);

         // Bind ID 
         // We have positional and named parameters in PDO.
         // The first parameter should bind to this->id
         $stmt->bindParam(1, $this->id);

         // Execute statement
         $stmt->execute();

         $row = $stmt->fetch(PDO::FETCH_ASSOC);

         // Set properties
         $this->title = $row['title'];
         $this->body = $row['body'];
         $this->author = $row['author'];
         $this->category_id = $row['category_id'];
         $this->category_name = $row['category_name'];
      }

      // Create Post
      // : = named parameter in PDO
      public function create() {
         $query = 'INSERT INTO myblog.' . $this->table . '
         SET 
            title = :title,
            body = :body,
            author = :author,
            category_id = :category_id
         ';

         // Prepare statement
         $stmt = $this->conn->prepare($query);

         // Clean up data for security purpose
         // When an object of Post class calls create() method, all these properties will have clean data which can't be used by hackers to leak information
         $this->title = htmlspecialchars(strip_tags($this->title));
         $this->body = htmlspecialchars(strip_tags($this->body));
         $this->author = htmlspecialchars(strip_tags($this->author));
         $this->category_id = htmlspecialchars(strip_tags($this->category_id));

         // Named parameters of PDO are used to make our query understand, which property's value should be inserted in respective column
         $stmt->bindParam(':title', $this->title);
         $stmt->bindParam(':body', $this->body);
         $stmt->bindParam(':author', $this->author);
         $stmt->bindParam(':category_id', $this->category_id);

         $stmt->execute();

         if($stmt->execute()) {
            return true;
         }

         // Print error if something goes wrong 
         printf("Error: %s.\n", $stmt->error);

         return false;
      }

      // Update post
      public function update() {
         $query = 'UPDATE myblog.' . $this->table . '
         SET 
            title = :title,
            body = :body,
            author = :author,
            category_id = :category_id
         WHERE id = :id
         ';

         // Prepare statement
         $stmt = $this->conn->prepare($query);

         // Clean up data for security purpose
         // When an object of Post class calls create() method, all these properties will have clean data which can't be used by hackers to leak information
         $this->id = htmlspecialchars(strip_tags($this->id));
         $this->title = htmlspecialchars(strip_tags($this->title));
         $this->body = htmlspecialchars(strip_tags($this->body));
         $this->author = htmlspecialchars(strip_tags($this->author));
         $this->category_id = htmlspecialchars(strip_tags($this->category_id));

         // Named parameters of PDO are used to make our query understand, which property's value should be inserted in respective column
         $stmt->bindParam(':id', $this->id);
         $stmt->bindParam(':title', $this->title);
         $stmt->bindParam(':body', $this->body);
         $stmt->bindParam(':author', $this->author);
         $stmt->bindParam(':category_id', $this->category_id);

         $stmt->execute();

         if($stmt->execute()) {
            return true;
         }

         // Print error if something goes wrong 
         printf("Error: %s.\n", $stmt->error);

         return false;
      }

      // Delete post
      public function delete() {
         $query = 'DELETE FROM myblog.' . $this->table . ' WHERE id = :id';

         // Prepare statement
         $stmt = $this->conn->prepare($query);

         $this->id = htmlspecialchars(strip_tags($this->id));

         $stmt->bindParam(':id', $this->id);

         if($stmt->execute()) {
            return true;
         }

         printf("Error: %s. \n" . $stmt->error);
         return false;
      }

      

   }
?>