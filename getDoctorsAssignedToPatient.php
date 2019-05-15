<?php
	require_once 'functions.php';
    
    $patientID = $_POST['patientID'];
    
    $stmt = "SELECT dap.userID, dap.assignDate, user.lastName, dept.departmentName
             FROM doctorassignedtopatient AS dap
             JOIN user AS user
             ON user.userID = dap.userID
             JOIN department AS dept
             ON dept.departmentID = user.departmentID
             WHERE patientID = ".$patientID;
    
    $result = mysqli_query($connection,$stmt);
    $patients_arr = array();
    
    while( $row = mysqli_fetch_array($result) )
    {
        $userID = $row['userID'];
        $last = $row['lastName'];
        $dept = $row['departmentName'];
        $date = $row['assignDate'];
        $drName = "Dr. " . $last;
        
        $patients_arr[] = array("userID" => $userID, "drName" => $drName, "dept" => $dept, "date" => $date);
    }
	echo json_encode($patients_arr);
	
?>