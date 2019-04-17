<?php
session_start();
require_once 'functions.php';
if(!isset($_SESSION['user'])){
    header("Location:login.php");
}

if(isset($_POST['patientID']))
{
    $_SESSION['patientID'] = $_POST['patientID'];
}
?>
<!DOCTYPE html>
<html lang="en">
 <head>
        <link rel="stylesheet" type="text/css" href="css/style.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
     <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
     <link href="https://fonts.googleapis.com/css?family=Lato|Raleway:400,700,900" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Prime Health Care - History</title>
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
        <img src="images/logo.png" class="logo">
    </header>
<body>
    <div class="container">
        <div class="center">
        
          <h1>Patient History for <?php echo "$nameOfPatient[firstName] $nameOfPatient[lastName]";?></h1>

            <!--Search Bar-->
            <div class="input-group mb-3">
                <span class="input-group-prepend">
                    <div class="input-group-text bg-transparent border-right-0">
                        <i class="fa fa-search"></i>
                    </div>
                </span>
                <input type="text" class="form-control border-left-0 mt-0 h-100" id="searchBarInput" onKeyUp="patientSearch();" placeholder="Enter your search term">
                <div class="input-group-append">
                    <select id="searchCat" class="form-control mt-0 h-100">
                        <option value=0>Date</option>
                        <option value=1>Assigned To</option>
                        <option value=3>Info</option>
                    </select>
                </div>
            </div>
        </div>

     
         <table id="historyTable" class="table table-striped">
              <tr>
            <th>Date</th>
            <th>Assigned To</th>
            <th>Info</th>
            <th></th>
                    
              </tr>
              <?php
		$tableIndex = 1;
		$row = getHistory($_SESSION['patientID']);
         $sort_column = array();
            foreach ($row as $a){
         if(!($a == ''))
            {
             $sort_column []= $a[0]; // 1 = your example
            }
            }
        array_multisort($sort_column, $row);
        foreach ($row as $record){
           if(!($record[0] == '')){
            //fix the columns after this
            ?>
              <tr>
                <td><input type='hidden' id="date<?php echo $tableIndex ?>" value="<?php echo $record[0] ?>"> 
                <?php echo $record[0];?> </td>
                <td>
                <input type='hidden' id="assigned<?php echo $tableIndex ?>" value="<?php echo "$record[1]"; ?>" >
		<a id="assignedTableVal<?php echo $tableIndex ?>"><?php echo "$record[1] ";?></a>
            </td>
            
            <td>
		<input type='hidden' id="info<?php echo $tableIndex ?>" value="<?php echo "$record[2]"; ?>" >
		<a id="infoTableVal<?php echo $tableIndex ?>"><?php echo "$record[2]";?></a>
		</td>
                <td>
		<input type='hidden' id="moreInfo<?php echo $tableIndex ?>" value="<?php echo "$record[3]"; ?>">
		<a id="mInfoTableVal<?php echo $tableIndex ?>"><?php echo "$record[3]";?></a>
		</td>
                </tr>
        <?php }} ?>
        
        </table>
        <!-- Optional JavaScript -->
        
        <script type="text/javascript">
        function patientSearch()
		{
			var input, filter, table, tr, td, i , textVal;
			input = document.getElementById("searchBarInput");
			filter = input.value.toUpperCase();
			table = document.getElementById("historyTable");
			tr = table.getElementsByTagName("tr");
			categoryDropDown = document.getElementById("searchCat");
			category = categoryDropDown.value;
			
			//loop through all table rows, and hide those that dont match
			for(i = 0; i < tr.length; i++)
				{
					//category is the search option selected in the dropdown menu
					td = tr[i].getElementsByTagName("td")[category];
					if(td)
						{
							textVal = td.textContent || td.innerText;
							if(textVal.toUpperCase().indexOf(filter) > -1)
								{
									tr[i].style.display = "";
								}
							else
							{
								tr[i].style.display = "none";
							}
						}
				}
		}
        
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </div>
    </div>
</body>
    <footer class="footer">
        <div class="container-fluid"><i class="fas fa-user"></i> Logged in as: <?php echo "$_SESSION[user]";?>
        </div>
    </footer>
</html>