<?php
	require_once 'functions.php';

	$pk = $_POST['patientID'];
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
    $stmt = $connection->prepare('call spReleasePatient(?)');
    $stmt->bind_param('i', $pk);
    $stmt->execute();
         if (!$stmt) {
               echo "There was a error with your data <a href='main.php'>click here</a> to return to the main menu.<br>";
         die($connection->error);
         }
         else
         {
             $stmt = $connection->prepare("DELETE FROM doctorassignedtopatient WHERE patientID = ?");
             $stmt->bind_param('i',$pk);
             $stmt->execute();
             if(!$stmt){
                 echo "There was a error with your data <a href='main.php'>click here</a> to return to the main menu.<br>";
                die($connection->error);
             }
             $stmt = $connection->prepare("UPDATE diagnosis SET isInactive = 1 WHERE patientID = ?");
             $stmt->bind_param("i",$pk);
             $stmt->execute();
             if(!stmt){
                  echo "There was a error with your data <a href='main.php'>click here</a> to return to the main menu.<br>";
                die($connection->error);
             }
             
         }
         
?>