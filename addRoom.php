<?php
session_start();

require_once 'functions.php';
if(!isset($_SESSION['user'])){
    $page = "login.php";
}

if (isset($_POST['dept'])){
      
        $dept = $roomNmb = $roomDescript = $maxCap = "";

 
    $dept = sanitizeString($_POST['dept']); //int
    $roomNmb = sanitizeString($_POST['roomNmb']); // int
    $roomDescript = sanitizeString($_POST['roomDescript']); // string
    $maxCap = sanitizeString($_POST['maxCap']); // int
    $currentCap = 0;
	
    if ($roomNmb == "" || $dept == "" || $roomDescript == "" || $maxCap == "")
        $error = "Not all fields were entered<br>";
    else{
        
        $stmt = $connection->prepare('insert into room (roomNumber, departmentID, description, maxCapacity, patientsAssigned) values (?,?,?,?,?)');				
	   $stmt->bind_param('ssssi',$roomNmb,$dept,$roomDescript,$maxCap,$currentCap);
        $stmt->execute();
         if (!$stmt) {
               echo "There was a error with your data <a href='main.php'>click here</a> to return to the main menu.<br>";
         die($connection->error);
         }
         else
         {
             $page = "viewRoomInformation.php";
         }
    }
   
   header("Location: ". $page);
   exit;
      
     
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Lato|Raleway:400,700,900" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Prime Health Care - Add Room</title>
    </head>
    <!--Navbar-->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="main.php">Prime Care</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="viewRoomInformation.php">Return to View Rooms<span class="sr-only">(current)</span></a>
                </li>
            </ul>
            <div class="form-inline my-2 ml-lg-2">
                <form method='post' action='viewRoomInformation.php' onsubmit='return true'>
                    <button type="submit" class="btn btn-outline-warning ">View Room Information</button>
                </form>
            </div>
            <div class="form-inline my-2 ml-lg-2">
                <form method='post' action='addpatient.php' onsubmit='return true'>
                    <button type="submit" class="btn btn-outline-warning ">Add Patient</button>
                </form>
            </div>
            <div class="form-inline my-2 ml-lg-2">
                <form method='post' action='logout.php' onsubmit='return true'>
                    <button type="submit" class="btn btn-outline-success ">Log Out</button>
                </form>
            </div>
        </div>
    </nav>



  <header>
    <a>
      <img src="images/logo.png"  class="logo">
    </a>
  </header>
  <div class="container">
      <form method="post" id="roomForm" action="addRoom.php" class="addprescription"> <?php $error?>
          <div class="formHeader">Add a new room</div>
          <div class="form">
		    <div class="form-group">
                  <label for="roomNmb">Room Number</label>
                  <input type="text" maxlength="64" id="roomNmb" class="form-control" name="roomNmb" value="<?php $roomNmb?>" required="required">
            </div>
              <div class="form-group">
                  <label for="dept">Department: </label>
                  <select name = 'dept' class="form-control">
                    <?php
                          $result = queryMysql("select * from department");
                          if ($result->num_rows > 0)
                          {
                            while ($row = mysqli_fetch_assoc($result))
                            {
                                echo '<option value=" '.$row['departmentID'].' "> '.$row['departmentID']. ' | ' .$row['departmentName'].'</option>';
                            }
                          }
                    ?>
                </select>
              </div>

              <div class="form-group">
                  <label for="roomDescript">Description</label>
                  <input type="text" maxlength="150" id="roomDescript" class="form-control" name="roomDescript" value="<?php $roomDescript?>" required="required">
              </div>

              <div class="form-group">
                  <label for="maxCap">Maximum Capacity</label>
                  <input type="text" maxlength="10" id="maxCap" class="form-control" name="maxCap" value="<?php $maxCap?>" required="required">
              </div>
              
              <button type="button" id="roomBtn" class="btn btn-outline-success" >Add room</button>
      </form>
  </div>
      <!--}else{
         echo "There is no data to be displayed please <a href='main.php'>add</a> some.";
      }-->

        <!-- Optional JavaScript -->
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script>
            var roomValue;
            var regex = new RegExp(/^[A-Za-z0-9 ]+$/);
            
            function validate()
            {
                
                roomValue = $("#roomNmb").val();
                if(isNaN(roomValue))
                {
                    alert("Room number must be a number.");
                    return false;
                }
                else if(roomValue == Math.floor(roomValue))
                {
                    var digitCount = roomValue.toString().length;
                    if(digitCount != 3)
                    {
                        alert("Room number must be 3 digits.");
                        return false;
                    }
                    else
                    {
                        if(roomValue <= 0)
                        {
                            alert("Room number must be greater than zero.");
                            return false;
                        }
                    }
                }
                else
                {
                    alert("Room number must be a whole number.");
                    return false;
                }
                
                var descriptionValue;
                descriptionValue = $("#roomDescript").val();
                
                if(descriptionValue == "")
                {
                    alert("Description cannot be blank.");
                    return false;
                }
                else if(regex.test(descriptionValue) == false)
                {
                    alert("Description cannot contain special characters.");
                    return false;
                }
                
                var maxCapValue = $("#maxCap").val();
                if(isNaN(maxCapValue))
                {
                    alert("Max capacity must be a number.");
                    return false;
                }
                else if(maxCapValue == Math.floor(maxCapValue))
                {
                    if(maxCapValue <= 0)
                    {
                        alert("Max capacity must be greater than zero.");
                        return false;
                    }
                    else if(maxCapValue > 100)
                    {
                        alert("Max capacity must be less than 100.");
                        return false;
                    }
                }
                else
                {
                    alert("Max capacity must be a whole number.");
                    return false;
                }
                return true;
            }
            $(document).ready(function(){
                var submitReady = 0;
                $("#roomForm").submit(function(e){
                    if(submitReady)
                    {
                        return;
                    }
                    else
                    {
                        e.preventDefault();
                    }
                });
                
                $("#roomBtn").click(function(){
                    
                    if(validate())
                    {
                        $.ajax({
                            url: "checkRoomNum.php",
                            type: "post",
                            data: {room: roomValue},
                            dataType: 'json',
                            success:function(response){
                                var len = response.length;
                                
                                if(len > 0)
                                {
                                    alert("Warning! Room number, "+ roomValue + " already exist.");
                                }
                                else
                                {
                                    submitReady = 1;
                                    $("#roomForm").submit();
                                }
                            }
                        });
                    }
                });
            });
        </script>
    </div>
    <footer class="footer">
        <div class="container-fluid"><i class="fas fa-user"> </i> Logged in as: <?php echo "$_SESSION[user]";?>
        </div>
    </footer>
</html>