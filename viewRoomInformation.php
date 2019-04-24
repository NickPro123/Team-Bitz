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
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Lato|Raleway:400,700,900" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Prime Health Care - View Room Information</title>
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
                    <a class="nav-link" href="main.php">Main Menu<span class="sr-only">(current)</span></a>
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
            <h1>Room Information</h1>
            <div class="addRoom">
		<form method='post' action='addRoom.php' onsubmit='return true'>                       
                 <input type="submit"value="Add Room">
         </form>
         </div>
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
                        <option value=1>Room Number</option>
                        <option value=2>DepartmentID</option>
                        <option value=3>Department Name</option>
                    </select>
                </div>
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
				    <button id= "viewBtn" name="viewBtn" onclick="openPopupMenu(<?php echo $tableIndex ?>)" class="btn btn-outline-success">View</button>
				</td>
              </tr>
 <?php ; $tableIndex++; } }else echo "<div class='container style=float: left;'>There are currently no perscriptions assigned to this patient. Assign a perscription below </div>"?>
</table>
        </div>
		</div>
		
		<!--Patient Details Popup-->
            <div id="popup_bg">
                <div class="popup_main_div">
                    <div class="popup_header">Patients in Room #<a id="detailRoomNum"></a>
                    </div>
                    <div class="popup_main">
                        <form>
                            <table id="roomDetailTable" class="table table-striped">
                                <tr>
                                    <th></th>
                                    
                                    <th>Patient ID</th>
                                    <th>Patient Name</th>
                                    
                                    <th></th>
                                </tr>
                                
                                
                            </table>
                        </form>
                        
                        <div id="emptyMessage"></div>
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
        var RoomID;
        var patientCount;
        var detailTable = document.getElementById("roomDetailTable");
        
        var emptyMessage = document.getElementById("emptyMessage");
        
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
            
            RoomID = document.getElementById("roomNum" + index);
            patientCount = document.getElementById("currentCap" + index);
			
			//saveBtn.style.visibility= 'hidden';
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
		    document.getElementById("detailRoomNum").text = RoomID.value;
		    
		    
		    CheckIfEmpty();
        /*    
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
		*/	
		}
		
		function CheckIfEmpty()
		{
		    if(patientCount.value == 0)
		    {
		        detailTable.style.visibility="hidden";
		        emptyMessage.innerHTML = "There are no patients in this room.";   
		    }
		}
		function closePopupMenu()
        {
            popup.style.display = "none";
            
            detailTable.style.visibility = ""
			
			// un-lock scroll position
			var html = jQuery('html');
			var scrollPosition = html.data('scroll-position');
			html.css('overflow', html.data('previous-overflow'));
			window.scrollTo(scrollPosition[0], scrollPosition[1])
            emptyMessage.innerHTML = "";
        }
        //JQuery to display patients occupied in a room when the view button is clicked
        $(document).ready(function(){
            $("button[name=viewBtn]").click(function(){
                var room = RoomID.value;
                //Clear table
                $("#roomDetailTable").find("tr:gt(0)").remove();
                $.ajax({
                    url: "getOccupants.php",
                    type: "post",
                    data: {roomNum: room},
                    dataType: "json",
                    success:function(response){
                        var len = response.length;
                        
                        for(var i=0; i<len; i++)
                        {
                            //extract data from json
                            var patientID = response[i]['patientID'];
                            var name = response[i]['name'];
                            
                            //id for select patient btn
                            var selectBtnID = "viewPatientBtn" + i;
                            
                            //append rows to detailTable using data extracted
                            $("#roomDetailTable tr:last").after("<tr><td></td><td>"+patientID+"</td><td>"+name+"</td><td><form method='post' action='main.php' onsubmit='return true'><button value="+patientID+" name='patientID' class='btn btn-outline-success'>Select</button></form></td></tr>");
                        }
                        
                    }
                });
            });
        });
        
    </script>
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