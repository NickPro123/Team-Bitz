<?php
	require_once 'functions.php';

	$patientID = $_POST['patient'];
	$userID = $_POST['user'];

    $stmt = $connection->prepare('DELETE FROM doctorassignedtopatient WHERE patientID = ? AND userID = ?');
    $stmt->bind_param('ii', $patientID, $userID);
    $stmt->execute();
         if (!$stmt) {
               echo "There was a error with your data <a href='main.php'>click here</a> to return to the main menu.<br>";
         die($connection->error);
         }
         
?>