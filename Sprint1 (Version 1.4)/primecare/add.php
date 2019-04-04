<?php 

session_start();
require_once 'functions.php';

$title = $system = $company = $genre = $year = "";
//echo "<script>fail = ''</script>"; 

if (!empty($_SESSION['user']))
  {
      
      if (empty($_POST['title']) || empty($_POST['system']) || empty($_POST['company']) || empty($_POST['genre']) || empty($_POST['year']))
      {  
          
      }else{
   
   
   $title = sanitizeString($_POST['title']);
   $system = sanitizeString($_POST['system']);
   $company = sanitizeString($_POST['company']);
   $genre = sanitizeString($_POST['genre']);
   $year = sanitizeString($_POST['year']);
      
 $result = queryMysql("INSERT INTO video_game(title, system, company, genre, year) VALUES ('$title', '$system', '$company', '$genre', '$year')");

  }
  //$error = "Not all fields were entered";
echo "<!DOCTYPE html>
 <script>
function validate(form)
      {
          fail = ''
      if (form.title.value == '' || form.system.value == '' || form.company.value == '' || form.genre.value == '' || form.year.value == '')
     {fail = 'Not all fields were enterd'}
     
        if   (fail == '')
           return true
        else { 
            alert(fail); 
            return false }
      }
  </script>
  <body>
   <form method='post' action='add.php' onsubmit='return validate(this)'>

Title: <input type='text' name='title' value='' /><br />
System: <input type='text' name='system' value='' /><br />
Company: <input type='text' name='company' value='' /><br />
Genre: <input type='text' name='genre' value='' /><br />
Year: <input type='text' name='year' value='' /><br />

<p>
<tr><td colspan='2' align='center'><input type='submit' value='Submit'></td></tr>
</p>
</form>
 <form method='post' action='main.php' onsubmit='return true'>
        <tr><td colspan='2' align='center'><input type='submit' value='Main Menu'></td></tr>
 </body>
</form>
  </html>";

}else{
     
  echo "You are not logged in
  <a href='Signup.html'>click here</a> to refresh the screen.";
}
  ?>