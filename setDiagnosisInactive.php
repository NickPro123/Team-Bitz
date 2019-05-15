<?php
	require_once 'functions.php';

	$patientPK = $_POST['patientPK'];
	$userPK = $_POST['userPK'];

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
    $stmt = $connection->prepare('UPDATE diagnosis SET isInactive = 1 WHERE patientID = ? AND userID = ?');
    $stmt->bind_param('ii', $patientPK, $userPK);
    $stmt->execute();
         if (!$stmt) {
               echo "There was a error with your data <a href='main.php'>click here</a> to return to the main menu.<br>";
         die($connection->error);
         }
         
?>