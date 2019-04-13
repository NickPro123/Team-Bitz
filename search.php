<?php 
// I certify that this submission is my own original work
//Joseph Mineo
//R01581419
session_start();
require_once 'functions.php';

$error = $search = $field = "";

if (!empty($_SESSION['user']))
  {
       if (!empty($_POST['search']))
   {
   $field = ($_POST['game']);
   $value = sanitizeString($_POST['search']);
 
  
       
      //$value = ($_POST['search']);
      
  $result = queryMysql("select * from video_game where $field = '$value'");
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
        
        <form method='post' action='main.php' onsubmit='return true'>
        <tr><td colspan='2' align='center'><input type='submit' value='Main Menu'></td></tr>
    </form>
    </table>";


  }else{
     echo "There is no data matching your criteria. <a href='main.php'>Main Menu</a>";

  }
  
  }else{
  
   echo "<!DOCTYPE html>
   <form method='post' action='search.php'>
<p>
Select a Field <select name='game' size='1'>
<option value='title'>Title</option>
<option value='system'>System</option>
<option value='company'>Company</option>
<option value='genre'>Genre</option>
<option value='year'>Year</option>
</select>
</p>
Enter field value: <input type='text' name='search' value='' /><br />

<p>
<input type='submit' value='Submit'/>
</p>
</form>
 <form method='post' action='main.php' onsubmit='return true'>
        <tr><td colspan='2' align='center'><input type='submit' value='Main Menu'></td></tr>


</form>";
}
}else{
     
  echo "You are not logged in
  <a href='Signup.html'>click here</a> to refresh the screen.";
}
  ?>