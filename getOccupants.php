<?php
	require_once 'functions.php';
    
    $room = $_POST['roomNum'];
    
    $stmt = "SELECT *
             FROM patient
             WHERE roomNumber = ".$room;
    
    $result = mysqli_query($connection,$stmt);
    $patients_arr = array();
    
    while( $row = mysqli_fetch_array($result) )
    {
        $patientID = $row['patientID'];
        $first = $row['firstName'];
        $last = $row['lastName'];
        $fullName = $first . " " . $last;
        
        $patients_arr[] = array("patientID" => $patientID, "name" => $fullName);
    }
	echo json_encode($patients_arr);
	
?>