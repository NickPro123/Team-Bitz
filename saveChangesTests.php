<?php
	require_once 'functions.php';

	$patient = $_POST['patient'];
	$pk = $_POST['primaryKey'];
	$start = $_POST['start'];
	$result = $_POST['result'];
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
    $stmt = $connection->prepare('UPDATE patientassignedtotest SET assignDateStart = ?, testResult = ? WHERE patientID = ? AND testID = ?;');
    $stmt->bind_param('ssii', $start, $result, $patient, $pk);
    $stmt->execute();
         if (!$stmt) {
               echo "There was a error with your data <a href='main.php'>click here</a> to return to the main menu.<br>";
         die($connection->error);
         }
         
?>