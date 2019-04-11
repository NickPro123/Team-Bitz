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
  
  function getDrugs()
{
	global $connection;
	
	$stmt = "SELECT drugID, medicineName, CONCAT(baseDose, ' mg') as baseDose, CONCAT('Warning: ', warning) as warning, description FROM drug";
	
	if($result = mysqli_query($connection,$stmt))

	  {

		  return $result;

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

function getPatientName($var)
{
	$result = queryMysql("select firstName, lastName from patient where patientID = '".$var."'");
	$row = mysqli_fetch_assoc($result);
	return $row;
}

function getHistory($var)
{
    global $connection;
$history = array();
    //$totalHistory = array();
    
    $stmt = "Select p.assignDate, CONCAT('Dr. ', u.lastName) as lastn, '', '' from doctorassignedtopatient p Join user u on u.userID = p.userID Where p.patientID = '".$var."'";
    if($result = mysqli_query($connection,$stmt))
	  {
    while ($row_user = $result->fetch_row())
    {
    $history[] = $row_user;
    }
	  }

    $stmt = "Select p.assignDate,  t.testName, '', '' from patientassignedtotest p
    Join test t on t.testID = p.testID
    Where p.patientID = '".$var."'";
    if($result = mysqli_query($connection,$stmt))
	  {
    while ($row_user = $result->fetch_row())
    {
    $history[] = $row_user;
    }       
	  }
    $stmt = "Select p.assignDateStart, t.testName, p.testResult, 'Start Test' from patientassignedtotest p
    Join test t on t.testID = p.testID
    Where p.patientID = '".$var."'";
    if($result = mysqli_query($connection,$stmt))
	  {
    while ($row_user = $result->fetch_row())
    {
    $history[] = $row_user;
    }
	  }
    $stmt = "Select p.assignDate,  t.treatmentName, p.recommendedAmount , '' from patientassignedtotreatment p
    Join treatment t on t.treatmentID = p.treatmentID
    Where p.patientID = '".$var."'";
    if($result = mysqli_query($connection,$stmt))
	  {
    while ($row_user = $result->fetch_row())
    {
    $history[] = $row_user;
    }
	  }
    $stmt = "Select p.assignDateStart, t.treatmentName, '','Start Treatment' from patientassignedtotreatment p
    Join treatment t on t.treatmentID = p.treatmentID
    Where p.patientID = '".$var."'";
    if($result = mysqli_query($connection,$stmt))
	  {
    while ($row_user = $result->fetch_row())
    {
    $history[] = $row_user;
    }       
	  }
    $stmt = "Select admittedDate, '', '', 'Patient Admitted' from patienthistory where patientID = '".$var."'";
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
    $stmt = "Select p.assignDateStart,  d.medicineName, concat('Dose: ', r.dose, 'mg, ', r.timesPerDay,' times per day'), 'Prescription Starts' from prescriptionassignedtopatient p
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
    $stmt = "Select p.assignDateEnd, d.medicineName ,'', 'Prescription Ends' from prescriptionassignedtopatient p
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