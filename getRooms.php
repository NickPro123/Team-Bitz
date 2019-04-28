<?php
	require_once 'functions.php';
    
    $deptID = $_POST['dept'];
    $showFull = $_POST['showFull'];
    
    if($showFull == "y")
    {
        $stmt = "SELECT r.roomNumber, r.description, d.departmentName
             FROM room AS r
             JOIN department AS d
             ON r.departmentID = d.departmentID
             WHERE r.departmentID = ".$deptID;
    }
    else
    {
        $stmt = "SELECT r.roomNumber, r.description, d.departmentName
             FROM room AS r
             JOIN department AS d
             ON r.departmentID = d.departmentID
             WHERE r.patientsAssigned < r.maxCapacity AND r.departmentID = ".$deptID;
    }
    
    $result = mysqli_query($connection,$stmt);
             
    $rooms_arr = array();
    
    while( $row = mysqli_fetch_array($result) )
    {
        $room = $row['roomNumber'];
        $text = $row['description'];
        $deptName = $row['departmentName'];
        
        $rooms_arr[] = array("roomNumber" => $room, "description" => $text, "deptName" => $deptName);
    }
	echo json_encode($rooms_arr);
	
?>