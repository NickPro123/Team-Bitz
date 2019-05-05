<?php
	require_once 'functions.php';

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
    $stmt = "SELECT roomNumber FROM room WHERE roomNumber = ". $roomNum;
    $result = mysqli_query($connection,$stmt);
    
    $room_arr = array();
    
    while($row = mysqli_fetch_array($result))
    {
        $doesExist = $row['roomNumber'];
        
        $room_arr[] = array("roomExists" => $doesExist);
    }
    echo json_encode($room_arr);
    
?>