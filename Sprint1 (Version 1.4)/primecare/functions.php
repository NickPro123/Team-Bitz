<?php 
  $hn  = 'localhost';    
  $un  = 'id8869832_admin'; 
  $pw  = '&JOEwIF25m8lZmngP13w';   
  $db = "id8869832_primecare"; 
  $connection = new mysqli($hn, $un, $pw, $db);

  if ($connection->connect_error) die($connection->connect_error);
  function add_user($connection, $firstName, $lastName, $type, $deptID, $password, $salt1, $salt2, $username )
  {
        //echo "add_user called.";
        $stmt = $connection->prepare('insert into user (firstName, lastName, type, departmentID, password, salt1, salt2, userName) values(?,?,?,?,?,?,?,?)');
        $stmt->bind_param('sssissss', $firstName, $lastName, $type, $deptID, $password, $salt1, $salt2, $username);
        $stmt->execute();
      //  $query = "insert into users values('$userName', '$password', '$email', '$salt1', '$salt2')";
       // $result = $connection->query($query);
         if (!$stmt) {
               echo "There was a error with your data <a href='Signup.html'>click here</a> to return to the previous screen.<br>";
         die($connection->error);
        }
  }
  function getUserType($username)
  {
	  global $connection;
	  $stmt = "SELECT type FROM user WHERE userName = '".$username."'";
 
	  if($result = mysqli_query($connection,$stmt))
	  {
		  while($row = $result->fetch_row())
		  {
			  return $row[0];
		  }
	  }
	  
	  else
	  {
		  echo "Unexpected error has occured.<br>";
	  }
  }

  function getUserDept($username)
  {
	  global $connection;
	  $stmt = "SELECT departmentID FROM user WHERE userName = '".$username."'";
	  
	  if($result = mysqli_query($connection,$stmt))
	  {
		  while($row = $result->fetch_row())
		  {
			  return $row[0];
		  }
	  }
	  
	  else
	  {
		  echo "Unexpected error has occured.<br>";
	  }
  }
  function username_exist_in_database($username)
  {		
        global $connection;
        $stmt = "SELECT userName FROM user where userName = '".$username."'";

        if($result = mysqli_query($connection,$stmt))
        {   
                while($row = $result->fetch_row())
                {
                     if($row[0] == $username)
                     {
						 return true;
                     }
				}
        }
        return false;
        /*
        if($result->num_rows >= 1)
        {
            echo "Email or Username already exist, try something else.";
            return 1;
        } 
        else 
        {
            echo "Username is unqiue!";
            return 0;
        }
*/
    }
  function generateSalt(){
      $str = "";
      $charset = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));

      for ($i = 0; $i < 10; ++$i){
          $rand = mt_rand(0, 50);
          $str .= $charset[$rand];
          }
      return $str;
      }
  function queryMysql($query)
  {
    global $connection;
    $result = $connection->query($query);
    if (!$result) die($connection->error);
    return $result;
  }
  function destroySession()
  {
    $_SESSION=array();
    if (session_id() != "" || isset($_COOKIE[session_name()]))
      setcookie(session_name(), '', time()-2592000, '/');
    session_destroy();
  }

  function sanitizeString($var)
  {
      //check 10-20
    global $connection;
    $var = strip_tags($var);
    $var = stripslashes($var);
    $var = htmlentities($var);
    return $connection->real_escape_string($var);
  }



function getHistory($var)
{
    global $connection;
$history = array();
    //$totalHistory = array();
    
    $stmt = "Select p.assignDate, u.lastName, '', 'Doctor Assigned' from doctorassignedtopatient p Join user u on u.userID = p.userID Where p.patientID = '".$var."'";
    if($result = mysqli_query($connection,$stmt))
	  {
    while ($row_user = $result->fetch_row())
    {
    $history[] = $row_user;
    }
	  }

    $stmt = "Select p.assignDate,  t.testName, '', 'Test Assigned' from patientassignedtotest p
    Join test t on t.testID = p.testID
    Where p.patientID = '".$var."'";
    if($result = mysqli_query($connection,$stmt))
	  {
    while ($row_user = $result->fetch_row())
    {
    $history[] = $row_user;
    }       
	  }
    $stmt = "Select p.assignDateStart, t.testName, p.testResult, 'Test Results' from patientassignedtotest p
    Join test t on t.testID = p.testID
    Where p.patientID = '".$var."'";
    if($result = mysqli_query($connection,$stmt))
	  {
    while ($row_user = $result->fetch_row())
    {
    $history[] = $row_user;
    }
	  }
    $stmt = "Select p.assignDate,  t.treatmentName, p.recommendedAmount , 'Treatment Assigned' from patientassignedtotreatment p
    Join treatment t on t.treatmentID = p.treatmentID
    Where p.patientID = '".$var."'";
    if($result = mysqli_query($connection,$stmt))
	  {
    while ($row_user = $result->fetch_row())
    {
    $history[] = $row_user;
    }
	  }
    $stmt = "Select p.assignDateStart, t.treatmentName, '', 'Patient assigned treatment' from patientassignedtotreatment p
    Join treatment t on t.treatmentID = p.treatmentID
    Where p.patientID = '".$var."'";
    if($result = mysqli_query($connection,$stmt))
	  {
    while ($row_user = $result->fetch_row())
    {
    $history[] = $row_user;
    }       
	  }
    $stmt = "Select admittedDate , '', '', 'Patient Admitted' from patienthistory where patientID = '".$var."'";
    if($result = mysqli_query($connection,$stmt))
	  {
    while ($row_user = $result->fetch_row())
    {
    $history[] = $row_user;
    }              
	  }
    $stmt = "Select dischargeDate, '', '', 'Patient Discharged' from patienthistory where patientID = '".$var."'";
    if($result = mysqli_query($connection,$stmt))
	  {
    while ($row_user = $result->fetch_row())
    {
    $history[] = $row_user;
    }                     
	  }
    $stmt = "Select p.assignDateStart,  d.medicineName, r.dose, r.timesPerDay from prescriptionassignedtopatient p
    Join prescription r on p.doctorOrderNumber = r.doctorOrderNumber
    Join drug d on r.drugID = d.drugID
    Where p.patientID = '".$var."'";
    if($result = mysqli_query($connection,$stmt))
	  {
    while ($row_user = $result->fetch_row())
    {
    $history[] = $row_user;
    }                            
	  }
    $stmt = "Select p.assignDateEnd, d.medicineName ,'', 'Prescription End' from prescriptionassignedtopatient p
    Join prescription r on p.doctorOrderNumber = r.doctorOrderNumber
    Join drug d on r.drugID = d.drugID
    Where p.patientID = '".$var."'";
    if($result = mysqli_query($connection,$stmt))
	  {
    while ($row_user = $result->fetch_row())
    {
    $history[] = $row_user;
    }
	  }                           
    return $history;
}

?>