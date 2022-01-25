<?php
   header('Access-Control-Allow-Origin: *');
   header('Content-Type: application/json');

   include_once('../../config/Database.php');
   include_once('../../models/Post.php');

   $database = new Database();
   $db = $database->connect();

   $post = new Post($db);

   // Get id
   // die() cuts everything off and nothing will be displayed
   $post->id = isset($_GET['id']) ? $_GET['id'] : die();

   // Get post
   $post->read_single();

   // Create array
   $post_arr = array(
      'id' => $post->id,
      'title' => $post->title,
      'body' => $post->body,
      'author' => $post->author,
      'category_id' => $post->category_id,
      'category_name' => $post->category_name
    );

   //  print_r() is used for printing an array
   //  Convert to JSON 
   echo(json_encode($post_arr));


?>