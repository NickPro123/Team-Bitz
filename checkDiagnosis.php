<?php
	require_once 'functions.php';

	$patientID = $_POST['patientID'];
	$userID = $_POST['userID'];
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
    $stmt = "SELECT patientID, userID FROM diagnosis WHERE isInactive = 0 AND patientID = ". $patientID ." AND userID = ". $userID;
    $result = mysqli_query($connection,$stmt);
    
    $diag_arr = array();
    
    while($row = mysqli_fetch_array($result))
    {
        $doesExist = $row['userID'];
        
        $diag_arr[] = array("diagnosisExists" => $doesExist);
    }
    echo json_encode($diag_arr);
    
?>