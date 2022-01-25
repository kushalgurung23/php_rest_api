<?php
   // This file will interact with the model

   // Headers to access rest api through http
   // * sign referes to public API which doesn't require any token
   header('Access-Control-Allow-Origin: *');
   header('Content-Type: application/json');

   include_once('../../config/Database.php');
   include_once('../../models/Post.php');

   // Instantiate database object & make connection
   $database = new Database();
   $db = $database->connect();

   // Instantiate blog post object
   $post = new Post($db);

   // Blog post query
   $result = $post->read();

   // Get row count
   $num = $result->rowCount();

   // Check if any blog posts
   if($num > 0) {
      // Initialize a post array
      $posts_arr = array();

      // It will store the actual data
      $posts_arr['data'] = array();

      while($row = $result->fetch(PDO::FETCH_ASSOC)) {
         // extract will allow us to use $id, $name directly instead of writing $row['id'], $row['name]
         extract($row);

         $post_item = array(
            'id' => $id,
            'title' => $title,
            'body' => html_entity_decode($body),
            'author' => $author,
            'category_id' => $category_id,
            'category_name' => $category_name
         );

         // Push to "data"
         array_push($posts_arr['data'], $post_item);
      }

      // Turn to JSON from PHP array
      echo json_encode($posts_arr);
   }
   else {
      echo json_encode(array('message' => 'No posts found'));
   }

?>