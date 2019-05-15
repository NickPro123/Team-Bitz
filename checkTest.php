<?php
	require_once 'functions.php';

	$patientID = $_POST['patientID'];
	$userID = $_POST['userID'];
	$testID = $_POST['testID'];
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
    $stmt = "SELECT patientID, userID, testID FROM patientassignedtotest WHERE patientID = ". $patientID ." AND userID = ". $userID ." AND testID = ". $testID;
    $result = mysqli_query($connection,$stmt);
    
    $test_arr = array();
    
    while($row = mysqli_fetch_array($result))
    {
        $doesExist = $row['userID'];
        
        $test_arr[] = array("testExists" => $doesExist);
    }
    echo json_encode($test_arr);
    
?>