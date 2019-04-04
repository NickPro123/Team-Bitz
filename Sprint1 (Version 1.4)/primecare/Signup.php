<?php
	require_once 'functions.php';
?>
<!DOCTYPE html>
<div lang='en'>
	<head>
       <link rel="stylesheet" type="text/css" href="css/style.css">
      <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
      <link href="https://fonts.googleapis.com/css?family=Lato|Raleway:400,700,900" rel="stylesheet">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Prime Health Care - Sign Up</title>
	</head>
        <script>
		
          function validate(form)
          {
            fail = validateName(form.firstName.value)
            fail += validateName(form.lastName.value)
            fail += validatePassword(form.password.value)
            fail += validateConfirmPassword(form.password.value, form.confirmPass.value)
<!--            fail += validateEmail(form.email.value) -->

            if   (fail == ''){   return true }
            else { alert(fail); return false }
          }

          function validateName(field)
          {
            if (field == '') return 'No name was entered.\n'
            else if (field.length < 2)
              return 'Names must be at least 2 characters.\n'
            else if (/[^a-zA-Z']/.test(field))
              return "Only a-z, A-Z and ' allowed in a name.\n"
            return ''
          }

          function validatePassword(field)
          {
            if (field == '') return 'No Password was entered.\n'
            else if (field.length < 6)
              return 'Passwords must be at least 6 characters.\n'
            else if (! /[a-z]/.test(field) ||
                     ! /[A-Z]/.test(field) ||
                     ! /[0-9]/.test(field))
              return 'Passwords require one each of a-z, A-Z and 0-9.\n'

            return ''
          }

          function validateConfirmPassword(field, field2){
            if (field != field2)
            return 'Passwords do not match.\n'
            return ''
          }
			
		


        </script>
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
                  <a class="nav-link" href="index.html">Home <span class="sr-only">(current)</span></a>
              </li>
          </ul>
          <div class="form-inline my-2 my-lg-0">
              <a href="login.php">
                  <button class="btn btn-outline-success my-2 my-sm-0" type="button">Log In</button>
              </a>
          </div>
          <div class="form-inline my-2 ml-lg-2">
              <a href="Signup.php">
                  <button class="btn btn-outline-success my-2 my-sm-0" type="button">Sign Up</button>
              </a>
          </div>
      </div>
  </nav>
  
  <header>
    <a href='index.html'>
      <img src='images/logo.png' alt='PrimeCare' class='logo'>
    </a>
  </header>
  <div class="container">
  <body>
      <form method='post' action='adduser.php' onsubmit='return validate(this)' class='signUp'>
          <div class ="formHeader">Sign Up Form</div>
          <div class ="form">
          <label for='firstName'>First Name</label>
          <input type='text' maxlength='24' id='firstName' name='firstName' required='required'>

          <label for='lastName'>Last Name</label>
          <input type='text' maxlength='64' id='lastName' name='lastName' required='required'>

          <label for='password'>Password (Minimum 6 characters, Must use Uppercase, Lowercase and a Number.)</label>
          <input type='password' maxlength='128' id='password' name='password' required='required'>

          <label for='confirmPass'>Confirm Password</label>
          <input type='password' maxlength='128' id='confirmPass' name='confirmPass' required='required'>
              <br>
		  
		  <label for='type'>Type of User: </label>
              <div>
                  <input type ='radio' name='type' value='doctor' required='required'> Doctor
                  <input type ='radio' name='type' value='nurse' required='required'> Nurse<br>
              </div><br>
		  
		  <label>Department: </label>
		  <select name = 'dept'>";
                <?php
                      $result = queryMysql("select departmentID,departmentName from department");
                      if ($result->num_rows > 0)
                      {
                        while ($row = mysqli_fetch_assoc($result))
                        {
                            echo '<option value=" '.$row['departmentID'].' "> '.$row['departmentID']. ' - ' .$row['departmentName'].'</option>';
                        }
                      }
                ?>
		  </select>
              <br><br>
		
	      <input type='submit' value='Sign Up'>
      </form>
    <!--  <form method='post' action='login.php' onsubmit='return true' class='signUp'>
          <input type='submit' value='Login'>
      </form> -->
      </div>
            <!-- Optional JavaScript -->
      <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</div>
    <footer class="footer">
        <div class="container-fluid"> Footer content goes here
        </div>
    </footer>
</html>
