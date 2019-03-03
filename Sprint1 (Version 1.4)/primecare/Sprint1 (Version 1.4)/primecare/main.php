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
    <table class='signup' border='0' cellpadding='2' cellspacing='5' bgcolor='#eeeeee'>
      <th colspan='2' align='center'>Main Menu</th>
      <tr><td>
      <ul class='menu'> 
          <li><a href='view.php'>View</a></li>               
          <li><a href='search.php'>Search</a></li> 
          <li><a href='add.php'>Add</a></li>               
                    
          <li><a href='delete.php'>Delete</a></li></ul>
      </tr></td>
      </form>
            <form method='post' action='logout.php' onsubmit='return true'>
        <tr><td colspan='2' align='center'><input type='submit' value='Logout'></td></tr>

      
    </table>
  </body>";
  
  }else{
  echo "You are not logged in
  <a href='Signup-test.html'>click here</a> to refresh the screen.";
  }
?>

    
</html>
