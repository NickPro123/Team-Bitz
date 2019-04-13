<?php
	require_once 'functions.php';

	$pk = $_POST['primaryKey'];
	$firstname = $_POST['fname'];
	$lastname = $_POST['lname'];
	$roomNum = $_POST['room'];

	/*
	$stmt = "UPDATE patient SET firstName = \"" . $firstname . "\" , lastName = \"" . $lastname . "\" , roomNumber = " . $roomNum . " WHERE patientID =  " . $pk  . ";";
	if($result = mysqli_query($connection,$stmt))
	{
		return;
	}
	  
	else
	 {
		 echo "Unexpected error has occured. Error code 3.<br>";
	 }
	 */
    $stmt = $connection->prepare('call spUpdatePatient(?, ?, ?, ?)');
    $stmt->bind_param('issi', $pk, $firstname, $lastname, $roomNum);
    $stmt->execute();
         if (!$stmt) {
               echo "There was a error with your data <a href='main.php'>click here</a> to return to the main menu.<br>";
         die($connection->error);
         }
         
?>