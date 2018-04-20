<?php
session_start();
if(isset($_GET['loggedin'])) {
  if(isset($_SESSION['name']) & isset($_SESSION['email'])) {
    $message = "Welcome, ".$_SESSION['name']."! You are now logged in.<script>window.history.replaceState(null, null, '/');</script>";
  } else {
    die("<script>location.href='login.php'</script>");
  }
}

function convertBBCode($body) {
    $find = array(
        "@\n@",
        "/\[url\=(.+?)\](.+?)\[\/url\]/is",
        "/\[b\](.+?)\[\/b\]/is",
        "/\[i\](.+?)\[\/i\]/is",
        "/\[u\](.+?)\[\/u\]/is",
        "/\[color\=(.+?)\](.+?)\[\/color\]/is",
        "/\[size\=(.+?)\](.+?)\[\/size\]/is",
        "/\[font\=(.+?)\](.+?)\[\/font\]/is",
        "/\[center\](.+?)\[\/center\]/is",
        "/\[right\](.+?)\[\/right\]/is",
        "/\[left\](.+?)\[\/left\]/is",
        "/\[img\](.+?)\[\/img\]/is",
        "/\[email\](.+?)\[\/email\]/is"
    );
    $replace = array(
        "<br />",
        "<a href=\"$1\" target=\"_blank\">$2</a>",
        "<strong>$1</strong>",
        "<em>$1</em>",
        "<span style=\"text-decoration:underline;\">$1</span>",
        "<font color=\"$1\">$2</font>",
        "<font size=\"$1\">$2</font>",
        "<span style=\"font-family: $1\">$2</span>",
        "<div style=\"text-align:center;\">$1</div>",
        "<div style=\"text-align:right;\">$1</div>",
        "<div style=\"text-align:left;\">$1</div>",
        "<img src=\"$1\" alt=\"Image\" />",
        "<a href=\"mailto:$1\" target=\"_blank\">$1</a>"
    );
    $body = preg_replace($find, $replace, $body);
    return $body;
}
 ?>
<!DOCTYPE html>
<html>
  <head>
    <title>League of Dank Memers</title>


    <!-- STYLES -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
      <link rel="stylesheet" href="styles.css?v=24">

    <link rel="icon" href="img/lodm.jpg" type="image/jpeg" />

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



    <div class="posts">
      <?php
      $posts = scandir("posts/", SCANDIR_SORT_DESCENDING);
      array_splice($posts, -1);
      array_splice($posts, -1);
      array_splice($posts, -1);
      array_splice($posts, -1);
      //print_r($posts);

      foreach($posts as $post) {
        $postinfo = json_decode(file_get_contents("posts/".$post."/post.json"), true);
        $postinfo2 = $postinfo;
        if(count($postinfo2['upvotes']) >= 1) {
          foreach($postinfo2['upvotes'] as $name) {
            $firstupvote = "First upvote was ".$name;
            break;
          }
        } else {
          $firstupvote = "Be the first to upvote!";
        }
        echo("<div class='post'><h2><a class='titlelink' href='/post/".$post."'>".$postinfo['title']."</a></h2>\n<span style='color: grey'>".$postinfo['author']."</span>\n<br><p>".convertBBCode($postinfo['content'])."</p><hr><span class='bottombuttons'><a class='btn btn-info btn-sm' onclick=\"post('/post/".$post."', {'upvote': 'true'});\">Upvote <span class='badge badge-light'>".(count($postinfo['upvotes']))."</span></a>  <span style='color: grey;'>".$firstupvote."</span></span></div>\n");
      }
      ?>
      <script>
      function post(path, params, method) {
        method = method || "post"; // Set method to post by default if not specified.

        // The rest of this code assumes you are not using a library.
        // It can be made less wordy if you use one.
        var form = document.createElement("form");
        form.setAttribute("method", method);
        form.setAttribute("action", path);

        for(var key in params) {
          if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
          }
        }

        document.body.appendChild(form);
        form.submit();
      }
      </script>
    </div>
  </body>
</html>
