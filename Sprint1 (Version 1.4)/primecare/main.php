<?php 
// I certify that this submission is my own original work
//Joseph Mineo
//R01581419
session_start();
require_once 'functions.php';


if (!empty($_SESSION['user']))
  {

echo "
    <link rel='stylesheet' type='text/css' href='css/style.css'>
    
  <header>
      <img src='images/logo.png' class='logo'>
  </header>
    <body>
      <th colspan='2' align='center'>Main Menu</th>";
      
      $result = queryMysql("select * from patient");
  if ($result->num_rows > 0)
  {
   echo "<style>
        table {
            border-collapse: collapse;
        }
                   
        </style>
      
      <table border = '1'>
  <tr>
      <th>ID</th>
    <th>Name</th>
    <th>Room Number</th> 
    
  </tr>";
    while ($row = mysqli_fetch_assoc($result)){
  echo "<tr>
    <th>".$row["patientID"]."</th> 
    <th>".$row["firstName"]." ".$row["lastName"]."</th>
    <th>".$row["roomNumber"]."</th></tr>";
            
    }
echo "    
      
    </table>
      </form>
            <form method='post' action='logout.php' onsubmit='return true'>
        <tr><td colspan='2' align='center'><input type='submit' value='Logout'></td></tr>

      
    </table>
  </body>";
  }else{
     echo "There is no data to be displayed please <a href='main.php'>add</a> some.";
  }
  
  }else{
  echo "You are not logged in
  <a href='Signup.html'>click here</a> to refresh the screen.";
  }
?>


