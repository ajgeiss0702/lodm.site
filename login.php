<?php
session_start();

if(isset($_SESSION['name']) & !isset($_GET['logout'])) {
  die("<script>location.href='../?loggedin'</script>");
}

if(isset($_GET['logout'])) {
  unset($_SESSION['name']);
  unset($_SESSION['email']);
  unset($_SESSION['imgurl']);
  unset($_SESSION['id']);
  $message = 'You have logged out. <script>window.history.replaceState(null, null, "/login.php");</script>';
}

if(isset($_POST['idtoken'])) {
  $resp = (array) json_decode(file_get_contents("https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=".$_POST['idtoken']));
  echo("<!--");
  print_r($resp);
  echo("-->");
  if(isset($resp['email_verified'])) {
    if($resp['email_verified'] !== "true") {
      die("Please verify the email on your google account.");
    }
  }
  if(isset($resp['name']) & isset($resp['email'])) {
    $_SESSION['name'] = $resp['name'];
    $_SESSION['email'] = $resp['email'];
    die("<script>location.href='../?loggedin'</script>");
  } else {
    die("Login failed: Could not get name and email");
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

  </head>
  <body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <nav id="mainnavbar" class="navbar navbar-expand-lg navbar-light bg-light" style="margin-bottom: 2em;">
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
    <div style="position: fixed; right: 53%; left: 47%; top: 0.2em;">
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
    <br>
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <meta name="google-signin-client_id" content="401130329928-lkdd0k9s25ra01ak1qhe7jihp7fqs3q8.apps.googleusercontent.com">
    <br>
    <div style="margin-left: 46%;">
      <div class="g-signin2" data-onsuccess="onSignIn"></div>
    </div>


    <script>
    <?php if(isset($_GET['logout'])) {?>
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
      console.log('User signed out.');
    });
    <?php } ?>
    var infos = {};
    function onSignIn(googleUser) {
      profile = googleUser.getBasicProfile();
      infos.idtoken = googleUser.getAuthResponse().id_token; // Do not send to your backend! Use an ID token instead.
      infos.name = profile.getName();
      infos.email = profile.getEmail(); // This is null if the 'email' scope is not present.
      post('login.php', {
        idtoken: infos.idtoken
      });
    }






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
    <div id="loginagain" style="margin-left: auto; margin-right: auto; text-align: center;">

    </div>

  </body>
</html>
