<?php
session_start();
require_once 'functions.php';
if(!isset($_SESSION['user'])){
    header("Location:login.php");
}
if(isset($_POST['patientID']) || isset($_SESSION['patientID']))
{
    if(isset($_POST['patientID']))
    {
	    $id = $_POST['patientID'];
	    $_SESSION['patientID'] = $id;
    }
	else if(isset($_SESSION['patientID']))
	    $id = $_SESSION['patientID'];
	    
	$result = queryMysql("SELECT d.drugID, d.medicineName, pap.dose, pap.timesPerDay, pap.userID, u.lastName
	                      FROM drug as d 
	                      JOIN prescriptionassignedtopatient as pap 
	                      ON d.drugID = pap.drugID 
	                      JOIN patient as pat 
	                      ON pap.patientID = pat.patientID 
	                      JOIN user AS u
	                      ON pap.userID = u.userID
	                      WHERE pat.patientID = '". $id ."'");  
}
else
	{
    	header("Location:main.php");
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
        <title>Prime Health Care - View Prescriptions</title>
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
                       <!-- <a class="dropdown-item" href="viewPrescriptions.php">View Prescriptions</a> -->
                         <?php if(isset($_SESSION['doctor'])){ ?>
                        <a class="dropdown-item" href="addprescription.php">Add Prescriptions</a>
                        <?php }?>
                        <a class="dropdown-item" href="viewCurrentDiagnosis.php">View Current Diagnosis</a>
                        <?php if(isset($_SESSION['doctor'])){ ?>
                        <a class="dropdown-item" href="adddiagnosis.php">Add Diagnosis</a>
                        <?php }?>
                        <a class="dropdown-item" href="viewTests.php">View Tests</a>
                         <?php if(isset($_SESSION['doctor'])){ ?>
                        <a class="dropdown-item" href="addtest.php">Add Test</a>
                        <?php }?>
                        <a class="dropdown-item" href="viewTreatments.php">View Treatments</a>
                         <?php if(isset($_SESSION['doctor'])){ ?>
                        <a class="dropdown-item" href="addtreatment.php">Add Treatment</a>
                        <?php }?>
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
          <h1><?php $nameOfPatient = getPatientName($_SESSION['patientID']); 
			echo "$nameOfPatient[firstName] $nameOfPatient[lastName]";?> - Prescription Menu </h1>
                <?php if ($result->num_rows > 0)
      {
          ?>
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
                        <option value=1>Drug ID</option>
                        <option value=2>Drug Name</option>
                        <option value=3>Dose</option>
                        <option value=4>Frequency</option>
                    </select>
                </div>
            </div>
		</div>
		  
          
         <table id="patientViewTable" class="table table-striped">
              <tr>
				  <th></th>
                <th>ID</th>
                <th>Drug Name</th>
                <th>Dose</th>
                <th>Frequency</th>
                <th>Prescribed By</th>
                  <th></th>
				  
              </tr>
              
        <?php
		$tableIndex = 1;
        while ($row = mysqli_fetch_assoc($result)){
            ?>
            
              <tr>
				<td></td>
                <td><input type='hidden' id="drugID<?php echo $tableIndex ?>" value="<?php echo $row['drugID'] ?>"> <?php echo "$row[drugID]";?> </td>
                <td>
					 <input type='hidden' id="drugName<?php echo $tableIndex ?>" value="<?php echo "$row[medicineName]"; ?>" >
					 <a id="drugNameVal<?php echo $tableIndex ?>"><?php echo "$row[medicineName] ";?></a>
				</td>
				  <td>
				 	 <input type='hidden' id="medicineDose<?php echo $tableIndex ?>" value="<?php echo "$row[dose]"; ?>" >
					<a id="medicineDoseVal<?php echo $tableIndex ?>"><?php echo "$row[dose]";?></a><a> mg</a>
				</td>
                <td>
					<input type='hidden' id="freq<?php echo $tableIndex ?>" value="<?php echo "$row[timesPerDay]"; ?>" >
					<a id="freqVal<?php echo $tableIndex ?>"><?php echo "$row[timesPerDay]";?></a><a> times per day</a>
				</td>
				<td>
				    <input type="hidden" id="userID<?php echo $tableIndex ?>" value="<?php echo "$row[userID]"; ?>" >
				    <a>Dr. </a><a id="userNameVal<?php echo $tableIndex ?>"><?php echo "$row[lastName]"; ?></a>
				</td>
                  <td class="btnCol">
                      
                      <button id= "detailBtn" onclick="openPopupMenu(<?php echo $tableIndex ?>)" class="btn btn-outline-success">Details</button>
                </td>
              </tr>
            <?php ; $tableIndex++; } }else { echo "<div class='container style=float: left;'>There are currently no perscriptions assigned to this patient."; if(isset($_SESSION['doctor'])){ echo " Assign a perscription below."; } echo "</div>"; } ?>
</table>
          
     <?php if(isset($_SESSION['doctor'])){ ?>
          <form method='post' action='addprescription.php' onsubmit='return true'>                       
                    <button type="submit" name="patientID" value="<?php echo $id; ?>"class="btn btn-outline-success ">Add Prescription</button>     
                </form>                
             <?php }?>
        </div>
    </div>
            <!--Prescription Details Popup-->
            <div id="popup_bg">
                <div class="popup_main_div">
                    <div class="popup_header">Prescription Detail
                    </div>
                    <div class="popup_main">
                        <form>
                            <div class="form-row">
                                <div class="col">
                                    ID: <br>
                                    <input type="text" id="detailDrugID" class="form-control" name="detailDrugID" readonly="readonly">
                                </div>
                                <div class="col">
                                    Medicine Name: <br>
                                    <input type="text" id="detailDrugName" class="form-control" name="detailDrugName" readonly="readonly"><br>
                                </div>
                                <div class="col">
                                    Dose: (mg)<br>
                                    <input type="text" id="detailDose" class="form-control" name="detailDose" readonly="readonly"><br>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    Frequency: (times per day)<br>
                                    <input type="text" id="detailFreq" class="form-control" name="detailFreq" readonly="readonly"><br>
                                </div>
                            </div>
                        </form>
                     <?php   if(isset($_SESSION['doctor'])){ ?>

                        <img id= "editBtn" src="images/edit_mode.png" style="cursor: pointer; max-width: 50px; max-height: 50px; margin-left:10px; margin-top: 10px;" onClick="enableEditMode();" >
                        <button id = "saveBtn">Save Changes</button>
                        <a id="successMessage"></a>
                        <?php }?> 
                    </div>
                    <div id="close_popup_div">
                        <p title="Close Detail Menu" >
                            X
                        </p>
                    </div>
                </div>
        </div>
  
        <!-- Scripting to display and hide patient detail popup menu -->
    <script type="text/javascript">
        var popup = document.getElementById("popup_bg");
        var IDMenuItem = document.getElementById("detailDrugID");
		var nameMenuItem = document.getElementById("detailDrugName");
		var doseMenuItem = document.getElementById("detailDose");
		var freqMenuItem = document.getElementById("detailFreq");
		var isEditing = false;
		var drugID;
		var medicineName;
		var dose;
		var freq;
		var recordIndex;
		var saveBtn = document.getElementById("saveBtn");
        var saveHistoryBtn = document.getElementById("saveHistoryBtn");
		var successMsg = document.getElementById("successMessage");
		
		var patientID = <?php echo $_SESSION['patientID'] ?>
		
        //Pure JS search function
		function patientSearch()
		{
			var input, filter, table, tr, td, i , textVal;
			input = document.getElementById("searchBarInput");
			filter = input.value.toUpperCase();
			table = document.getElementById("patientViewTable");
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
		/*Patient Details*/
	    function openPopupMenu(index)
        {
			recordIndex = index;
			
            popup.style.display="block";
			 <?php   if(isset($_SESSION['doctor'])){ ?>
			saveBtn.style.visibility= 'hidden';
			<?php } ?>
			// lock scroll position, but retain settings for later
			var scrollPosition = [
  			self.pageXOffset || document.documentElement.scrollLeft || document.body.scrollLeft,
  			self.pageYOffset || document.documentElement.scrollTop  || document.body.scrollTop
			];
			
			var html = jQuery('html'); 
			html.data('scroll-position', scrollPosition);
			html.data('previous-overflow', html.css('overflow'));
			html.css('overflow', 'hidden');
			window.scrollTo(scrollPosition[0], scrollPosition[1]);
			displayPatientDetails(index);
        }
        function displayPatientDetails(index)
		{
			var IDIndex = "drugID" + index +"";
			var nameIndex = "drugName" + index + "";
			var doseIndex = "medicineDose" + index + "";
			var freqIndex = "freq" + index + "";
			
			drugID = document.getElementById(IDIndex);
			drugName = document.getElementById(nameIndex);
			dose = document.getElementById(doseIndex);
			freq = document.getElementById(freqIndex);
						
			IDMenuItem.value = drugID.value;
			nameMenuItem.value = drugName.value;
			doseMenuItem.value = dose.value;
			freqMenuItem.value = freq.value;
			
		}
        function closePopupMenu()
        {
             <?php   if(isset($_SESSION['doctor'])){ ?>
            disableEditMode();
            <?php } ?>
            popup.style.display = "none";
			
			// un-lock scroll position
			var html = jQuery('html');
			var scrollPosition = html.data('scroll-position');
			html.css('overflow', html.data('previous-overflow'));
			window.scrollTo(scrollPosition[0], scrollPosition[1])
            successMsg.innerHTML = "";
        }
         <?php   if(isset($_SESSION['doctor'])){ ?>
        function enableEditMode()
        {
            if(!isEditing)
            {
                editBtn.src = "images/enable_edit_mode.png";
                saveBtn.style.visibility = 'visible';
                doseMenuItem.readOnly = false;
                freqMenuItem.readOnly = false;
                successMsg.innerHTML = "";
                isEditing = true;
            }
            else
            {
                disableEditMode();
            }
        }
        function disableEditMode()
        {
            if(isEditing)
            {
                editBtn.src = "images/edit_mode.png";
                saveBtn.style.visibility = 'hidden';
                doseMenuItem.readOnly = true;
                freqMenuItem.readOnly = true;
                isEditing = false;
            }
        }
        
		$(document).ready(function(){
			$("#saveBtn").click(function(){
				var pk = drugID.value;
				var newDose = doseMenuItem.value;
				var newFreq = freqMenuItem.value;
				var updatedRecordDose = document.getElementById("medicineDoseVal" + recordIndex);
				var updatedRecordFreq = document.getElementById("freqVal" + recordIndex);
			
			    if($.isNumeric(newDose) && $.isNumeric(newFreq))
			    {
			        if( ( Math.sign(newDose) > 0 ) && ( Math.sign(newFreq) > 0 ) )
			        {
				        if(Math.floor(newFreq) == newFreq)
				        {
				            $.ajax({
    					        url: "saveChangesPrescriptions.php",
	    				        method: "post",
		    			        data: { patient: patientID, drugKey: pk, dose: newDose, freq: newFreq},
			    		        success: function(response){
				    	    	    console.log(response);
					        	    $(updatedRecordDose).text(newDose);
					        	    $(updatedRecordFreq).text(newFreq);
						   
    					     	    $(dose).val(newDose);
	    				        	$(freq).val(newFreq);
		    			    	    saveDetails();
			    		        }
				            });
				        }
				        else
				        {
				            alert("Frequency input must be an integer");
				        }
			        }
			        else
			        {
			            alert("Dose and Frequency inputs must be positive values")
			        }
			    }
			    else
			    {
			        alert("Dose and Frequency inputs must be numeric");
			    }
			});
		});
		<?php }?>
		
		//If in editing mode, and the close button is clicked confirm that the user doesnt want to save changes
		$(document).ready(function(){
		    $("#close_popup_div").click(function(){
		        if(isEditing)
		        {
		            var dialog = confirm("Are you sure you want to close? Any unsaved changes will be lost.");
		            
		            if(dialog == true)
		            {
		                closePopupMenu();
		            }
		            else
		            {
		                
		            }
		        }
		        
		        else
		        {
		            closePopupMenu();
		        }
		    });
		});
			
		 <?php   if(isset($_SESSION['doctor'])){ ?>
        function saveDetails()
        {
            if(isEditing)
            {
                disableEditMode();
				
                closePopupMenu();
            }
        }
        <?php } ?>
    </script>
      <!--}else{
         echo "There is no data to be displayed please <a href='main.php'>add</a> some.";
      }-->
        <!-- Optional JavaScript -->
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