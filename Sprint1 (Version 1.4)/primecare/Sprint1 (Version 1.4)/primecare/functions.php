<?php 
// I certify that this submission is my own original work
//Joseph Mineo
//R01581419
  $hn  = 'localhost';    
  $un  = 'root'; 
  $pw  = '';   
  $db = "primecare1"; 

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
               echo "There was a error with your data <a href='Signup-test.html'>click here</a> to return to the previous screen.<br>";
         die($connection->error);
        }
  }

  function username_exist_in_database($username)
  {		
        $statement = $connection->prepare('select * from user where userName = ?');
        $statement->bind_param('s', $username);
        $statement->execute();
        
        if($statement)
        {
            $statement->store_result();
            return $statement->num_rows;
        }
        else
        {
            return 0;
        }
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

 

  
?>
