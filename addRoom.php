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
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Lato|Raleway:400,700,900" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Prime Health Care - Add Room</title>
    </head>
    <!--Navbar-->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="main.php">Prime Care</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="viewRoomInformation.php">Return to View Rooms<span class="sr-only">(current)</span></a>
                </li>
            </ul>
            <div class="form-inline my-2 ml-lg-2">
                <form method='post' action='viewRoomInformation.php' onsubmit='return true'>
                    <button type="submit" class="btn btn-outline-warning ">View Room Information</button>
                </form>
            </div>
            <div class="form-inline my-2 ml-lg-2">
                <form method='post' action='addpatient.php' onsubmit='return true'>
                    <button type="submit" class="btn btn-outline-warning ">Add Patient</button>
                </form>
            </div>
            <div class="form-inline my-2 ml-lg-2">
                <form method='post' action='logout.php' onsubmit='return true'>
                    <button type="submit" class="btn btn-outline-success ">Log Out</button>
                </form>
            </div>
        </div>
    </nav>



  <header>
    <a href="main.php">
      <img src="images/logo.png"  class="logo">
    </a>
  </header>
  <div class="container">
      <form method="post" action="addRoom.php" class="addprescription"> <?php $error?>
          <div class="formHeader">Add a new room</div>
          <div class="form">
		    <div class="form-group">
                  <label for="roomNmb">Room Number</label>
                  <input type="text" maxlength="64" id="roomNmb" class="form-control" name="roomNmb" value="<?php $roomNmb?>" required="required">
            </div>
              <div class="form-group">
                  <label for="dept">Department: </label>
                  <select name = 'dept' class="form-control">
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
              </div>

              <div class="form-group">
                  <label for="roomDescript">Desciption</label>
                  <input type="text" maxlength="150" id="roomDescript" class="form-control" name="roomDescript" value="<?php $roomDescript?>" required="required">
              </div>

              <div class="form-group">
                  <label for="maxCap">Maximum Capacity</label>
                  <input type="text" maxlength="10" id="maxCap" class="form-control" name="maxCap" value="<?php $maxCap?>" required="required">
              </div>
              
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
    <footer class="footer">
        <div class="container-fluid"><i class="fas fa-user"> </i> Logged in as: <?php echo "$_SESSION[user]";?>
        </div>
    </footer>
</html>