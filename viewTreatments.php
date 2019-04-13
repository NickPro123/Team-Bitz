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

	    

	$result = queryMysql("SELECT t.treatmentID, t.treatmentName, ptt.assignDate, ptt.assignDateStart, 						ptt.recommendedAmount
						  FROM treatment as t
						  JOIN patientassignedtotreatment as ptt
						  ON t.treatmentID = ptt.treatmentID
						  WHERE ptt.patientID = ". $id .";");  

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



          <h1><?php $nameOfPatient = getPatientName($_SESSION['patientID']); 



			echo "$nameOfPatient[firstName] $nameOfPatient[lastName]";?> - Treatments Menu </h1>



                <?php if ($result->num_rows > 0)



      {



          ?>



          <div class="searchBarText" style= "width: 50%; float: left;">



			<input type='text' id="searchBarInput" onKeyUp="patientSearch();" placeholder="Enter your search term">



			</div>



			  



		  <div class= "searchBarText" style = "width: 50%; margin-top: 8px; float: right;">



			  <select id="searchCat">



				



				<option value=1>Treatment ID</option>



				<option value=2>Treatment Name</option>



				<option value=3>Assign Date</option>

				  

				<option value=4>Start Date</option>



		<!--		<option value=3>Department</option> -->



				



			</select>



	      </div>

			

		</div>



		  







          



         <table id="patientViewTable" class="table table-striped">



              <tr>



				  <th></th>

                <th>ID</th>



                <th>Treatment Name</th>



                <th>Assign Date</th>



                <th>Start Date</th>
				  
				<th>Recommended Amount</th>

                  <th></th>

				  

              </tr>



              



        <?php



		$tableIndex = 1;



        while ($row = mysqli_fetch_assoc($result)){



            ?>



            



              <tr>

				<td></td>

                <td><input type='hidden' id="treatmentID<?php echo $tableIndex ?>" value="<?php echo $row['treatmentID'] ?>"> <?php echo "$row[treatmentID]";?> </td>



                <td>



					 <input type='hidden' id="treatmentName<?php echo $tableIndex ?>" value="<?php echo "$row[treatmentName]"; ?>" >



					 <a id="treatmentNameVal<?php echo $tableIndex ?>"><?php echo "$row[treatmentName] ";?></a>

				</td>

				  <td>



				 	 <input type='hidden' id="assignDate<?php echo $tableIndex ?>" value="<?php echo "$row[assignDate]"; ?>" >



					<a id="assignDateVal<?php echo $tableIndex ?>"><?php echo "$row[assignDate]";?> </a>







				</td>



                <td>



					<input type='hidden' id="startDate<?php echo $tableIndex ?>" value="<?php echo "$row[assignDateStart]"; ?>" >



					<a id="startDateVal<?php echo $tableIndex ?>"><?php echo "$row[assignDateStart]";?></a>



				</td>
				
				<td>



					<input type='hidden' id="amount<?php echo $tableIndex ?>" value="<?php echo "$row[recommendedAmount]"; ?>" >



					<a id="amountVal<?php echo $tableIndex ?>"><?php echo "$row[recommendedAmount]";?> times per day</a>



				</td>


                  <td class="btnCol">



                      



                      <button id= "detailBtn" onclick="openPopupMenu(<?php echo $tableIndex ?>)" class="btn btn-outline-success">Details</button>



                </td>



              </tr>







            <?php ; $tableIndex++; } }else echo "<div class='container style=float: left;'>There are currently no treatments assigned to this patient. Assign a treatment below </div>"?>



</table>



          



    



          <form method='post' action='addtreatment.php' onsubmit='return true'>                       



                    <button type="submit" name="patientID" value="<?php echo $id; ?>"class="btn btn-outline-success ">Add Treatment</button>     



                </form>                



             



        </div>







    </div>



            <!--Treatment Details Popup-->



            <div id="popup_bg">



                <div class="popup_main_div">



                    <div class="popup_header">Treatment Detail



                    </div>



                    <div class="popup_main">



                        <form>



                            <div class="form-row">



                                <div class="col">



                                    ID: <br>



                                    <input type="text" id="detailTreatmentID" name="detailTreatmentID" readonly="readonly">



                                </div>



                                <div class="col">



                                    Treatment Name: <br>



                                    <input type="text" id="detailTreatmentName" name="detailTreatmentName" readonly="readonly"><br>



                                </div>



                                <div class="col">



                                    Assign Date:<br>



                                    <input type="date" id="detailAssign" name="detailAssign" readonly="readonly"><br>



                                </div>
								
								<div class = "col">
								
									Start Date:<br>
									<input type="date" id="detailStart" name="detailStart" readonly="readonly"><br>
									
								</div>



                            </div>



                            <div class="form-row">



                                <div class="col">



                                    Recommended Amount:<br>



                                    <input type="text" id="detailRecommend" name="detailRecommend" readonly="readonly"><br>



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



  



        <!-- Scripting to display and hide patient detail popup menu -->



    <script type="text/javascript">



        var popup = document.getElementById("popup_bg");



        var IDMenuItem = document.getElementById("detailTreatmentID");







		var nameMenuItem = document.getElementById("detailTreatmentName");







		var assignMenuItem = document.getElementById("detailAssign");







		var startMenuItem = document.getElementById("detailStart");

		var recommendMenuItem = document.getElementById("detailRecommend");

		var isEditing = false;



		var treatmentID;



		var treatmentName;



		var assign;



		var start;

		var recommend;

		var recordIndex;





		var saveBtn = document.getElementById("saveBtn");


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







			var IDIndex = "treatmentID" + index +"";







			var nameIndex = "treatmentName" + index + "";







			var assignIndex = "assignDate" + index + "";







			var startIndex = "startDate" + index + "";

			var recommendIndex = "amount" + index + "";





			







			treatmentID = document.getElementById(IDIndex);







			treatmentName = document.getElementById(nameIndex);







			assign = document.getElementById(assignIndex);







			start = document.getElementById(startIndex);



			recommend = document.getElementById(recommendIndex);



						







			IDMenuItem.value = treatmentID.value;







			nameMenuItem.value = treatmentName.value;







			assignMenuItem.value = assign.value;







			startMenuItem.value = start.value;



			recommendMenuItem.value = recommend.value;



			







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







                nameMenuItem.readOnly = false;







                assignMenuItem.readOnly = false;







                startMenuItem.readOnly = false;



				recommendMenuItem.readOnly = false;



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







                nameMenuItem.readOnly = true;







                assignMenuItem.readOnly = true;







                startMenuItem.readOnly = true;

				recommendMenuItem.readOnly = true;





                isEditing = false;







            }







        }







		$(document).ready(function(){



			$("#saveBtn").click(function(){



				var pk = drugID.value;



				var newName = nameMenuItem.value;



				var newAssign = assignMenuItem.value;



				var newStart = startMenuItem.value;

				var newRecommend = recommendMenuItem.value;

				var updatedRecordName = document.getElementById("treatmentNameVal" + recordIndex);



				var updatedRecordAssign = document.getElementById("assignDateVal" + recordIndex);



				var updatedRecordStart = document.getElementById("startDateVal" + recordIndex);

				var updatedRecordRecommend = document.getElementById("amountVal" + recordIndex);

			



				$.ajax({



					url: "saveChanges.php",



					method: "post",



					data: { primaryKey: pk, name: newName, assign: newAssign, start: newStart, amount: newRecommend},



					success: function(response){



						console.log(response);



						$(updatedRecordName).text(newName);



						$(updatedRecordAssign).text(newAssign);



						$(updatedRecordStart).text(newStart);

						$(updatedRecordRecommend).text(newRecommend);

						



						$(treatmentName).val(newName);



						$(assign).val(newAssign);



						$(start).val(newStart);

						$(recommend).val(newRecommend);

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







        <div class="container-fluid"> Logged in as: <?php echo "$_SESSION[user]";?>







        </div>







    </footer>















</html>