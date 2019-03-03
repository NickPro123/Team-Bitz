<?php 
// I certify that this submission is my own original work
//Joseph Mineo
//R01581419
session_start();
require_once 'functions.php';


if (!empty($_SESSION['user']))
  {
  $result = queryMysql("select * from video_game");
  if ($result->num_rows > 0)
  {
      echo "<style>
        table {
            border-collapse: collapse;
        }
                   
        </style>
      
      <table border = '1'>
  <tr>
    <th>Title</th>
    <th>System</th> 
    <th>Company</th>
    <th>Genre</th>
    <th>Year</th>
  </tr>";
    while ($row = mysqli_fetch_assoc($result)){
    echo "<tr>
    <th>".$row["title"]."</th>
    <th>".$row["system"]."</th> 
    <th>".$row["company"]."</th>
    <th>".$row["genre"]."</th>
    <th>".$row["year"]."</th>
  </tr>";
            
    }
echo "</table>
        <br>
        </form>
        <form method='post' action='main.php' onsubmit='return true'>
        <tr><td colspan='2' align='center'><input type='submit' value='Main Menu'></td></tr>

      
    </table>
";


  }else{
     echo "There is no data to be displayed please <a href='main.php'>add</a> some.";

  }
 }else{
  echo "You are not logged in
  <a href='Signup-test.html'>click here</a> to refresh the screen.";
  }
  
  ?>