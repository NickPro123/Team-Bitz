<?php 
// I certify that this submission is my own original work
//Joseph Mineo
//R01581419
  require_once 'functions.php';
session_start();

  if (isset($_SESSION['user']))
  {
    destroySession();
    header('Location: Signup-test.html');
    //echo "<div class='main'>You have been logged out. Please " .
    //     "<a href='Signup-test.html'>click here</a> to refresh the screen.";
  }
  else echo "<div class='main'>" .
            "You cannot log out because you are not logged in".
      "<a href='Signup-test.html'>Click here</a> to return to the sign up page.";

?>

    <br><br></div>
  </body>
</html>
