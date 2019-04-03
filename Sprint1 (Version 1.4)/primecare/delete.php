<?php 
// I certify that this submission is my own original work
//Joseph Mineo
//R01581419
session_start();
require_once 'functions.php';


if (!empty($_SESSION['user']))
  {
  
  if (!empty($_POST['game']))
  {
     // echo $_POST['game'];
      $game = $_POST['game'];
 //put delete statement here
 $title = strtok($game, '/');
// echo $title;
$system = substr($game, strpos($game, "/") + 1);
//echo $system;
  queryMysql("DELETE FROM video_game WHERE title = '$title' AND system = '$system'");
  }
  
    $result = queryMysql("select * from video_game");

  if ($result->num_rows > 0)
  {
      $i = 0;
  echo "<form method='post' action='delete.php' onsubmit='return true'>";

    while ($row = mysqli_fetch_assoc($result)){
        //echo $row["title"].$row["system"].$row["company"].$row["genre"].$row["year"];
      //$game[$i] = $row;
       $title = $row["title"];
       $system = $row["system"];
    echo "<tr> <input type='radio' name='game' value='$title/$system'/>";
       foreach($row as $k=>$v){
       echo $v." ";
        }
        echo "<br/>";
  /*  

    <th>".$row["title"]."</th>
    <th>".$row["system"]."</th> 
    <th>".$row["company"]."</th>
    <th>".$row["genre"]."</th>
    <th>".$row["year"]."</th>
    <br>
  </tr>";*/
       // echo $game[$i]["title"];    
  echo "<br>";
  $i++;
    }
    
    
echo "  
        <tr><td colspan='2' align='center'><input type='submit' value='Delete'></td></tr>
    </form>";



    
echo "  <form method='post' action='main.php' onsubmit='return true'>
        <tr><td colspan='2' align='center'><input type='submit' value='Main Menu'></td></tr>
    </form>";


  }else{
     echo "There is no data to be displayed please <a href='main.php'>add</a> some.";

  }
  
 }else{
  echo "You are not logged in
  <a href='Signup.html'>click here</a> to refresh the screen.";
  }
  
  ?>