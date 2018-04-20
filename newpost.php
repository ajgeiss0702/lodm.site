<?php
session_start();

$authors = json_decode(file_get_contents("authors.json"), true);

if(isset($_POST['title']) & isset($_POST['post'])) {
  if($_POST['title'] !== "" & $_POST['post'] !== "") {
    if(isset($_SESSION['name']) & isset($_SESSION['email'])) {
      if(isset($authors[$_SESSION['email']]) & $authors[$_SESSION['email']] == $_SESSION['name']) {
        $pid = "P".time();
        $succ1 = mkdir("posts/".$pid);
        $succ2 = mkdir("posts/".$pid."/comments");
        $postjson = fopen('posts/'.$pid."/post.json", 'w');
        $title = htmlspecialchars(str_replace("\"", "\\\"", $_POST["title"]));
        $content = str_replace("\n", "<br>", str_replace("\r\n", "<br>", str_replace("\"", "\\\"", htmlspecialchars($_POST["post"]))));
        $data = '{"title": "'.$title.'", "author": "'.htmlspecialchars($_SESSION["name"]).'", "author-email": "'.htmlspecialchars($_SESSION['email']).'", "content": "'.$content.'", "upvotes": {}}';
        $message = "Post created! Taking you to it..<script>setTimeout(function(){location.href='../post/".$pid."'}, 1000)</script>";
        fwrite($postjson, $data);
        fclose($postjson);
      } else {
        $alertmessage = "You are not an author! A post submit feature will be coming soon.";
      }
    } else {
      $alertmessage = "You must be signed in to post!";
    }
  } else {
    $alertmessage = "Your post must have content!";
  }
}



 ?>
<!DOCTYPE html>
<html>
  <head>
    <title>League of Dank Memers</title>


    <!-- STYLES -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
      <link rel="stylesheet" href="styles.css?v=12">

      <link rel="icon" href="img/lodm.jpg" type="image/jpeg" />

      <script async type="text/javascript" src="//mod.imgbb.com/website.js" charset="utf-8"></script>
  </head>
  <body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <nav id="mainnavbar" class="navbar navbar-expand-lg navbar-light bg-light fixed-top" style="margin-bottom: 2em;">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarExpand" aria-controls="navbarExpand" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarExpand">
        <div class="navbar-nav mr-auto">
          <a class="nav-item nav-link" href="../../">Posts</a>
          <a class="nav-item nav-link" href="authors.php">Authors</a>
          <a class="nav-item nav-link" href="discord.php">Discord</a>
        </div>
        <div class="navbar-nav ml-auto">
          <?php
          if(isset($_SESSION['name'])) {
            echo("<span class='nav-item'>Signed in as ".$_SESSION['name'].". <a class='btn btn-danger btn-sm' href='login.php?logout'>Log out</a></span>");
          } else {
            echo("<span class='nav-item'>Not signed in. <a class='btn btn-success btn-sm' href='login.php'>Sign in</a></span>");
          }
          ?>
        </div>
      </div>

    </nav>
    <div style="position: fixed; right: 53%; left: 47%; top: 0.2em; z-index: 1031;">
      <a class="navbar-brand" href="../">
        <img src="img/lodm.jpg" style="height: 4em;" class="navlogo d-inline-block align-top" alt="">
      </a>
    </div>

    <div style="margin-left: auto; margin-right: auto; text-align: center;">
      <?php if(isset($message)) { ?>
        <div class="alert alert-success alert-dismissible fade show" style="max-width: 70%; margin-left: auto; margin-right: auto;" role="alert">
          <?php echo($message); ?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      <?php } ?>
    </div>
    <div style="margin-left: auto; margin-right: auto; text-align: center;">
      <?php if(isset($alertmessage)) { ?>
        <div class="alert alert-danger alert-dismissible fade show" style="max-width: 70%; margin-left: auto; margin-right: auto;" role="alert">
          <?php echo($alertmessage); ?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      <?php } ?>
    </div>
    <br>

    <div style="text-align: center;">
      <h1>New post</h1>
      <form method="post">
        <label for="title">Title</label><br>
        <input type="text" name="title"><br>
        <label for="post">Post content</label><br>
        <textarea name="post" style="height: 15em; width: 25em;"></textarea><br>
        <input type="submit">
      </form>
    </div>

  </body>
</html>
