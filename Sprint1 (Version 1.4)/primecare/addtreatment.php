<?php
session_start();

require_once 'functions.php';
if(!isset($_SESSION['user'])){
    $page = "login.php";
}

if (isset($_POST['treatmentID'])){
      
        $treatmentID = $start = $inst = "";

 
    $treatmentID = sanitizeString($_POST['treatmentID']);
    $inst = sanitizeString($_POST['instructions']);
  
    
        $start = sanitizeString($_POST['start']);

    
    

    if ($treatmentID == "" || $inst == "" || $start == "")
        $error = "Not all fields were entered<br>";
    else{
        
        $stmt = $connection->prepare('call spAddTreatment(?,?,?,?,?)');				
	   $stmt->bind_param('iissi',$_SESSION['patientID'],$treatmentID, $start, $inst, $_SESSION['id']);
        $stmt->execute();
         if (!$stmt) {
               echo "There was a error with your data <a href='main.php'>click here</a> to return to the main menu.<br>";
         die($connection->error);
         }
         else
         {
             $page = "viewTreatments.php";
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
        <title>Prime Health Care - Add Treatment</title>
    </head>

    <!--Navbar-->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="main.php">Prime Care</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="main.php">Main Menu</a>
                </li>
                <!-- Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false"><?php $nameOfPatient = getPatientName($_SESSION['patientID']);
                        echo "$nameOfPatient[firstName] $nameOfPatient[lastName]";?></a>
                    <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="viewPrescriptions.php">View Prescriptions</a>
                        <a class="dropdown-item" href="addprescription.php">Add Prescriptions</a>
                        <a class="dropdown-item" href="viewTests.php">View Tests</a>
                        <a class="dropdown-item" href="addtest.php">Add Test</a>
                        <a class="dropdown-item" href="viewTreatments.php">View Treatments</a>
                        <a class="dropdown-item" href="addtreatment.php">Add Treatment</a>
                        <a class="dropdown-item" href="history.php">View History</a>
                    </div>
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
      <form method="post" action="addtreatment.php" class="addtreatment"> <?php $error?>
          <div class="formHeader">Add a new Treatment</div>
          <div class="form">
              <div class="form-group">
                  <label for="treatmentID" >Treatment Name</label>
                  <input type="hidden" name="patientID" value="<?php echo $_SESSION['patientID']; ?>" >
                  <select name = 'treatmentID' class="form-control">
                      <?php
                        $treatmentList = getTreatment();
                        while($treatmentOption = $treatmentList->fetch_row())
                        {
                            echo "<option value = '";
                            $isFirst = 1;
                            foreach($treatmentOption as $treatmentField)
                            {
                                if($isFirst)
                                {
                                    echo $treatmentField;
                                    echo "' > ID: $treatmentField";
                                    $isFirst = 0;

                                }
                                else
                                {
                                    echo " | $treatmentField ";
                                }
                            }
                            echo "</option>";
                        }
                      ?>
                  </select>
              </div>

              <div class="form-group">
                  <label for="instructions">Instructions</label>
                  <input type="text" maxlength="128" id="instructions" class="form-control" name="instructions" value="<?php $inst?>" required="required">
              </div>

              <div class="form-group">
                  <label for="start">Start Date</label>
                  <input type="date" id="start" class="form-control" name="start" value="<?php $start ?>">
              </div>
              
              <button type="submit" class="btn btn-outline-success ">Add Treatment</button>
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
        <div class="container-fluid"> <i class="fas fa-user"></i> Logged in as: <?php echo "$_SESSION[user]";?>
        </div>
    </footer>
</html>