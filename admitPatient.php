<?php
	require_once 'functions.php';

	$pk = $_POST['patientID'];
	
	if(isset($_POST['setRoom']))
	{
	    $roomNum = 170;
	    $stmt = $connection->prepare("call spUpdatePatientRoom(?,?)");
        $stmt->bind_param('ii',$pk,$roomNum);
        $stmt->execute();
        
        if(!stmt)
        {
            do
            {
                $roomNum++;
                $stmt = $connection->prepare("call spUpdatePatientRoom(?,?)");
                $stmt->bind_param("ii",$pk,$roomNum);
                $stmt->execute();
            }while(!stmt);
        }
	}
	
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
    $stmt = $connection->prepare('call spAdmitPatient(?)');
    $stmt->bind_param('i', $pk);
    $stmt->execute();
         if (!$stmt) {
               echo "There was a error with your data <a href='main.php'>click here</a> to return to the main menu.<br>";
         die($connection->error);
         }
         
?>