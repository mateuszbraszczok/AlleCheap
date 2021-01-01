<?php

  session_start();
  
  if (isset($_SESSION['login']))
    header("location: ../index.php");

  $FirstName = $LastName = $Username = $email_address = $pass1 = $pass2 = "";
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ProperData = true;
    $FirstName = test_input($_POST["FirstName"]);
    $LastName = test_input($_POST["LastName"]);
    $Username = test_input($_POST["Username"]);
    $email_address = test_input($_POST["email_address"]);
    $pass1 = test_input($_POST["pass1"]);
    $pass2 = test_input($_POST["pass2"]);
  

    $emailB = filter_var($email_address, FILTER_SANITIZE_EMAIL);
		
		if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email_address))
		{
			$ProperData=false;
			$_SESSION['error_mail']="Incorrect e-mail";
    }
    
    $pass_hash = password_hash($pass1, PASSWORD_DEFAULT);
    
    $SecretKey = "6LeHYRoaAAAAALC4NOjo7B7KENGHFvhoieAYa0bX";
		
		$check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$SecretKey.'&response='.$_POST['g-recaptcha-response']);
		
		$answer = json_decode($check);
		
		if ($answer->success==false)
		{
			$ProperData=false;
			$_SESSION['error_recaptcha']="Please confirm reCAPTCHA";
    }
    $_SESSION['fr_FirstName'] = $FirstName;
		$_SESSION['fr_LastName'] = $LastName;
		$_SESSION['fr_Username'] = $Username;
    $_SESSION['fr_email_address'] = $email_address;
    $_SESSION['fr_pass1'] = $pass1;
    if (isset($_POST['checkbox1'])) 
      $_SESSION['fr_checkbox1'] = true;


    require_once "dbconnect.php";


    try 
		{
      $conn = new mysqli($servername, $username, $password, $dbname);
      if ($conn->connect_errno!=0)
      {
        throw new Exception(mysqli_connect_errno());
      }
      else
      {
        //is it email exist in db?
        $sql = $conn->query("SELECT id FROM users WHERE email='$email_address'");
          
        if (!$sql) throw new Exception($conn->error);
          
        $how_many_mails = $sql->num_rows;
        if($how_many_mails>0)
        {
          $ProperData=false;
          $_SESSION['error_mail']="Account with this e-mail already exists!";
        }		

        //is it username exist in db?
        $sql = $conn->query("SELECT id FROM users WHERE username='$Username'");
          
        if (!$sql) throw new Exception($conn->error);
          
        $how_many_usernames = $sql->num_rows;
        if($how_many_usernames>0)
        {
          $ProperData=false;
          $_SESSION['error_username']="Account with this username already exists!";
        }
          
        if ($ProperData==true)
        {
          //Adding new user to db
            
          if ($conn->query("INSERT INTO users VALUES (NULL, '$FirstName', '$LastName', '$Username', '$email_address', '$pass_hash')"))
          {
            $_SESSION['username'] = $Username;
            $_SESSION['firstname'] = $FirstName;
            $_SESSION['lastname'] = $LastName;
            $_SESSION['email'] = $email_address;
            header('Location: login.php');
          }
          else
          {
            throw new Exception($conn->error);
          }   
        }
				$conn->close();
			}	
		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Server error! Please visit us later!</span>';
			echo '<br />Info for devs: '.$e;
		}
  }


  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-Ua-Compatible" content="IE=edge">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
  <script src='https://kit.fontawesome.com/a076d05399.js'></script>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <link rel="stylesheet" href="styles.css" type="text/css"/>  
  <link rel="icon" type="image/png" sizes="16x16" href="../favicon.png">
  <title>AlleCheap</title>
  <style>
		.error
		{
			color:red;
			margin-top: 10px;
			margin-bottom: 10px;
		}
	</style>
</head>

