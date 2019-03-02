<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Prime Health Care - Log In</title>
</head>
<header>
    <a href="index.html">
        <img src="images/logo.png" class="logo" alt="HealthCare Logo">
    </a>
</header>

<body>
<form method='post' action='login.php' class="signUp"><?php $error?>
    <h3>Log In Form</h3>
    <label for="userName">User Name</label>
    <input type="text" maxlength="16" name="user" id="userName" value="<?php $user?>" required="required">

    <label for="password">Password</label>
    <input type="password" maxlength="16" name="pass" id="password" value="<?php $pass ?>" required="required">

    <input type="submit" value="Log In">
</form>
<form method="post" action="Signup.html" onsubmit="return true" class="signUp">
    <input type="submit" value="Sign Up">
</body>
</html>

<?php

  require_once 'functions.php';

   //echo "<div class='main'><h3>Please enter your details to log in</h3>";
  $error = $user = $pass = "";

  if (isset($_POST['user']))
   {
    $user = sanitizeString($_POST['user']);
   $pass = sanitizeString($_POST['pass']);

  //  $un_temp = sanitizeString(_SERVER['PHP_AUTH_USER']);
   // $pw_temp = sanitizeString(_SERVER['PHP_AUTH_PW']);

    if ($user == "" || $pass == "")
        $error = "Not all fields were entered<br>";
    else
    {


        $query = "SELECT * FROM user WHERE username='$user'";
        $result = queryMysql($query);
        if (!$result) die($connection->error);
        elseif ($result->num_rows){

            $row = $result->fetch_array(MYSQLI_NUM);
            $result->close();
            $first = $row[1];
            $last = $row[2];
            $salt1 = $row[6];
            $salt2 = $row[7];

            $token = hash('ripemd128', "$salt1$pass$salt2");

            if($token == $row[5])
            {
              session_start();
              $_SESSION['user'] = $user;
              $_SESSION['pass'] = $pass;

              $error = "you have successfully logged in";

              //echo $error;
              //header('Location: menu.php'); //bring you to the main menu

            
              echo "

                        <body>
                            <h3>You have Successfully been authenticated, $first $last </h3>
                        </body>
                  ";
            }
            else die("Invalid Username/Password combination");

            }
            else die("Invalid Username/Password combination");


      //if ($result->num_rows == 0)
     // {
      //  $error = "<span class='error'>Username/Password
      //            invalid</span><br><br>";
     // }

    }
  }

?>

