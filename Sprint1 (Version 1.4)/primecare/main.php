<?php
session_start();
require_once 'functions.php';
if(!isset($_SESSION['user'])){
    header("Location:login.php");
}
if(isset($_POST['showInactive']))
{
	if($_POST['showInactive'] == "Show Inactive Patients")
	{
		$result = queryMysql("select * from patient");
	}
	
	else
	{
    	$result = queryMysql("select * from patient WHERE roomNumber IS NOT NULL");
    	echo "<script> console.log('ELSE FIRING FROM LINE 14'); </script>";
	}
    
}

else
{
	$result = queryMysql("SELECT * FROM patient WHERE roomNumber IS NOT NULL");
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" type="text/css" href="css/style.css">

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

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
          <h1>Doctor Main Menu</h1>
          
          <div class="searchBarText" style= "width: 50%; float: left;">
			<input type='text' id="searchBarInput" onKeyUp="patientSearch();" placeholder="Enter your search term">
			</div>
			  
		  <div class= "searchBarText" style = "width: 50%; margin-top: 8px; float: right;">
			  <select id="searchCat">
				
				<option value=0>Patient ID</option>
				<option value=1>First Name OR Last Name</option>
				<option value=2>Room Number</option>
		<!--		<option value=3>Department</option> -->
				
			</select>
	      </div>
			  
		  
			
			 <div class="toggleInactivePatientDiv" style= "width: 50%;  margin-left: 25%; margin-right: 25%;">
			  <form method="post" onsubmit="return true" action="main.php">
			<input type="submit" name="showInactive" onChange="this.form.submit();" <?php 
								if(isset($_POST['showInactive']))
								{
									if($_POST['showInactive'] == "Show Inactive Patients")
									{
										echo "value = 'Hide Inactive Patients'";
									}
									else
									{
										echo "value = 'Show Inactive Patients'";
									}
								}
								else
								{
									echo "value = 'Show Inactive Patients'";
								}?>   />
			</form>
		  </div>
		</div>
		  
      <?php if ($result->num_rows > 0)
      {
          ?>
         <table id="patientViewTable" class="table table-striped">
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Room Number</th>

                  <th></th>
              </tr>
        <?php
		$tableIndex = 1;
        while ($row = mysqli_fetch_assoc($result)){
            ?>
              <tr>
                <td><input type='hidden' id="patientID<?php echo $tableIndex ?>" value="<?php echo $row['patientID'] ?>"> <?php echo "$row[patientID]";?> </td>
                <td>
					 <input type='hidden' id="patientFName<?php echo $tableIndex ?>" value="<?php echo "$row[firstName]"; ?>" >
					 <a id="fNameTableVal<?php echo $tableIndex ?>"><?php echo "$row[firstName] ";?></a>

				 	 <input type='hidden' id="patientLName<?php echo $tableIndex ?>" value="<?php echo "$row[lastName]"; ?>" >
					<a id="lNameTableVal<?php echo $tableIndex ?>"><?php echo "$row[lastName]";?></a>

				</td>
                <td>
					<input type='hidden' id="patientRm<?php echo $tableIndex ?>" value="<?php echo "$row[roomNumber]"; ?>">
					<a id="roomTableVal<?php echo $tableIndex ?>"><?php echo "$row[roomNumber]";?></a>
				</td>
                  <td><button id= "detailBtn" onclick="openPopupMenu(<?php echo $tableIndex ?>)" class="btn btn-outline-success">Details</button>

                  <form method='post' action='history.php' onsubmit='return true'> 
                  <button id="historyBtn" name="patientID" value="<?php echo "$row[patientID]" ?>" class="btn btn-outline-success">History</button>
                  </form>
                  </td>
                    

              </tr>

            <?php ; $tableIndex++; } ?>

          </table>

          <form method='post' action='addpatient.php' onsubmit='return true'>                       
                    <button type="submit" class="btn btn-outline-success ">Add Patient</button>     
                </form>                                                                             
        </div>

    </div>
            <!--Patient Details Popup-->
            <div id="popup_bg">
                <div class="popup_main_div">
                    <div class="popup_header">Patient Detail
                    </div>
                    <div class="popup_main">
                        <form>
                            <div class="form-row">
                                <div class="col">
                                    ID: <br>
                                    <input type="text" id="detailPatientID" name="detailPatientID" readonly="readonly">
                                </div>
                                <div class="col">
                                    First Name: <br>
                                    <input type="text" id="detailPatientFName" name="detailPatientFName" readonly="readonly"><br>
                                </div>
                                <div class="col">
                                    Last Name: <br>
                                    <input type="text" id="detailPatientLName" name="detailPatientLName" readonly="readonly"><br>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    Room Number:<br>
                                    <input type="text" id="detailPatientRm" name="detailPatientRoom" readonly="readonly"><br>
                                </div>
                            </div>
                        </form>
                        <img id= "editBtn" src="images/edit_mode.png" style="cursor: pointer; max-width: 50px; max-height: 50px; margin-left:10px; margin-top: 10px;" onClick="enableEditMode();" >
                        <button id = "saveBtn" onClick="saveDetails();">Save Changes</button>
                        <a id="successMessage"></a>
                    </div>
                    <div id="close_popup_div" onclick="closePopupMenu()">
                        <p title="Close Detail Menu" >
                            X
                        </p>
                    </div>
                </div>
        </div>
    <!--Patient History-->

    <div id="popup_history_bg">
        <div class="popup_main_div">
            <div class="popup_header">Patient History</div>
            <div class="popup_main">
                <table class="table table-striped">
                    <tr>
                        <th>Date</th>
                        <th>Assigned</th>
						<th>Info</th>
						<th>More Info</th>
                    </tr>
		<?php
			$tableDex = 1;
			//call function to return 2d array w/ dates
			
		//	while ($row = getHistory($tableDex)){
        ?>
					<tr>
                      
						<td><input type='hidden' id="date" name = "date"</td>
       					<td><input type='hidden' id="assigned" name = "assigned"</td>
						<td><input type='hidden' id="info"  name = "info"</td>
						<td><input type='hidden' id="minfo" name = "minfo"</td>

                        
                    </tr>
					
		<?php ; $tableDex++; /*}*/ ?>
                </table>
                <img id = "editHistoryBtn" src="images/edit_mode.png" style="cursor: pointer; max-width: 50px; max-height: 50px; margin-left:10px; margin-top: 10px;" onClick="enableHistoryEditMode();" >
                <button id = "saveHistoryBtn" onClick="saveHistoryChanges();">Save Changes</button>
                <a id="successMessage"></a>
            </div>
            <div id="close_popup_div" onclick="closeHistoryPopupMenu()">
                <p title="Close Detail Menu" >
                    X
                </p>
            </div>
        </div>
    </div>
        <!-- Scripting to display and hide patient detail popup menu -->
    <script type="text/javascript">
        var popup = document.getElementById("popup_bg");

        var historyPopup = document.getElementById("popup_history_bg");





        var IDMenuItem = document.getElementById("detailPatientID");

		var fNameMenuItem = document.getElementById("detailPatientFName");

		var lNameMenuItem = document.getElementById("detailPatientLName");

		var roomMenuItem = document.getElementById("detailPatientRm");



        var patientIDHistoryItem = document.getElementById("patientIDHistory");

        var fNameHistoryItem = document.getElementById("detailPatientFName");

        var lNameHistoryItem = document.getElementById("detailPatientLName");

        var roomHistoryItem = document.getElementById("detailPatientRm");



		var isEditing = false;

		var patientID;
		var patientFName;
		var patientLName;
		var patientRm;
		var recordIndex;

		var saveBtn = document.getElementById("saveBtn");

        var saveHistoryBtn = document.getElementById("saveHistoryBtn");

		var successMsg = document.getElementById("successMessage");

		
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

			

			saveBtn.style.visibility= 'hidden';

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

			var IDIndex = "patientID" + index +"";

			var fNameIndex = "patientFName" + index + "";

			var lNameIndex = "patientLName" + index + "";

			var roomIndex = "patientRm" + index + "";

			

			patientID = document.getElementById(IDIndex);

			patientFName = document.getElementById(fNameIndex);

			patientLName = document.getElementById(lNameIndex);

			patientRm = document.getElementById(roomIndex);

						

			IDMenuItem.value = patientID.value;

			fNameMenuItem.value = patientFName.value;

			lNameMenuItem.value = patientLName.value;

			roomMenuItem.value = patientRm.value;

			

		}



        function closePopupMenu()



        {

            disableEditMode();

            popup.style.display = "none";
			
			// un-lock scroll position
			var html = jQuery('html');
			var scrollPosition = html.data('scroll-position');
			html.css('overflow', html.data('previous-overflow'));
			window.scrollTo(scrollPosition[0], scrollPosition[1])

            successMsg.innerHTML = "";



        }





        function enableEditMode()

        {

            if(!isEditing)

            {

                editBtn.src = "images/enable_edit_mode.png";

                saveBtn.style.visibility = 'visible';

                fNameMenuItem.readOnly = false;

                lNameMenuItem.readOnly = false;

                roomMenuItem.readOnly = false;

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

                fNameMenuItem.readOnly = true;

                lNameMenuItem.readOnly = true;

                roomMenuItem.readOnly = true;

                isEditing = false;

            }

        }

		$(document).ready(function(){
			$("#saveBtn").click(function(){
				var pk = patientID.value;
				var newFName = fNameMenuItem.value;
				var newLName = lNameMenuItem.value;
				var newRoom = roomMenuItem.value;
				var updatedRecordFName = document.getElementById("fNameTableVal" + recordIndex);
				var updatedRecordLName = document.getElementById("lNameTableVal" + recordIndex);
				var updatedRecordRoom = document.getElementById("roomTableVal" + recordIndex);
			
				$.ajax({
					url: "saveChanges.php",
					method: "post",
					data: { primaryKey: pk, fname: newFName, lname: newLName, room: newRoom},
					success: function(response){
						console.log(response);
						$(updatedRecordFName).text(newFName);
						$(updatedRecordLName).text(newLName);
						$(updatedRecordRoom).text(newRoom);
						
						$(patientFName).val(newFName);
						$(patientLName).val(newLName);
						$(patientRm).val(newRoom);
					}
				});
			});
		});
			
		

        function saveDetails()

        {

            if(isEditing)

            {

                disableEditMode();
				
                closePopupMenu();

            }

        }

        /*Patient History*/



        function openHistoryPopupMenu(index)



        {

            historyPopup.style.display="block";



            saveHistoryBtn.style.visibility= 'hidden';





            displayPatientHistory(index);



        }



        function displayPatientHistory(index)

        {

            var patientIDHistoryIndex = "patientID" + index +"";

            var patientFNameHistoryIndex = "patientFName" + index + "";

            var lNameIndex = "patientLName" + index + "";

            var roomIndex = "patientRm" + index + "";



            patientIDHistory = document.getElementById(patientIDHistoryIndex);

            var patientFNameHistory = document.getElementById(patientFNameHistoryIndex);

            var patientLNameHistory = document.getElementById(lNameIndex);

            var patientRmHistory = document.getElementById(roomIndex);



            //patientIDHistoryItem.value = patientID.value;

            fNameHistoryItem.value = patientFNameHistory.value;

            lNameHistoryItem.value = patientLNameHistory.value;

            roomHistoryItem.value = patientRmHistory.value;



        }



        function enableHistoryEditMode()

        {

            if(!isEditing)

            {

                editHistoryBtn.src = "images/enable_edit_mode.png";

                saveHistoryBtn.style.visibility = 'visible';

                fNameMenuItem.readOnly = false;

                lNameMenuItem.readOnly = false;

                roomMenuItem.readOnly = false;

                successMsg.innerHTML = "";

                isEditing = true;

            }

            else

            {

                disableHistoryEditMode();

            }

        }



        function disableHistoryEditMode()

        {

            if(isEditing)

            {

                editHistoryBtn.src = "images/edit_mode.png";

                saveHistoryBtn.style.visibility = 'hidden';

                fNameMenuItem.readOnly = true;

                lNameMenuItem.readOnly = true;

                roomMenuItem.readOnly = true;

                isEditing = false;

            }

        }



        function closeHistoryPopupMenu()



        {

            disableEditMode();

            historyPopup.style.display = "none";

            successMsg.innerHTML = "";



        }





        function saveHistoryChanges()

        {

            if(isEditing)

            {

                disableEditMode();



                var newPatientFName;

                var newPatientLName;

                var newPatientRoom;



                newPatientFName = fNameMenuItem.value;

                newPatientLName = lNameMenuItem.value;

                newPatientRoom = roomMenuItem.value;



                //<?php //updatePatientRecord( ?> patientID.value <?php //, ?> newPatientFName.value <?php// , ?> newPatientLName.value <?php// , ?> newPatientRoom.value <?php //) ?>





                successMsg.innerHTML = "Changes Saved!";

                closePopupMenu();

            }

        }



    </script>



      <!--}else{



         echo "There is no data to be displayed please <a href='main.php'>add</a> some.";



      }-->



<?php } ?>







        <!-- Optional JavaScript -->






        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>



        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>



    </div>

    </div>

</body>



    <footer class="footer">

        <div class="container-fluid"> Logged in as: <?php echo "$firstName + $lastName";?>

        </div>

    </footer>



</html>