<!DOCTYPE html>
<html>
<head>
    <link rel='stylesheet' type='text/css' href='css/style.css'>
    <title>Prime Health Care - Your User Name Is</title>
</head>
<header>
    <img src="images/logo.png" class="logo" alt="HealthCare Logo">
</header>
<body>
    <?php
    require_once 'functions.php';
 
  if (isset($_POST['firstName']))
  {
    $firstName = fix_string($_POST['firstName']);
	$firstName = sanitizeString($firstName);
  }
	
  if (isset($_POST['lastName']))
  {
    $lastName = fix_string($_POST['lastName']);
	$lastName = sanitizeString($lastName);
  }
	
  if (isset($_POST['password']))
  {
    $password = fix_string($_POST['password']);
	$password = sanitizeString($password);
  }
	
  if (isset($_POST['confirmPass']))
  {
    $confirmPass = fix_string($_POST['confirmPass']);
  	$confirmPass = sanitizeString($confirmPass);
  }
	
  if(isset($_POST['type']))
  {
	$type = fix_string($_POST['type']);
	$type = sanitizeString($type);
  }
	
  if(isset($_POST['dept']))
  {
	$dept = fix_string($_POST['dept']);
    $dept = sanitizeString($dept);
  }

 
//  $fail = validate_username($firstName);
//  $fail .= validate_email($lastName);
  $fail = validate_password($password);
  $fail .= validate_confirmpass($password, $confirmPass);

  //CALL GENERATE USERNAME HERE
  $username = create_username($firstName, $lastName);
  

  //echo "<!DOCTYPE html>\n<html><head><title>An Example Form</title>";

  if ($fail == "")
  {
   // echo "</head><body>Form data successfully validated:
    //   $username, $password, $email.</body></html>";
    $salt1 = generateSalt();
    $salt2 = generateSalt();
    $hash = hash('ripemd128', "$salt1$password$salt2");
    
    
    
    add_user($connection, $firstName, $lastName, $type, $dept, $hash, $salt1, $salt2, $username );
	

    //ADD CODE TO DISPLAY USERS GENERATED USERNAME HERE
    
    echo " 
        <div>
            <p>Your username is $username</p>
            <a href='login.php'><button type='button'>Log In</button></a>
        </div>";
   
  }




  // The PHP functions

/*
  function validate_firstName($field)
  {
    if ($field == "") return "No first name was entered<br>";
    else if (strlen($field) < 2)
      return "Usernames must be at least 2 characters<br>";
    else if (preg_match("/[^a-zA-Z0-9_-]/", $field))
      return "Only letters, numbers, - and _ in usernames<br>";
    return "";		
  }
*/

//Generate a unique username using Database
function create_username($firstName, $lastName,  $sec_no = 0)
  {
    while(true)
    {
        $part1 = substr($lastName, 0,4); //cut second name to 5 letters
        $part2 = substr($firstName, 0,1); //cut first name to 8 letters
        if($sec_no == 0)
        {
            $part3 = "";
        }
        else
        {
            $part3 = $sec_no;
        }
		
        $username = $part1. $part2. $part3; //str_shuffle to randomly shuffle all characters 
        $username = strtolower($username);
    //   return $username;
		
    
        $username_exist_in_db = username_exist_in_database($username); //check username in database
        if(!$username_exist_in_db)
        {
            return $username;
        }
        
        else
        {
            create_username($firstName,$lastName, ++$sec_no);
        }
       
    
    }
}





  function validate_password($field)
  {
    if ($field == "") return "No Password was entered<br>";
    else if (strlen($field) < 6)
      return "Passwords must be at least 6 characters<br>";
    else if (!preg_match("/[a-z]/", $field) ||
             !preg_match("/[A-Z]/", $field) ||
             !preg_match("/[0-9]/", $field))
      return "Passwords require 1 each of a-z, A-Z and 0-9<br>";
    return "";
  }
  
    function validate_confirmpass($field, $field2){
        if ($field != $field2)
        return "Passwords do not match.\n";
        return "";
      }
  
/*  function validate_email($field)
  {
    if ($field == "") return "No Email was entered<br>";
      else if (!((strpos($field, ".") > 0) &&
                 (strpos($field, "@") > 0)) ||
                  preg_match("/[^a-zA-Z0-9.@_-]/", $field))
        return "The Email address is invalid<br>";
    return "";
  }
*/
  
  function fix_string($string)
  {
    if (get_magic_quotes_gpc()) $string = stripslashes($string);
    return htmlentities ($string);
  }
?>
</body>
</html>