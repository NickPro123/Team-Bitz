<?php
session_start();
require_once 'functions.php';
if(!isset($_SESSION['user'])){
    header("Location:login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
 <head>
        <link rel="stylesheet" type="text/css" href="css/style.css">

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link href="https://fonts.googleapis.com/css?family=Lato|Raleway:400,700,900" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Prime Health Care - Welcome</title>
    </head>
    <!--Navbar-->

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.html">Prime Care</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <?php if(!isset($_SESSION['doctor']))
                    {
                        echo '<a class="nav-link" href="nurseMainMenu.php">Nurse Main Menu<span class="sr-only">(current)</span></a>';
                    }
                    else
                    {   
                        echo '<a class="nav-link" href="main.php">Main Menu<span class="sr-only">(current)</span></a>';
                    }
                    ?>
                </li>
            </ul>

           <div class="form-inline my-2 ml-lg-2">
                <form method='post' action='logout.php' onsubmit='return true'>
                    <button type="submit" class="btn btn-outline-success ">Log Out</button>
                </form>
            </div>
        </div>
    </nav>
    <header>
        <img src="images/logo.png" class="logo">
    </header>
<body>

    <div class="container">
        <div class="center">
          <h1>Patient History for <?php $nameOfPatient = getPatientName($_POST['patientID']); 
			echo "$nameOfPatient[firstName] $nameOfPatient[lastName]";?></h1>
     
         <table class="table table-striped">
              <tr>
            <th>Date</th>
            <th>Assigned To</th>
            <th>Info</th>
            <th></th>
                    
              </tr>
              <?php
		$tableIndex = 1;
		$row = getHistory($_POST['patientID']);
         $sort_column = array();
            foreach ($row as $a){
         if(!($a == ''))
            {
             $sort_column []= $a[0]; // 1 = your example

            }
            }
        array_multisort($sort_column, $row);
        foreach ($row as $record){
           if(!($record[0] == '')){
            //fix the columns after this
            ?>
              <tr>
                <td><input type='hidden' id="date<?php echo $tableIndex ?>" value="<?php echo $record[0] ?>"> 
                <?php echo $record[0];?> </td>
                <td>
                <input type='hidden' id="assigned<?php echo $tableIndex ?>" value="<?php echo "$record[1]"; ?>" >
		<a id="assignedTableVal<?php echo $tableIndex ?>"><?php echo "$record[1] ";?></a>
            </td>
            
            <td>
		<input type='hidden' id="info<?php echo $tableIndex ?>" value="<?php echo "$record[2]"; ?>" >
		<a id="infoTableVal<?php echo $tableIndex ?>"><?php echo "$record[2]";?></a>

		</td>
                <td>
		<input type='hidden' id="moreInfo<?php echo $tableIndex ?>" value="<?php echo "$record[3]"; ?>">
		<a id="mInfoTableVal<?php echo $tableIndex ?>"><?php echo "$record[3]";?></a>
		</td>
                </tr>

        <?php }} ?>
        
        </table>

        <!-- Optional JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </div>
    </div>
</body>
    <footer class="footer">
        <div class="container-fluid"> Logged in as: <?php echo "$_SESSION[user]";?>
        </div>
    </footer>

</html>