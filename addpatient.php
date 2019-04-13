<?php

session_start();
require_once 'functions.php';
if(!isset($_SESSION['user'])){
    header("Location:login.php");
    
}

if (isset($_POST['firstName'])){
    //$username = fix_string($_POST['firstName']);
      
        $first = $last = $rmnumber = "";

 
    $first = sanitizeString($_POST['firstName']);
    $last = sanitizeString($_POST['lastName']);
    $rmnumber = sanitizeString($_POST['rmnumber']);

//echo "$first, $last, $rmnumber";
  //  $un_temp = sanitizeString(_SERVER['PHP_AUTH_USER']);
   // $pw_temp = sanitizeString(_SERVER['PHP_AUTH_PW']);

    if ($first == "" || $last == "" || $rmnumber == "")
        $error = "Not all fields were entered<br>";
    else{
       // echo "$first, $last, $rmnumber";
        $stmt = $connection->prepare('call spAddPatient(?,?,?)');		
	   // $stmt->bind_param('ssi', $first, $last, $rmnumber);		
	 //  $stmt = $connection->prepare('insert into patient(firstName, lastName, roomNumber) values(?,?,?)');		
	   $stmt->bind_param('ssi',$first, $last, $rmnumber);
        $stmt->execute();
         if (!$stmt) {
               echo "There was a error with your data <a href='main.php'>click here</a> to return to the main menu.<br>";
         die($connection->error);
         }
         }
         
   header('Location: main.php');
      
    } 

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link href="https://fonts.googleapis.com/css?family=Lato|Raleway:400,700,900" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Prime Health Care - Welcome</title>
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
                  <a class="nav-link" href="main.php">Doctor Main Menu<span class="sr-only">(current)</span></a>
              </li>
          </ul>
          <div class="form-inline my-2 ml-lg-2">
              <a href="logout.php">
                  <button class="btn btn-outline-success my-2 my-sm-0" type="button">Log out</button>
              </a>
          </div>
      </div>
  </nav>

  <header>
    <a href="index.html">
      <img src="images/logo.png"  class="logo">
    </a>
  </header>
  <div class="container">
      <form method="post" action="addpatient.php" class="addpatient"> <?php $error?>
          <div class="formHeader">Add a new Patient</div>
          <div class="form">
              <label for="firstName">First Name</label>
              <input type="text" maxlength="24" id="firstName" name="firstName" value="<?php $first?>" required="required">

              <label for="lastName">Last Name</label>
              <input type="text" maxlength="64" id="lastName" name="lastName" value="<?php $last?>" required="required">

              <label for="rmNumber">Room Number</label>
              <input type="text" maxlength="128" id="rmnumber" name="rmnumber" value="<?php $rmnumber?>" required="required">
                <form method='post' action='addpatient.php' onsubmit='return true'>
                    <button type="submit" class="btn btn-outline-success ">Add Patient</button>
                </form>
      </form>
          </div>
      <!--}else{
         echo "There is no data to be displayed please <a href='main.php'>add</a> some.";
      }-->
<?php 
require_once 'functions.php';

      ?>

        <!-- Optional JavaScript -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </div>
</html>