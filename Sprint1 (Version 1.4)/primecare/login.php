<?php
@ob_start();
session_start();
?>
<!-- The only lines I changed are lines 226 to 233-->
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Lato|Raleway:400,700,900" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Prime Health Care - Log In</title>
</head>
<!--Navbar-->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.html">Prime Care</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
            </li>
        </ul>
        <div class="form-inline my-2 my-lg-0">
            <a href="login.php">
                <button class="btn btn-outline-success my-2 my-sm-0" type="button">Log In</button>
            </a>
        </div>
        <div class="form-inline my-2 ml-lg-2">
            <a href="Signup.php">
                <button class="btn btn-outline-success my-2 my-sm-0" type="button">Sign Up</button>
            </a>
        </div>
    </div>
</nav>
<header>
    <a href="index.html">
        <img src="images/logo.png" class="logo" alt="HealthCare Logo">
    </a>
</header>
<div>
<div class="container">
<form method='post' action='login.php' class="signUp"><?php $error?>
    <div class="formHeader">Login</div>
    <div class="form">
    <label for="userName">User Name</label>
    <input type="text" maxlength="16" name="user" id="userName" value="<?php $user?>" required="required">
    <label for="password">Password</label>
    <input type="password" maxlength="16" name="pass" id="password" value="<?php $pass ?>" required="required">
    <input type="submit" value="Log In">
</form>
<form method="post" action="Signup.php" onsubmit="return true">
    <input type="submit" value="Sign Up">
    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</div>
</div>
</body>
<footer class="footer">
    <div class="container-fluid">
    </div>
</footer>
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
              $_SESSION['id'] = $row[0];
              $_SESSION['pass'] = $pass;
              $error = "you have successfully logged in";
		
              //echo $error;
			
			$query1 = "SELECT type FROM user WHERE username='$user' AND type ='doctor'";
			$result1 = queryMysql($query1);
			if($result1->num_rows == 0){
				 header('Location: main.php'); //bring you to the nurse main menu
			} else {
				header('Location: main.php'); //bring you to the doctor main menu
				$_SESSION['doctor'] = "1";
			}
            
              //echo "
//
  //                      <body>
    //                        <h3>You have Successfully been authenticated, $first $last </h3>
      //                  </body>
        //          ";
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
