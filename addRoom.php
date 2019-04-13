<?php
session_start();

require_once 'functions.php';
if(!isset($_SESSION['user'])){
    $page = "login.php";
}

if (isset($_POST['dept'])){
      
        $dept = $roomNmb = $roomDescript = $maxCap = "";

 
    $dept = sanitizeString($_POST['dept']); //int
    $roomNmb = sanitizeString($_POST['roomNmb']); // int
    $roomDescript = sanitizeString($_POST['roomDescript']); // string
    $maxCap = sanitizeString($_POST['maxCap']); // int
    $currentCap = 0;
	
    if ($roomNmb == "" || $dept == "" || $roomDescript == "" || $maxCap == "")
        $error = "Not all fields were entered<br>";
    else{
        
        $stmt = $connection->prepare('insert into room (roomNumber, departmentID, description, maxCapacity, patientsAssigned) values (?,?,?,?,?)');				
	   $stmt->bind_param('ssssi',$roomNmb,$dept,$roomDescript,$maxCap,$currentCap);
        $stmt->execute();
         if (!$stmt) {
               echo "There was a error with your data <a href='main.php'>click here</a> to return to the main menu.<br>";
         die($connection->error);
         }
         else
         {
             $page = "viewRoomInformation.php";
         }
    }
   
   header("Location: ". $page);
   exit;
      
     
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
                  <a class="nav-link" href="viewPrescriptions.php">Return to Prescriptions Menu<span class="sr-only">(current)</span></a>
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
      <form method="post" action="addRoom.php" class="addprescription"> <?php $error?>
          <div class="formHeader">Add a new room</div>
          <div class="form">
		  
              <label for="roomNmb">Room Number</label>
              <input type="text" maxlength="64" id="roomNmb" name="roomNmb" value="<?php $roomNmb?>" required="required">

              <label>Department: </label>
			  <select name = 'dept'>
		  
                <?php
                      $result = queryMysql("select * from department");
                      if ($result->num_rows > 0)
                      {
                        while ($row = mysqli_fetch_assoc($result))
                        {
                            echo '<option value=" '.$row['departmentID'].' "> '.$row['departmentID']. ' - ' .$row['departmentName'].'</option>';
                        }
                      }
                ?>

		  </select>
		  
		  <label for="description">Desciption</label>
          <input type="text" maxlength="150" id="roomDescript" name="roomDescript" value="<?php $roomDescript?>" required="required">
		  
		  <label for="maxCap">Maximum Capacity</label>
          <input type="text" maxlength="10" id="maxCap" name="maxCap" value="<?php $maxCap?>" required="required">
              
                    <button type="submit" class="btn btn-outline-success ">Add room</button>
      </form>
          </div>
      <!--}else{
         echo "There is no data to be displayed please <a href='main.php'>add</a> some.";
      }-->

        <!-- Optional JavaScript -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </div>
</html>