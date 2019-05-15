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
        
        $stmt = $connection->prepare('call spAddPrescription(?,?,?,?,?,?)');				
	   $stmt->bind_param('iidisi',$_SESSION['patientID'],$drugID, $dose, $freq, $end, $_SESSION['id']);
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link href="https://fonts.googleapis.com/css?family=Lato|Raleway:400,700,900" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Prime Health Care - Add Prescription</title>
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
                        <!-- <a class="dropdown-item" href="addprescription.php">Add Prescriptions</a> -->
                        <a class="dropdown-item" href="viewCurrentDiagnosis.php">View Current Diagnosis</a>
                        <a class="dropdown-item" href="adddiagnosis.php">Add Diagnosis</a>
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
    <!-- Breadcrumb-->
    <!--<div class="mr-auto"> -->
    <!--    <nav aria-label="breadcrumb"> -->
            <!--<ol class="breadcrumb clearfix d-none d-md-inline-flex pt-0"> -->
                <!--<li class="breadcrumb-item"><a href="main.php">Main Menu</a><i class="fas fa-caret-right mx-2" aria-hidden="true"></i></li> -->
            <!--    <li class="breadcrumb-item"><a href="viewPrescriptions.php">View Prescription</a><i class="fas fa-caret-right mx-2" aria-hidden="true"></i></li> -->
        <!--        <li class="breadcrumb-item active">Add New Prescription</li> -->
    <!--        </ol> -->
    <!--    </nav> -->

  <header>
    <a>
      <img src="images/logo.png"  class="logo">
    </a>
  </header>
  <div class="container">
      <form method="post" action="addprescription.php" id="addform" class="addprescription"> <?php $error?>
          <div class="formHeader">Add a new Prescription</div>
          <!--Add Perscription Form-->
          <div class="form">
              <div class="form-group">
                  <label for="drugName" >Drug Name</label>
                  <input type="hidden" name="patientID" class="form-control" value="<?php echo $_SESSION['patientID']; ?>" >
                  <select id="drugOption" name = 'drugID' class="form-control">
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
              </div>
              <div class="form-group">
                  <label for="dose">Dose(mg)</label>
                  <input type="text" maxlength="64" id="dose" name="dose" class="form-control" value="<?php $dose?>" required="required">
              </div>

              <div class="form-group">
                  <label for="freq">Frequency(per day)</label>
                  <input type="text" maxlength="128" id="freq" name="freq" class="form-control" value="<?php $freq?>" required="required">
              </div>

              <div class="form-group">
                  <label for="end">End Date</label>
                  <input type="date" id="end" name="end" class="form-control" value="<?php $end ?>">
              </div>

              <button type="submit" id="addBtn" class="btn btn-outline-success " >Add Prescription</button>
      </form>
  </div>
      <!--}else{
         echo "There is no data to be displayed please <a href='main.php'>add</a> some.";
      }-->

        <!-- Optional JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        		<script>
		function validate()
		{
		    var doseInput;
		    doseInput = dose.value;
		    if(isNaN(doseInput))
		    {
		        alert("Dose must be a number.");
		        return false;
		    }
		    else if(doseInput <= 0)
		    {
		        alert("Dose must be greater than zero.");
		        return false;
		    }
		    
		    var freqInput;
		    freqInput = $("#freq").val();
		    if(isNaN(freqInput))
		    {
		        alert("Frequency must be a number.");
		        return false;
		    }
		    else if(freqInput <= 0)
		    {
                alert("Frequency must be greater than zero.");
                return false;
		    }
		    
		    else if(Math.floor(freqInput) != freqInput)
            {
                alert("Frequency must be an interger.") 
                return false;
            }
		    
			var currentDate = new Date().toJSON().slice(0,10).replace(/-/g,'-');
			if (end.value == "")
			{
				alert("You didn't enter a date. Please enter one.");
				return false;
			}
			else if (end.value < currentDate)
			{
				alert("You entered a date earlier than " + currentDate + ". Please try again.");
				return false;
			}
			
			return true;
		}
		
		$(document).ready(function(){
		    var submitReady = 0;
		    $("#addform").submit(function(e){
		        if(submitReady)
		        {
		            return;
		        }
		        else
		        {
		            e.preventDefault();
		        }
		    });
		    
		    $("#addBtn").click(function(){
		       if(validate())
		       {
		           var drugSelected = $("#drugOption").val();
		           console.log(drugSelected);
		           $.ajax({
                    url: "checkPrescription.php",
                            type: "post",
                            data: {patientID: <?php echo $_SESSION['patientID']; ?>, userID: <?php echo $_SESSION['id']; ?>,drugID: drugSelected },
                            dataType: 'json',
                            success:function(response){
                                var len = response.length;
                                
                                if(len > 0)
                                {
                                    alert("Warning! You have assigned this patient the selected medication, either edit the existing prescription or mark the current prescription as inactive.");
                                }
                                else
                                {
                                    submitReady = 1;
                                    $("#addform").submit();
                                }
                            }
                });
		       }
		    });
		});
		</script>
    </div>
    <footer class="footer">
        <div class="container-fluid"><i class="fas fa-user"></i> Logged in as: <?php echo "$_SESSION[user]";?>
        </div>
    </footer>
</html>