<body class="d-flex flex-column min-vh-100">

  <header>
    <nav class="navbar navbar-expand-md navbar-light bg-light">
      <a style="margin-left:15px;" class="navbar-brand" href="../index.php"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-basket" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1v4.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 13.5V9a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h1.217L5.07 1.243a.5.5 0 0 1 .686-.172zM2 9v4.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V9H2zM1 7v1h14V7H1zm3 3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 4 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 6 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5z"/>
      </svg> AlleCheap</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item active">
            <a class="nav-link" href="../index.php">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Auctions</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Pricing</a>
          </li>

        </ul>
        <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="login.php"><button type="button" class="btn btn-primary">Login</button></a>
        </li>
        <li class="nav-item">
          <a class="nav-link"><button type="button" class="btn btn-success">Sign In</button></a>
        </li>
      </ul>
      </div>
    </nav>
  </header>


  <main>
    <br>
    <div class="container">
    <div class="row ">
        <div class="col-sm-1 col-lg-2"></div>      
        <div class="col-sm-9 col-lg-7" >
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
          <fieldset style=" padding:20px; border: 1px solid lightgray;">
          <form>
          <div class="form-row">
              <div class="form-group col-md-6">
              <label for="FirstName">First Name</label>
              <input type="text" class="form-control" id="FirstName" name="FirstName" placeholder="First Name" required pattern="[A-BD-Za-z0-9()._-‘]+" value="<?php
                if (isset($_SESSION['fr_FirstName']))
                {
                  echo $_SESSION['fr_FirstName'];
                  unset($_SESSION['fr_FirstName']);
                }
              ?>">
              </div>        
              <div class="form-group col-md-6">
              <label for="LastName">Last Name</label>
              <input type="text" class="form-control" id="LastName" name="LastName" placeholder="Last Name" required pattern="[A-BD-Za-z0-9()._-‘]+" value="<?php
                if (isset($_SESSION['fr_LastName']))
                {
                  echo $_SESSION['fr_LastName'];
                  unset($_SESSION['fr_LastName']);
                }
              ?>">
              </div>
          </div>
          <div class="form-group ">
              <label for="inputUserName">Username (4 to 20 characters)</label>
              <input type="text" class="form-control" id="inputUserName" name="Username" placeholder="Username" required pattern=".{4,20}"  title="4 to 20 characters" value="<?php
                if (isset($_SESSION['fr_Username']))
                {
                  echo $_SESSION['fr_Username'];
                  unset($_SESSION['fr_Username']);
                }
              ?>">
              <?php
                if (isset($_SESSION['error_username']))
                {
                  echo '<div class="error">'.$_SESSION['error_username'].'</div>';
                  unset($_SESSION['error_username']);
                }
              ?>
          </div>
          <div class="form-group ">
              <label for="inputEmail">Your e-mail</label>
              <input type="email" class="form-control" id="inputEmail" name="email_address" placeholder="Email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" value="<?php
                if (isset($_SESSION['fr_email_address']))
                {
                  echo $_SESSION['fr_email_address'];
                  unset($_SESSION['fr_email_address']);
                }
              ?>">
              <?php
                if (isset($_SESSION['error_mail']))
                {
                  echo '<div class="error">'.$_SESSION['error_mail'].'</div>';
                  unset($_SESSION['error_mail']);
                }
              ?>
              <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
          </div>
          <div class="form-row">
              <div class="form-group col-md-6">
                <label for="inputPassword">Password (8 to 20 characters)</label>
                <input type="password" class="form-control" id="inputPassword" name="pass1" placeholder="Password" required 
                pattern="(?=^.{8,20}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$"   title="UpperCase, LowerCase, Number/SpecialChar and 8 to 20 Chars" value="<?php
                if (isset($_SESSION['fr_pass1']))
                {
                  echo $_SESSION['fr_pass1'];
                  unset($_SESSION['fr_pass1']);
                }
              ?>">
              </div>
              <div class="form-group col-md-6">
              <label for="confirmPassword" id="labpass">Confirm Password</label>
              <input type="password" class="form-control" id="confirmPassword" name="pass2" placeholder="Confirm Password" required pattern="(?=^.{8,20}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$"  title="UpperCase, LowerCase, Number/SpecialChar and 8 to 20 Chars">
              <p id="passHelp" class="form-text" style="color:red; visibility:hidden;">test</p>
              </div>
          </div>

          <div class="form-group">
              <div class="form-check">
              <input class="form-check-input" type="checkbox" name="checkbox1" id="gridCheck" required <?php
                if (isset($_SESSION['fr_checkbox1']))
                {
                  echo "checked";
                  unset($_SESSION['fr_checkbox1']);
                }
                  ?>/>
              <label class="form-check-label" for="gridCheck">
                  I am over 18 years old
              </label>
              </div>
          </div>
          <div class="g-recaptcha" data-sitekey="6LeHYRoaAAAAAHP8fKuA3s448k98-uyxNyiDKehU"></div>
          <?php
            if (isset($_SESSION['error_recaptcha']))
            {
              echo '<div class="error">'.$_SESSION['error_recaptcha'].'</div>';
              unset($_SESSION['error_recaptcha']);
            }
          ?>	
          <br>
          <button type="submit" class="btn btn-success" id="submbutt">Sign in</button>
          </form>
          </fieldset>
        </form>
        </div>
        <div class="col-sm-2 col-lg-1"></div>
    </div>
    </div>
    <br>
  </main>

  
    
  <div class="wrapper flex-grow-1"></div>
  <footer class="bg-light text-center text-lg-start">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.3)">
      © 2021 Copyright:
      <span class="text-dark">Braszczok & Wojciechowski</span>
    </div>
  </footer>


  <script>
    const email = document.getElementById("inputEmail");
    const pass1 = document.getElementById("inputPassword");
    const pass2 = document.getElementById("confirmPassword");
    email.addEventListener("input", function (event) {
      if (email.validity.typeMismatch) {
        email.setCustomValidity("I am expecting an valid e-mail address");
        
      } else {
        email.setCustomValidity("");
        
      }
    });
    pass2.addEventListener("input", function (event) {
      if (pass2.value == pass1.value) {
        pass2.setCustomValidity("");
        document.getElementById("passHelp").innerHTML  = "test";
        document.getElementById("passHelp").style.visibility = 'hidden';
      } else {
        pass2.setCustomValidity("Passwords vary");           
        document.getElementById("passHelp").innerHTML  = "Passwords vary";
        document.getElementById("passHelp").style.visibility = 'visible';
      }
    });
</script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    
</body>
</html>