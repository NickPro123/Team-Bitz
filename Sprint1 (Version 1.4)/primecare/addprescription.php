<?php
session_start();

require_once 'functions.php';
if(!isset($_SESSION['user'])){
    $page = "login.php";
}

if (isset($_POST['drugID'])){
      
        $drugID = $dose = $freq = $end = "";

 
    $drugID = sanitizeString($_POST['drugID']);
    $dose = sanitizeString($_POST['dose']);
    $freq = sanitizeString($_POST['freq']);
    $end = sanitizeString($_POST['end']);
    
    

    if ($drugID == "" || $dose == "" || $freq == "")
        $error = "Not all fields were entered<br>";
    else{
        
        $stmt = $connection->prepare('call spAddPrescription(?,?,?,?,?)');				
	   $stmt->bind_param('iidis',$_SESSION['patientID'],$drugID, $dose, $freq, $end);
        $stmt->execute();
         if (!$stmt) {
               echo "There was a error with your data <a href='main.php'>click here</a> to return to the main menu.<br>";
         die($connection->error);
         }
         else
         {
             $page = "viewPrescriptions.php";
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
      <form method="post" action="addprescription.php" class="addprescription"> <?php $error?>
          <div class="formHeader">Add a new Prescription</div>
          <div class="form">
              <label for="drugName" >Drug Name</label>
              <input type="hidden" name="patientID" value="<?php echo $_SESSION['patientID']; ?>" >
			  <select name = 'drugID'>
				  <?php 
				  	$drugList = getDrugs();
		  			while($drugOption = $drugList->fetch_row())
					{
						echo "<option value = '";
						$isFirst = 1;
						foreach($drugOption as $drugField)
						{
							if($isFirst)
							{
								echo $drugField;
								echo "' > ID: $drugField";
								$isFirst = 0;
									
							}
							else
							{
								echo " | $drugField ";
							}
						}
						echo "</option>";
					}
				  ?>
			  </select>

              <label for="lastName">Dose(mg)</label>
              <input type="text" maxlength="64" id="dose" name="dose" value="<?php $dose?>" required="required">

              <label for="rmNumber">Frequency(per day)</label>
              <input type="text" maxlength="128" id="freq" name="freq" value="<?php $freq?>" required="required">
              
              <label for="endDate">End Date</label>
              <input type="date" id="end" name="end" value="<?php $end ?>">
              
                    <button type="submit" class="btn btn-outline-success ">Add Prescription</button>
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