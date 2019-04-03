<?php
  require_once 'functions.php';
session_start();

  if (isset($_SESSION['user']))
  {
    destroySession();
    header('Location: login.php');
    //echo "<div class='main'>You have been logged out. Please " .
      //  "<a href='login.php'>click here</a> to refresh the screen.";
  }
?>