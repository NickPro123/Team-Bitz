<?php

session_start();

require_once 'functions.php';

	$result = queryMysql("select * from room as r join department as d on d.departmentID = r.departmentID");  

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
		
		<form method='post' action='addRoom.php' onsubmit='return true'>                       
                 <button type="submit" class="btn btn-outline-success ">Add Room</button>     
         </form>

	<?php if ($result->num_rows > 0)
      {
          ?>
          <div class="searchBarText" style= "width: 50%; float: left;">

			<input type='text' id="searchBarInput" onKeyUp="patientSearch();" placeholder="Enter your search term">

			</div>

		  <div class= "searchBarText" style = "width: 50%; margin-top: 8px; float: right;">

			  <select id="searchCat">

				<option value=1>Room Number</option>

				<option value=2>DepartmentID</option>
				
				<option value=3>Department Name</option>

		<!--		<option value=3>Department</option> -->

			</select>

	      </div>
			
		</div>

         <table id="patientViewTable" class="table table-striped">

              <tr>

				  <th></th>
                <th>Room Number</th>

                <th>Department ID</th>
                
                <th>Department Name</th>

				<th>Room Description</th>
				
                <th>Max Capacity</th>

                <th>Current Capacity</th>

                  <th></th>
				  
              </tr>         

        <?php

		$tableIndex = 1;

        while ($row = mysqli_fetch_assoc($result))
		
		{

            ?>
			
              <tr>
				<td></td>
                <td><input type='hidden' id="roomNum<?php echo $tableIndex ?>" value="<?php echo $row['roomNumber'] ?>"> <?php echo "$row[roomNumber]";?> </td>

                <td>

					 <input type='hidden' id="deptID<?php echo $tableIndex ?>" value="<?php echo "$row[departmentID]"; ?>" >

					 <a id="departmentIDVal<?php echo $tableIndex ?>"><?php echo "$row[departmentID] ";?></a>
				</td>
				
				<td>

					 <input type='hidden' id="deptName<?php echo $tableIndex ?>" value="<?php echo "$row[departmentName]"; ?>" >

					 <a id="departmentNameVal<?php echo $tableIndex ?>"><?php echo "$row[departmentName] ";?></a>
				</td>
				
				<td>
					 <input type='hidden' id="roomDescription<?php echo $tableIndex ?>" value="<?php echo "$row[description]"; ?>" >
					 <a id="departmentIDVal<?php echo $tableIndex ?>"><?php echo "$row[description] ";?></a>
				</td>
				
				  <td>

				 	 <input type='hidden' id="maxCap<?php echo $tableIndex ?>" value="<?php echo "$row[maxCapacity]"; ?>" >

					<a id="maxCapVal<?php echo $tableIndex ?>"><?php echo "$row[maxCapacity]";?></a>

				</td>
                    
                <td>

					<input type='hidden' id="currentCap<?php echo $tableIndex ?>" value="<?php echo "$row[patientsAssigned]"; ?>" >

					<a id="currentCapVal<?php echo $tableIndex ?>"><?php echo "$row[patientsAssigned]";?></a>

				</td>
				
				<td>
				    <button id= "detailBtn" onclick="openPopupMenu(<?php echo $tableIndex ?>)" class="btn btn-outline-success">View</button>
				</td>

              </tr>

 <?php ; $tableIndex++; } }else echo "<div class='container style=float: left;'>There are currently no perscriptions assigned to this patient. Assign a perscription below </div>"?>

</table>

        </div>

		</div>
		
		<!--Patient Details Popup-->

            <div id="popup_bg">

                <div class="popup_main_div">

                    <div class="popup_header">Patients in Room #

                    </div>

                    <div class="popup_main">

                        <form>
                            <table class="table table-striped">
                                <tr>
                                    <th>Patient ID</th>
                                    <th>Patient Name</th>
                                </tr>
                            </table>
                        </form>

                        

                        <a id="successMessage"></a>

                    </div>

                    <div id="close_popup_div" onclick="closePopupMenu()">

                        <p title="Close Detail Menu" >

                            X

                        </p>

                    </div>

                </div>

        </div>

		
        <!-- Scripting to display and hide patient detail popup menu -->

    <script type="text/javascript">
	
	    var popup = document.getElementById("popup_bg");
        var descriptionMenuItem = document.getElementById("detailRoomDescription");
		
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



            popup.style.display = "none";

			

			// un-lock scroll position

			var html = jQuery('html');

			var scrollPosition = html.data('scroll-position');

			html.css('overflow', html.data('previous-overflow'));

			window.scrollTo(scrollPosition[0], scrollPosition[1])



            successMsg.innerHTML = "";







        }
        
    </script>

        <!-- Optional JavaScript -->

        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    </div>
    </div>
</body>
    <footer class="footer">
        <div class="container-fluid"> Logged in as: <?php echo "$_SESSION[user]";?>
        </div>
    </footer>
</html>