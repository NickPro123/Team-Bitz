<?php
	require_once 'functions.php';

	$patientID = $_POST['patientID'];
	$userID = $_POST['userID'];
	$drugID = $_POST['drugID'];
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
    $stmt = "SELECT patientID, userID, drugID FROM prescriptionassignedtopatient WHERE assignDateEnd >= CURDATE() AND patientID = ". $patientID ." AND userID = ". $userID ." AND drugID = ". $drugID;
    $result = mysqli_query($connection,$stmt);
    
    $drug_arr = array();
    
    while($row = mysqli_fetch_array($result))
    {
        $doesExist = $row['userID'];
        
        $drug_arr[] = array("prescriptionExists" => $doesExist);
    }
    echo json_encode($drug_arr);
    
?>