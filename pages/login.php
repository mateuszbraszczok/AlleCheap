<?php

  session_start();

  
  if (isset($_SESSION['login']))
    header("location: ../");

    if ((isset($_POST['username'])) && (isset($_POST['pass'])))
    { 
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
          $username = $_POST['username'];
          $pass = $_POST['pass'];
          
          $username = htmlentities($username, ENT_QUOTES, "UTF-8");
        
          if ($result = $conn->query(
          sprintf("SELECT * FROM users WHERE username='%s'",
          mysqli_real_escape_string($conn,$username))))
          {
            $how_many_users = $result->num_rows;
            if($how_many_users>0)
            {
              $row = $result->fetch_assoc();
              
              if (password_verify($pass, $row['pass']))
              {  
                $_SESSION['login'] = true;
                $_SESSION['username'] = $row['username'];
                $_SESSION['firstname'] = $row['firstname'];
                $_SESSION['lastname'] = $row['lastname'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['id'] = $row['ID'];

                $sql = "SELECT * FROM userimg WHERE userID=".$_SESSION['id'];      
                $result = $conn->query($sql);  
                if (!$result) throw new Exception($conn->error);
                if($result->num_rows>0)    
                {
                  $row = $result->fetch_assoc();

                  $_SESSION['imgstatus']=$row['status'];   
                } 
                else
                {
                  if ($conn->query("INSERT INTO userimg VALUES (NULL, '".$_SESSION['id']."', 'profile_pictures/default.png')"))
                  {
                    $_SESSION['imgstatus']='profile_pictures/default.png'; 
                  }
                  else
                  {
                    throw new Exception($conn->error);
                  }  
                }
                  
                unset($_SESSION['error']);
                $result->free_result();
                if(isset($_SESSION['from']))
                {
                  $from=$_SESSION['from'];
                  unset($_SESSION['from']);
                  header("location: ".$from."");
                }
                else
                {
                  header("location: ../");
                }
                
              }
              else 
              {
                $_SESSION['error'] = '<p style="color:red">Incorrect Username or Password!</p>';         
              }
              
            } else {
              
              $_SESSION['error'] = '<p style="color:red">Incorrect Username or Password!</p>'; 
            }
            
          }
          else
          {
            throw new Exception($conn->error);
          }
          
          $conn->close();
        }
      }
      catch(Exception $e)
      {
        echo '<p style="color:red;">Server error! Please visit us later!</p>';
        echo '<br />Info for devs: '.$e;
      }
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
  <link rel="stylesheet" href="styles.css" type="text/css"/>  
  <link rel="icon" type="image/png" sizes="16x16" href="../favicon.png">
  <title>AlleCheap</title>
</head>

<body class="d-flex flex-column min-vh-100">

 <?php require_once('header.php'); ?>


  <main>
    <br>
    <div class="container">   
    <div class="row ">
        <div class="col-sm-2 col-lg-3"></div>
        <div class="col-sm-8 col-lg-6" >
          <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <fieldset style=" padding:20px; border: 1px solid lightgray; border-radius: 5px;">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" aria-describedby="emailHelp" placeholder="Enter your username" required>
            </div>
            <div class="form-group" style="margin-bottom:8px;">
                <label for="pass">Password</label>
                <input type="password" class="form-control" id="pass" name="pass" placeholder="Password" required>    
            </div>
            <div class="form-check" style="font-size: 14px;">
                <input type="checkbox" class="form-check-input" id="exampleCheck1" onclick="ShowPassword()">
                <label class="form-check-label" for="exampleCheck1">Show Password</label>        
            </div>
            <?php if(isset($_SESSION['error']))
              echo($_SESSION['error']);
            ?>
            <br>
            <button type="submit" class="btn btn-primary">Login</button><br>
            <a href="register"><small> Don't have an account? Just register!</small></a>
            </fieldset>
          </form>
          
          
        </div>
        <div class="col-sm-2 col-lg-3"></div>
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
  
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
<script>
  function ShowPassword() {
    var x = document.getElementById("pass");
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
}
</script> 
</body>
</html>