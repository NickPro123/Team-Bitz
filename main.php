<?php
session_start();
require_once 'functions.php';
if(!isset($_SESSION['user'])){
    header("Location:login.php");
}

if(!isset($_POST['deptID']) && !isset($_SESSION['deptID']))
{
    $deptID = getUserDept($_SESSION['user']);
}
else if(isset($_POST['deptID']))
{
    $deptID = $_POST['deptID'];
    $_SESSION['deptID'] = $deptID;
}
else if(isset($_SESSION['deptID']))
{
    $deptID = $_SESSION['deptID'];
}

//in the event that the user doesnt have a type, use the default(deptID = 1 or general care dept)
//if(!isset($deptID))
//{
//    $deptID = getUser
//}

$showInactive = 0;
if(isset($_POST['showInactive']))
{
	if($_POST['showInactive'] == "Show Discharged Patients")
	{
	    $showInactive = 1;
	    $deptID = -1;
		$result = queryMysql("SELECT DISTINCT p.patientID, p.firstName, p.lastName, (SELECT MAX(dischargeDate) FROM patienthistory WHERE patientID = p.patientID) AS dischargeDate ,  p.roomNumber, diag.diagnosis
		                      FROM patient AS p
		                      LEFT JOIN diagnosis AS diag
		                      ON p.patientID = diag.patientID
		                      AND diag.dateAssigned IN (SELECT MAX(dateAssigned)
                                                            FROM diagnosis
                                                            GROUP BY patientID)
                              JOIN patienthistory AS history
                              ON p.patientID = history.patientID
		                      WHERE p.roomNumber IS NULL
		                      ORDER BY p.patientID");
	}
	
	else
	{
	    if($deptID != -1)
	    {
	        $showInactive = 0;
    	    $result = queryMysql("SELECT p.patientID, p.firstName, p.lastName, p.roomNumber, d.departmentID, d.departmentName, diag.diagnosis 
    	                         FROM patient AS p 
	                              JOIN room AS r
	                            ON p.roomNumber = r.roomNumber
	                             JOIN department AS d
	                             ON r.departmentID = d.departmentID
	                             LEFT JOIN diagnosis AS diag
	                              ON p.patientID = diag.patientID
	                              AND diag.dateAssigned IN (SELECT MAX(dateAssigned)
                                                            FROM diagnosis
                                                            GROUP BY patientID
                                                            HAVING isInactive = 0)
    	                          WHERE p.roomNumber IS NOT NULL AND d.departmentID = ". $deptID."
    	                          ORDER BY p.patientID");
	    }
	    else
	    {
	        $showInactive = 0;
	        $result = queryMysql("SELECT p.patientID, p.firstName, p.lastName, p.roomNumber, d.departmentID, d.departmentName, diag.diagnosis 
    	                         FROM patient AS p 
	                              JOIN room AS r
	                            ON p.roomNumber = r.roomNumber
	                             JOIN department AS d
	                             ON r.departmentID = d.departmentID
	                             LEFT JOIN diagnosis AS diag
	                              ON p.patientID = diag.patientID
	                              AND diag.dateAssigned IN (SELECT MAX(dateAssigned)
                                                            FROM diagnosis
                                                            GROUP BY patientID
                                                            HAVING isInactive = 0)
    	                          WHERE p.roomNumber IS NOT NULL
    	                          ORDER BY p.patientID");
	    }
	}
    
}
else
{
    if(!isset($_POST['patientID']))
    {
        if($deptID != -1)
	    {
	        $showInactive = 0;
    	    $result = queryMysql("SELECT p.patientID, p.firstName, p.lastName, p.roomNumber, d.departmentID, d.departmentName, diag.diagnosis 
    	                         FROM patient AS p 
	                              JOIN room AS r
	                            ON p.roomNumber = r.roomNumber
	                             JOIN department AS d
	                             ON r.departmentID = d.departmentID
	                             LEFT JOIN diagnosis AS diag
	                              ON p.patientID = diag.patientID
	                              AND diag.dateAssigned IN (SELECT MAX(dateAssigned)
                                                            FROM diagnosis
                                                            GROUP BY patientID
                                                            HAVING isInactive = 0)
    	                          WHERE p.roomNumber IS NOT NULL AND d.departmentID = ". $deptID."
    	                          ORDER BY p.patientID");
	    }
	    else
	    {
	        $showInactive = 0;
	        $result = queryMysql("SELECT p.patientID, p.firstName, p.lastName, p.roomNumber, d.departmentID, d.departmentName, diag.diagnosis 
    	                         FROM patient AS p 
	                              JOIN room AS r
	                            ON p.roomNumber = r.roomNumber
	                             JOIN department AS d
	                             ON r.departmentID = d.departmentID
	                             LEFT JOIN diagnosis AS diag
	                              ON p.patientID = diag.patientID
	                              AND diag.dateAssigned IN (SELECT MAX(dateAssigned)
                                                            FROM diagnosis
                                                            GROUP BY patientID
                                                            HAVING isInactive = 0)
    	                          WHERE p.roomNumber IS NOT NULL
    	                          ORDER BY p.patientID");
	    }
    }
    else
    {
        $showInactive = 0;
        $result = queryMysql("SELECT p.patientID, p.firstName, p.lastName, p.roomNumber, d.departmentID, d.departmentName, diag.diagnosis
	                      FROM patient AS p 
	                      JOIN room AS r
	                      ON p.roomNumber = r.roomNumber
	                      JOIN department AS d
	                      ON r.departmentID = d.departmentID
	                      LEFT JOIN diagnosis AS diag
	                      ON p.patientID = diag.patientID
	                      AND diag.dateAssigned IN (SELECT MAX(dateAssigned)
                                                            FROM diagnosis
                                                            GROUP BY patientID
                                                            HAVING isInactive = 0)
	                      WHERE p.roomNumber IS NOT NULL AND p.patientID = ". $_POST['patientID']."
	                      ORDER BY p.patientID");
    }
	
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" type="text/css" href="css/style.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Lato|Montserrat:500,700" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Prime Health Care - Main Menu</title>
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
                    <a class="nav-link" href="main.php">Main Menu<span class="sr-only">(current)</span></a>
                </li>
                <!-- Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">Change Dept</a>
                    <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
                        <form action="main.php" method="post" onsubmit="return true" >
                            <button class="dropdown-item" name="deptID" value= -1  >Show All</button>
                        <?php
                            $deptResult = queryMysql("SELECT departmentID, departmentName FROM department");
                            if($deptResult->num_rows > 0)
                            {
                                while($deptRow = mysqli_fetch_assoc($deptResult))
                                {
                                    if($deptID == $deptRow['departmentID'])
                                    {
                                        $deptName = $deptRow['departmentName'];
                                    }
                                    echo '<form action="main.php" method="post" onsubmit="return true" >';
                                    
                                    echo '<button class="dropdown-item" name="deptID" value='. $deptRow['departmentID'] .'  > '. $deptRow['departmentName'] .'</button>';
                                    
                                    echo '</form>';
                                }
                            }
                        ?>
                    </div>
                </li>
                <?php 
                if(isset($deptName))
                { ?>
                <li class="nav-item active">
                    <a class="nav-link">Current Dept: <?php echo $deptName; ?></a>
                </li>
                <?php }
                else
                { ?>
                <li class="nav-item active">
                    <form action="main.php" method="post" onsubmit="return true">
                        <button type="submit" name="deptID" value="<?php echo getUserDept($_SESSION['user']); ?>" class="btn btn-outline-info ">Return to my Dept</button>
                    </form>
                </li>
                <?php } ?>
            </ul>
            <div class="form-inline my-2 ml-lg-2">
                <form method='post' action='main.php' onsubmit='return true'>
                    <button name="showInactive" value="<?php
                            if($showInactive == 0)
                                $btnVal =  "Show Discharged Patients";
                            else if($showInactive == 1)
                                $btnVal = "Show Active Patients";
                            echo $btnVal;
                        ?>" type="submit" class="btn btn-outline-info ">
                        <?php 
                            echo $btnVal;
                        ?>
                    </button>
                </form>
            </div>
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
          <h1>Main Menu</h1>
          <!-- <h1>┴┬┴┤( ͡° ͜ʖ├┬┴┬</h1>-->
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
                        <option value=1>Patient ID</option>
                        <option value=2>First Name OR Last Name</option>
                        <?php 
                        if($showInactive == 0)
                        { ?>
                        <option value=3>Room Number</option>
                        <?php } ?>
                        <?php 
                        if($deptID == -1)
                        { 
                            if($showInactive == 0)
                            { ?>
                        <option value=4>Department</option>
                        <?php 
                            }
                        } ?>
                    </select>
                </div>
            </div>
		</div>
		  
      <?php if ($result->num_rows > 0)
      {
          ?>
          
         <table id="patientViewTable" class="table table-striped">
              <tr>
                <th></th>
                <th>ID</th>
                <th>Name</th>
                <?php 
                if($showInactive == 0)
                { ?>
                <th>Room Number</th>
                <?php }
                else
                { ?>
                <th>Discharge Date</th>
                <?php } ?>
                <?php 
                if($deptID == -1)
                { 
                    if($showInactive == 0)
                    { ?>
                <th>Department</th>
                <?php 
                    }
                } ?>
                <th>Diagnosis</th>
                  <th></th>
              </tr>
              
        <?php
		$tableIndex = 1;
        while ($row = mysqli_fetch_assoc($result)){
                if(!isset($row['departmentID']))
                {
                    $row['departmentID'] = "";
                    $row['departmentName'] = "";
                }
                if(!isset($row['diagnosis']))
                {
                    $row['diagnosis'] = "";
                }
            ?>
            
              <tr>
                <td></td>
                <td><input type='hidden' id="patientID<?php echo $tableIndex ?>" value="<?php echo $row['patientID'] ?>"> <?php echo "$row[patientID]";?> </td>
                <td>
					 <input type='hidden' id="patientFName<?php echo $tableIndex ?>" value="<?php echo "$row[firstName]"; ?>" >
					 <a id="fNameTableVal<?php echo $tableIndex ?>"><?php echo "$row[firstName] ";?></a>
				 	 <input type='hidden' id="patientLName<?php echo $tableIndex ?>" value="<?php echo "$row[lastName]"; ?>" >
					<a id="lNameTableVal<?php echo $tableIndex ?>"><?php echo "$row[lastName]";?></a>
				</td>
				<input type='hidden' id="patientRm<?php echo $tableIndex ?>" value="<?php echo "$row[roomNumber]"; ?>">
				<?php 
                if($showInactive == 0)
                { ?>
                <td>
					<a id="roomTableVal<?php echo $tableIndex ?>"><?php echo "$row[roomNumber]";?></a>
				</td>
				<?php }
				else
				{ ?>
				<td>
				    <input type='hidden' id="dischargeDate<?php echo $tableIndex ?>" value="<?php echo "$row[dischargeDate]"; ?>">
				    <a id="dischargeDateVal<?php echo $tableIndex ?>"></a><?php echo "$row[dischargeDate]"; ?></a>
				</td>
				<?php } ?>
				<input type="hidden" id="patientDeptID<?php echo $tableIndex ?>" value="<?php echo "$row[departmentID]"; ?>">
				    <input type='hidden' id="patientDept<?php echo $tableIndex ?>" value="<?php echo "$row[departmentName]"; ?>">
				<?php 
                if($deptID == -1)
                { 
                    if($showInactive == 0)
                    { ?>
				<td>
				    <a id="deptTableVal<?php echo $tableIndex ?>"><?php echo "$row[departmentName]";?></a>
				</td>
				<?php 
                    }
				} ?>
				<td>
				    <input type="hidden" id="diagnosis<?php echo $tableIndex ?>" value="<?php echo "$row[diagnosis]"; ?>">
				    <a id="diagnosisVal<?php echo $tableIndex ?>"><?php echo "$row[diagnosis]"; ?></a>
				</td>
                <td class="btnCol">
                      
                    <button id= "detailBtn<?php echo $tableIndex ?>" name="detailBtn" onclick="openPopupMenu(<?php echo $tableIndex ?>)" class="btn btn-outline-success">Details</button>
                </td>
              </tr>
            <?php ; $tableIndex++; } ?>
          </table>
             
        </div>
    </div>
            <!--Patient Details Popup-->
            <div id="popup_bg">
                <div class="popup_main_div">
                    <div class="popup_header">Patient Detail
                    </div>
                    <div id="main_popup" class="popup_main">
                        <form>
                            <div class="form-row">
                                <div class="col">
                                    <label for="detailPatientID">ID:</label>
                                    <input type="text" id="detailPatientID" class="form-control" name="detailPatientID" readonly="readonly">
                                </div>
                                <div class="col">
                                    <label for="detailPatientFName">First Name:</label>
                                    <input type="text" id="detailPatientFName" class="form-control" name="detailPatientFName" readonly="readonly"><br>
                                </div>
                                <div class="col">
                                    <label for="detailPatientLName">Last Name:</label>
                                    <input type="text" id="detailPatientLName" class="form-control" name="detailPatientLName" readonly="readonly"><br>
                                </div>
                            </div>
                            <?php if($showInactive == 0){ ?>
                            <div class="form-row">
                                <div class="col">
                                    <label for="detailDeptNameSelected">Department:</label>
                                    <input type="hidden" id="detailDeptNameSelected">
                                    <select disabled id="detailDeptList" name = 'dept'>
                                    <?php
                                        $result = queryMysql("select departmentID,departmentName from department");
                                        if ($result->num_rows > 0)
                                        {
                                            while ($row = mysqli_fetch_assoc($result))
                                            {
                                                echo '<option value="'.$row['departmentID'].'"> '.$row['departmentID']. ' | ' .$row['departmentName'].'</option>';
                                            }
                                        }
                                    ?>
		                            </select><br>
                                </div>
                                <div class="col">
                                    <label for="detailRoomList">Room Number:</label>
                                    <select disabled id="detailRoomList" name="room">
                                    </select>
                                </div>
                            </div>
                            <?php } ?>
                        </form>
                        <br>
                        <div class="row">
                            <div class="col-lg" align="center">
                                <form method="post" action="viewCurrentDiagnosis.php" onsubmit="return true">
                                    <button id="diagnosisBtnDetail" name="patientID" class="btn btn-outline-success">Diagnosis</button>
                                </form>
                            </div>
                            <div class="col-lg" align="center">
                                <form method='post' action="viewTests.php"  onsubmit='return true'>
                                    <button id="testBtnDetail" name="patientID" class="btn btn-outline-success">Tests</button>
                                </form>
                            </div>
                            <div class="col-lg" align="center">
                                <form method='post' action="viewTreatments.php" onsubmit='return true'>
                                    <button id="treatmentBtnDetail" name="patientID" class="btn btn-outline-success">Treatments</button>
                                </form>
                            </div>
                            <div class="col-lg" align="center">
                                <form method='post' action='viewPrescriptions.php' onsubmit='return true'>
                                    <button id="prescriptionBtnDetail" name="patientID" class="btn btn-outline-success">Prescriptions</button>
                                </form>
                            </div>
                            <div class="col-lg" align="center">
                                <form method='post' action='history.php' onsubmit='return true'>
                                    <button id="historyBtnDetail" name="patientID" class="btn btn-outline-success">History</button>
                                </form>
                            </div>

                            <div class="col-lg" align="center">
                            <?php if(isset($_SESSION['doctor'])){ 
                                if($showInactive == 0)
                                {
	                                ?>
	                                    <button id="dischargeBtnDetail" name="patientID" class="btn btn-outline-danger">Discharge Patient</button>
	                                <?php
                                }
                                else
                                {
                                    ?>
                                    <form id = "admitForm" method="post" action ="main.php" onsubmit="return true">
                                        <input type="hidden" name="patientID" id="admitPatientValue"/>
                                        <button id="admitBtnDetail" name="patientID" class="btn btn-outline-danger">Admit Patient</button>
                                    </form>
                                    <?php
                                }
	                       ?>
                           </div>
                           <?php if(isset($_SESSION['doctor'])){ 
                                if($showInactive == 0){ ?>
                                    <div class="col-lg" align="center">
                                        <button id="assignBtnDetail" name="patientID" class="btn btn-outline-info"><a id="assignBtnLabel">Assign Patient to Me</a></button>
                                    </div>
                        <?php   }
                           } ?>
                        </div>
                        <?php if($showInactive == 0){ ?>
                            <div class="row">
                                <div class="col-lg float-left">
                                    <button id = "saveBtn" class="btn btn-success">Save Changes</button>
                                </div>
                                <div class="col-lg float-right">
                                    <button id="assignedDoctorsBtn" name="patientID" class="btn btn-outline-info">Doctors Assigned</button>
                                    <img id= "editBtn" src="images/edit_mode.png" style="cursor: pointer; max-width: 50px; max-height: 50px; margin-left:10px; margin-top: 10px;" onClick="enableEditMode();" >
                                </div>
                            </div>
                            <?php } ?>
                            <?php }?>
                        </div>
                        <div id="assigned_popup" class="popup_main">
                            <table id="assignedDetailTable" class="table table-striped">
                                <tr>
                                    <th></th>
                                    <th>Doctor</th>
                                    <th>Date Assigned</th>
                                    <th>Department</th>
                                    <th></th>
                                </tr>
                            </table>
                        </div>
                    <div id="close_popup_div" >
                        <p title="Close Detail Menu" >
                            X
                        </p>
                    </div>
                </div>
        </div>
    
        <!-- Scripting to display and hide patient detail popup menu -->
    <script type="text/javascript">
        var popup = document.getElementById("popup_bg");
        var popup_main = document.getElementById("main_popup");
        var popup_assigned = document.getElementById("assigned_popup");
        var historyPopup = document.getElementById("popup_history_bg");
        var IDMenuItem = document.getElementById("detailPatientID");
		var fNameMenuItem = document.getElementById("detailPatientFName");
		var lNameMenuItem = document.getElementById("detailPatientLName");
		var deptMenuItem = document.getElementById("detailDeptList");
		var roomMenuItem = document.getElementById("detailRoomList");
		var isEditing = false;
		var patientID;
		var patientFName;
		var patientLName;
		var patientDept;
		var deptName;
		var patientRm;
		var recordIndex;
		var saveBtn = document.getElementById("saveBtn");
		
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
							if(textVal.toUpperCase().match(filter))
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
		function getPatientID()
		{
			return patientID.value;
		}
		/*Patient Details*/
	    function openPopupMenu(index)
        {
			recordIndex = index;
			
            popup.style.display="block";
            popup_main.style.display="block";
            popup_assigned.style.display="none";
			<?php if(isset($_SESSION['doctor']) && $showInactive == 0){ ?>
			saveBtn.style.visibility= 'hidden';
			<?php }?>
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
			
			patientID = document.getElementById(IDIndex);
			patientFName = document.getElementById(fNameIndex);
			patientLName = document.getElementById(lNameIndex);
			
			IDMenuItem.value = patientID.value;
			fNameMenuItem.value = patientFName.value;
			lNameMenuItem.value = patientLName.value;
			
			<?php if($showInactive == 0) { ?>
			var deptIndex = "patientDeptID" + index + "";
			var roomIndex = "patientRm" + index + "";
			
			patientDept = document.getElementById(deptIndex);
			patientRm = document.getElementById(roomIndex);
						
			deptMenuItem.value = patientDept.value;
			roomMenuItem.value = patientRm.value;
			<?php } ?>
			
			//Detail menu buttons to access tests, treatments, prescriptions and history
			document.getElementById("diagnosisBtnDetail").value = patientID.value;
			document.getElementById("testBtnDetail").value = patientID.value;
			document.getElementById("treatmentBtnDetail").value = patientID.value;
			document.getElementById("prescriptionBtnDetail").value = patientID.value;
			document.getElementById("historyBtnDetail").value = patientID.value;
			<?php if(isset($_SESSION['doctor']) && $showInactive == 0){ ?>
			document.getElementById("dischargeBtnDetail").value = patientID.value;
			<?php } ?>
			<?php if(isset($_SESSION['doctor']) && $showInactive == 1){ ?>
			document.getElementById("admitBtnDetail").value = patientID.value;
			document.getElementById("admitPatientValue").value = patientID.value;
			<?php } ?>
		}
        function closePopupMenu()
        {
            <?php if(isset($_SESSION['doctor']) && $showInactive == 0){ ?>
            disableEditMode();
            <?php }?>
            if(popup_assigned.style.display == "none")
            {
                popup.style.display = "none";
            }
            else
            {
                popup_assigned.style.display = "none";
                popup_main.style.display = "block";
            }
			
			// un-lock scroll position
			var html = jQuery('html');
			var scrollPosition = html.data('scroll-position');
			html.css('overflow', html.data('previous-overflow'));
			window.scrollTo(scrollPosition[0], scrollPosition[1])
        }
        <?php if(isset($_SESSION['doctor']) && $showInactive == 0){ ?>
        function enableEditMode()
        {
            if(!isEditing)
            {
                editBtn.src = "images/enable_edit_mode.png";
                saveBtn.style.visibility = 'visible';
                fNameMenuItem.readOnly = false;
                lNameMenuItem.readOnly = false;
                deptMenuItem.disabled = false;
                roomMenuItem.disabled = false;
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
                deptMenuItem.disabled = true;
                roomMenuItem.disabled = true;
                isEditing = false;
            }
        }
        
        function formatString(string) 
        {
            string = string.toLowerCase();
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
        
        function validate(fNameInput,lNameInput,roomInput)
        {
        
            if(fNameInput == "")
            {
                alert("First Name cannot be blank.");
                return false;
            }
        
            if(lNameInput == "")
            {
                alert("Last Name cannot be blank.");
                return false;
            }
        
            var regex = new RegExp(/^[A-Za-z0-9 ]+$/);
        
            if(regex.test(fNameInput) == false)
            {
                alert("First Name cannot contain special characters.");
                return false;
            }
        
             else if(regex.test(lNameInput) == false)
             {
                alert("Last Name cannot contain special characters.");
                return false;
             }
        
         if(roomInput == 0)
         {
                alert("Please select a room for the patient.");
                return false;
         }
        
        return true;
    }
        <?php }?>
        // JQuery to handle updating patient record
		$(document).ready(function(){
			$("#saveBtn").click(function(){
				var pk = patientID.value;
				var newFName = formatString(fNameMenuItem.value);
				var newLName = formatString(lNameMenuItem.value);
				var newDept = deptMenuItem.value;
				var newRoom = roomMenuItem.value;
				var updatedRecordFName = document.getElementById("fNameTableVal" + recordIndex);
				var updatedRecordLName = document.getElementById("lNameTableVal" + recordIndex);
				var updatedRecordDept = document.getElementById("deptTableVal" + recordIndex);
				var updatedRecordRoom = document.getElementById("roomTableVal" + recordIndex);
				
				if(validate(newFName,newLName,newRoom) == true)
				{
				    //ajax call to run update function
			    	$.ajax({
				    	url: "saveChanges.php",
				    	method: "post",
				    	data: { primaryKey: pk, fname: newFName, lname: newLName, room: newRoom},
			    		success: function(response){
			    			console.log(response);
			    			//if patient was not assigned in a room, admit the patient
			    			if($(patientRm).val() == '')
				    		{
				    		    $.ajax({
				    		        url: "admitPatient.php",
				    		        method: "post",
				    		        data: {patientID: pk},
				    		        success: function(response){
				    		            console.log(response);
				    		            window.location.href='main.php';
					    	        }
						         });
						    }
						    //After update, update ui elements
					    	$(updatedRecordFName).text(newFName);
					    	$(updatedRecordLName).text(newLName);
				    		$(updatedRecordDept).text(deptName);
			    			$(updatedRecordRoom).text(newRoom);
						
		    				$(patientFName).val(newFName);
		    				$(patientLName).val(newLName);
			    			$(patientDept).val(newDept);
		    				$(patientRm).val(newRoom);
		    				
		    			    saveDetails();
				    	}
				    });
				}
			});
		});
		
		<?php if($showInactive == 0) { ?>
		//JQuery to handle setting up room dropdown
		$(document).ready(function(){
		$("button[name=detailBtn]").click(function(){
		        var deptID = deptMenuItem.value;
		        var patientRoom = patientRm.value;
		        $.ajax({
		            url: "getRooms.php",
		            type: "post",
		            data: {dept: deptID, showFull: "y"},
		            dataType: "json",
		            success:function(response){
		                var len= response.length;
		                
		                $("#detailRoomList").empty();
		                for(var i = 0;i<len; i++)
		                {
		                    var roomNum = response[i]['roomNumber'];
		                    var text = response[i]['description'];
		                    
		                    $("#detailRoomList").append("<option value="+roomNum+">"+roomNum+" | "+text+"</option>");
		                    
		                }
		                $(roomMenuItem).val(patientRoom);
		                
		                
		            }
		        });
		        <?php if(isset($_SESSION['doctor'])) { ?>
		        $.ajax({
		                    url: "getDoctorsAssignedToPatient.php",
		                    type: "post",
		                    data: {patientID: patientID.value},
		                    dataType: "json",
		                    success:function(response){
	    	                    var len= response.length;
		                        var doctorIsAssigned = 0;
	    	                    if(len > 0)
	    	                    {
	    	                        for(var i = 0; i<len; i++)
	    	                        {
	    	                            var id = response[i]['userID'];
	    	                            if(id == <?php echo $_SESSION['id'] ?>)
	    	                            {
	    	                                doctorIsAssigned = 1;
	    	                                break;
	    	                            }
	    	                        }
	    	                        if(doctorIsAssigned == 1)
	    	                        {
	    	                            $("#assignBtnLabel").text("Unassign Patient from Me");
	    	                        }
	    	                        else
	    	                        {
	    	                            $("#assignBtnLabel").text("Assign Patient to Me");
	    	                        }
	    	                    }
	    	                    else
	    	                    {
	    	                        $("#assignBtnLabel").text("Assign Patient to Me");
	    	                    }
		                    }
		        });
		        <?php } ?>
		    });
		});
		
		<?php if(isset($_SESSION['doctor'])){ ?>
		$(document).ready(function(){
		    $("#assignedDoctorsBtn").click(function(){
		        if(isEditing)
		        {
		            var dialog = confirm("Are you sure you want to close? Any unsaved changes will be lost.");
		            
		            if(dialog == true)
		            {
		                $("#assignedDetailTable").find("tr:gt(0)").remove();
		                disableEditMode();
		                $.ajax({
		                    url: "getDoctorsAssignedToPatient.php",
		                    type: "post",
		                    data: {patientID: patientID.value},
		                    dataType: "json",
		                    success:function(response){
	    	                    var len= response.length;
		                
	    	                    if(len > 0)
	    	                    {
	    	                        for(var i = 0; i<len; i++)
	    	                        {
		                                var name = response[i]['drName'];
		                                var date = response[i]['date'];
		                                var dept = response[i]['dept'];
		                                
		                                $("#assignedDetailTable tr:last").after("<tr><td></td><td>"+name+"</td><td>"+date+"</td><td>"+dept+"</td></tr>");
	    	                        }
		                            popup_main.style.display="none";
		                            popup_assigned.style.display="block";
		                        }
		                        else
		                        {
		                            alert("Patient is not assigned to any doctors.");
		                        }
		                    }
		                });
		            }
	    	    }
		        else
		        {
		            $("#assignedDetailTable").find("tr:gt(0)").remove();
		            disableEditMode();
		            $.ajax({
		                    url: "getDoctorsAssignedToPatient.php",
		                    type: "post",
		                    data: {patientID: patientID.value},
		                    dataType: "json",
		                    success:function(response){
	    	                    var len= response.length;
		                
	    	                    if(len > 0)
	    	                    {
	    	                        for(var i = 0; i<len; i++)
	    	                        {
		                                var name = response[i]['drName'];
		                                var date = response[i]['date'];
		                                var dept = response[i]['dept'];
		                                
		                                $("#assignedDetailTable tr:last").after("<tr><td></td><td>"+name+"</td><td>"+date+"</td><td>"+dept+"</td></tr>");
	    	                        }
		                            popup_main.style.display="none";
		                            popup_assigned.style.display="block";
		                        }
		                        else
		                        {
		                            alert("Patient is not assigned to any doctors.");
		                        }
		                    }
		                });
		        }
		        
		    });
		});
		<?php } ?>
		        
		// JQuery to handle user changing dept selection and update rooms
		$(document).ready(function(){ 
		    $("#detailDeptList").change(function(){
		        var deptID = deptMenuItem.value;
		        
		        $.ajax({
		            url: "getRooms.php",
		            type: "post",
		            data: {dept: deptID, showFull: "n"},
		            dataType: "json",
		            success:function(response){
		                var len= response.length;
		                
		                $("#detailRoomList").empty();
		                for(var i = 0;i<len; i++)
		                {
		                    var roomNum = response[i]['roomNumber'];
		                    var text = response[i]['description'];
		                    deptName = response[i]['deptName'];
		                    
		                    $("#detailRoomList").append("<option value='"+roomNum+"'>"+roomNum+" | "+text+"</option>");
		                }
		            }
		        });
		    });
		}); 
		
		//JQuery to discharge patient when doctor clicks dischargeBtn
		$(document).ready(function(){
		    $("#dischargeBtnDetail").click(function(){
		        var dialog = confirm("Are you sure you want to discharge patient?");
		        var patient = $(this).val();
		        
		        if(dialog == true)
		        {
		            $.ajax({
		                url: "dischargePatient.php",
		                type: "post",
		                data: {patientID: patient},
		                success:function(response){
		                    window.location.href='main.php';
		                }
		            });
		        }
		    });
		});
		
		$(document).ready(function(){
		    $("#assignBtnDetail").click(function(){
		        if($("#assignBtnLabel").text() == "Assign Patient to Me")
		        {
	    	        $.ajax({
    		            url: "assignDoctorToPatient.php",
    		            type: "post",
    		            data: {patient: patientID.value, user: <?php echo $_SESSION['id']; ?> },
	    	            success:function(response){
	    	                console.log(response);
	    	                $("#assignBtnLabel").text("Unassign Patient from Me");
	    	                alert("You have been assigned to this patient.");
	    	            }
	    	        });
		        }
		        else
		        {
		            $.ajax({
    		            url: "unassignDoctorToPatient.php",
    		            type: "post",
    		            data: {patient: patientID.value, user: <?php echo $_SESSION['id']; ?> },
	    	            success:function(response){
	    	                console.log(response);
	    	                $("#assignBtnLabel").text("Assign Patient to Me");
	    	                alert("You have been unassigned from this patient.");
	    	            }
	    	        });
		        }
		    })
		})
		<?php }
		else { ?>
		//JQuery to Admit patient when doctor clicks admitBtn
		$(document).ready(function(){
		    var submitReady = 0;
		    $("#admitForm").submit(function(e){
		        if(submitReady)
		        {
		            return;
		        }
		        else
		        {
		            e.preventDefault();
		        }
		    });
		    
		    $("#admitBtnDetail").click(function(){
		        var dialog = confirm("Are you sure you want to admit patient?");
		        var patient = $(this).val();
		    
		        if(dialog == true)
		        {
		            $.ajax({
		                url: "admitPatient.php",
		                type: "post",
		                data: {patientID: patient, setRoom: true},
		                success:function(response){
		                    submitReady = 1;
		                    $("#admitForm").submit();
		                }
		            })
		        }
		    });
		});
		<?php } ?>
		
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
	    	    }
		        else
		        {
		            closePopupMenu();
		        }
		    });
		});
		        
		<?php if(isset($_SESSION['doctor'])){ ?>
        function saveDetails()
        {
            if(isEditing)
            {
                disableEditMode();
				
                closePopupMenu();
            }
        }
        <?php }?>
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
        <div class="container-fluid"> <i class="fas fa-user"></i> Logged in as: <?php echo "$_SESSION[user]";?>
        </div>
    </footer>
</html>