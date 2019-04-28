<?php
	require_once 'functions.php';

	$patient = $_POST['patient'];
	$userID = $_POST['userID'];
	$newDiagnosis = $_POST['diagnosis'];
	$newNotes = $_POST['notes'];
	//$newEnd = $_POST['end'];

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
    $stmt = $connection->prepare("UPDATE diagnosis SET diagnosis = ?, doctorNotes = ? 
                                  WHERE patientID = ? AND userID = ?;");
    $stmt->bind_param('ssii', $newDiagnosis, $newNotes, $patient, $userID);
    $stmt->execute();
         if (!$stmt) {
               echo "There was a error with your data <a href='main.php'>click here</a> to return to the main menu.<br>";
         die($connection->error);
         }
?>