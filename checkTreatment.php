<?php
	require_once 'functions.php';

	$patientID = $_POST['patientID'];
	$treatmentID = $_POST['treatmentID'];
	$diagnosisID = $_POST['diagnosisID'];
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
    $stmt = "SELECT CONCAT('Dr. ',u.lastName) AS lastName FROM patientassignedtotreatment AS ptt JOIN user AS u ON u.userID = ptt.userID WHERE ptt.patientID = ". $patientID ." AND ptt.treatmentID = ". $treatmentID ." AND ptt.diagnosisID = ". $diagnosisID;
    $result = mysqli_query($connection,$stmt);
    
    $treatment_arr = array();
    
    while($row = mysqli_fetch_array($result))
    {
        $doesExist = $row['lastName'];
        
        $treatment_arr[] = array("treatmentExists" => $doesExist);
    }
    echo json_encode($treatment_arr);
    
?>