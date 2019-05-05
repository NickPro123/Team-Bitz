<?php

session_start();
require_once 'functions.php';
if(!isset($_SESSION['user'])){
    header("Location:login.php");

}?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Lato|Raleway:400,700,900" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Prime Health Care - Add Patient</title>
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
                <a class="nav-link" href="main.php">Main Menu<span class="sr-only">(current)</span></a>
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
<?php
if (isset($_POST['firstName'])){
    //$username = fix_string($_POST['firstName']);

    $first = $last = $rmnumber = "";


    $first = sanitizeString($_POST['firstName']);
    $last = sanitizeString($_POST['lastName']);
    $rmnumber = sanitizeString($_POST['room']);

//echo "$first, $last, $rmnumber";
    //  $un_temp = sanitizeString(_SERVER['PHP_AUTH_USER']);
    // $pw_temp = sanitizeString(_SERVER['PHP_AUTH_PW']);

    if ($first == "" || $last == "" || $rmnumber == "")
        $error = "Not all fields were entered<br>";
    else{
        // echo "$first, $last, $rmnumber";
        $stmt = $connection->prepare('call spAddPatient(?,?,?)');
        // $stmt->bind_param('ssi', $first, $last, $rmnumber);
        //  $stmt = $connection->prepare('insert into patient(firstName, lastName, roomNumber) values(?,?,?)');
        $stmt->bind_param('ssi',$first, $last, $rmnumber);
        $stmt->execute();
        if (!$stmt) {
            echo "There was a error with your data <a href='main.php'>click here</a> to return to the main menu.<br>";
            die($connection->error);
        }
        else
        {
            $lastID_result = mysqli_query($connection,"SELECT patientID FROM patient WHERE patientID = LAST_INSERT_ID()");
            $lastID = mysqli_fetch_assoc($lastID_result);
            echo "<div class='container'><div class='row h3'>Patient Added. Would you like to assign a diagnosis?</div>";
            echo "<div class='row'><div class='col'><form method='post' action='adddiagnosis.php' onsubmit='return true'>
                    <button type='submit' name='patientID' value=". $lastID['patientID'] ." class='btn btn-outline-success' >Yes, add diagnosis</button>
                </form></div>
                <div class='col'>
            <form method='post' action='main.php' onsubmit='return true'>
                    <button type='submit' class='btn btn-outline-success' >No, return to main menu</button>
                </form></div></div></div>";
        }
    }

    


    //header('Location: main.php');
}

?>

<!--Main Content-->
<div class="container">
    <form method="post" id="addform" action="addpatient.php" class="addpatient"> <?php $error?>
        <div class="formHeader">Add a new Patient</div>

        <!--Add New Patient Form-->
        <div class="form">
            <div class="form-group">
                <label for="firstName">First Name</label>
                <input type="text" maxlength="24" id="firstName" class="form-control" name="firstName" value="<?php $first?>" required="required">
            </div>

            <div class="form-group">
                <label for="lastName">Last Name</label>
                <input type="text" maxlength="64" id="lastName" class="form-control" name="lastName" value="<?php $last?>" required="required">
            </div>

            <div class="form-group">
                <label for="deptList">Department</label>
                <select id="deptList" class="form-control">
                    <option value="0">- Select Department -</option>
                    <?php
                    $result = queryMysql("select departmentID,departmentName from department");
                    if ($result->num_rows > 0)
                    {
                        while ($row = mysqli_fetch_assoc($result))
                        {
                            echo '<option value="'.$row['departmentID'].'"> '.$row['departmentID']. ' | ' .$row['departmentName'].'</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="roomList">Room</label>
                <select id="roomList" class="form-control" name="room">
                    <option value="0">- Select Department -</option>
                </select>
            </div>
            <form method='post' action='addpatient.php' onsubmit='return true'>
                <button type="submit" id="patientBtn" class="btn btn-outline-success ">Add Patient</button>
            </form>
    </form>
</div>
<!--}else{
   echo "There is no data to be displayed please <a href='main.php'>add</a> some.";
}-->
<?php
require_once 'functions.php';

?>

<!-- Optional JavaScript -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<!-- JQuery to populate room dropdown -->
<script>

    function validate()
    {
        var fNameInput;
        var lNameInput;
        var roomInput;
        
        fNameInput = $("#firstName").val();
        lNameInput = $("#lastName").val();
        roomInput = $("#roomList").children("option:selected").val();
        
        if(fNameInput == "")
        {
            alert("First Name cannot be blank.");
            return false;
        }
        
        if(lNameInput == "")
        {
            alert("Last Name cannot be blank.");
            return false;
        }
        
        var regex = new RegExp(/^[A-Za-z0-9 ]+$/);
        
        if(regex.test(fNameInput) == false)
        {
            alert("First Name cannot contain special characters.");
            return false;
        }
        
        else if(regex.test(lNameInput) == false)
        {
            alert("Last Name cannot contain special characters.");
            return false;
        }
        
        if(roomInput == 0)
        {
            alert("Please select a room for the patient.");
            return false;
        }
        
        return true;
    }
    
    $(document).ready(function(){
        var submitReady = 0;
        $("#addform").submit(function(e){
            if(submitReady == 1)
            {
                return;
            }
            else
            {
                e.preventDefault();
            }
        });
        
        $("#patientBtn").click(function(){
            if(validate())
            {
                submitReady = 1;
                $("#addform").submit();
            }
        })
        $("#deptList").change(function(){
            var deptID = $(this).val();

            $.ajax({
                url: "getRooms.php",
                type: "post",
                data: {dept: deptID, showFull: "n"},
                dataType: 'json',
                success:function(response){
                    var len = response.length;

                    $("#roomList").empty();
                    for(var i = 0; i<len; i++)
                    {
                        console.log(response);
                        var roomNum = response[i]['roomNumber'];
                        var text = response[i]['description'];

                        $("#roomList").append("<option value='"+roomNum+"'>"+roomNum+" | "+text+"</option>");
                    }
                }
            });
        });
    });
</script>
</div>
<footer class="footer">
    <div class="container-fluid"><i class="fas fa-user"></i> Logged in as: <?php echo "$_SESSION[user]";?>
    </div>
</footer>
</html>