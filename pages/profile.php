<?php 
session_start();
  if (!isset($_SESSION['login']))
  {
    header("location: ../index.php");
  }
  if(!isset($_SESSION['lat']) || !isset($_SESSION['lon']) )
    {
    require_once "dbconnect.php";

    try 
	  {
      $conn = new mysqli($servername, $username, $password, $dbname);
      if ($result=$conn->connect_errno!=0)
      {
        throw new Exception(mysqli_connect_errno());
      }
      else
      {
        
        $sql = $conn->query("SELECT * FROM userlocalization WHERE userID='".$_SESSION['id']."'");
        if (!$sql) throw new Exception($conn->error);

        $num_rows = $sql->num_rows; 
        if ($num_rows == 1)
        {
          $row = $sql->fetch_assoc();
          $_SESSION['lat'] = $row['Latitude'];
          $_SESSION['lng'] = $row['Longitude'];
        }
      }  
        
		$conn->close();
				
    }
	catch(Exception $e)
	{
		echo '<span style="color:red;">Server error! Please visit us later!</span>';
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
  <link rel="icon" type="image/png" sizes="16x16" href="favicon.png">
  <title>AlleCheap</title>
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
            <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Auctions</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Pricing</a>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto">
        <?php 
        if (!isset($_SESSION['login']))
          echo('<li class="nav-item">
            <a class="nav-link" href="pages/login.php"><button type="button" class="btn btn-primary">Login</button></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/register.php"><button type="button" class="btn btn-success">Sign In</button></a>
          </li>'); 
        else
        echo('<a style="margin-right:50px; margin-top:auto; margin-bottom:auto;" class="navbar-brand" href="profile.php">
        <img  src="'.$_SESSION['imgstatus'].'" style="width:40px; height:40px;"/>     Your Profile</a>  
        <li class="nav-item">
          <a class="nav-link" href="logout.php"><button type="button" class="btn btn-danger">Logout</button></a>
        </li>');?>
      </ul>
      </div>
    </nav>
  </header>


  <main>
    <br>
    <div class="container" >   
        <div class="row " style="border-style: solid; border-width: 1px; padding:15px; margin:1px;">
            <div class="col-md-4 col-lg-5">
                <img class="img-thumbnail img img-responsive full-width" src="<?php echo($_SESSION['imgstatus']);?>" alt="" style="width:100%;">
                <br><br>
                <p style="text-align: center;"> ID : <?php echo($_SESSION['id']); ?></p>
                <section class="mt-4 px-lg-3 " >
                    <a href="editprofile.php" class="btn btn-outline-secondary btn-lg btn-block text-truncate">
                        <i class="fas fa-pencil-alt" aria-hidden="true"></i>
                        Edit my profile
                    </a>
                    <br>
                </section>
            </div>
            <div class="col-md-8 col-lg-7">
                <div class="container" style="border-style: solid; border-width: 1px; padding:20px; border-color: DarkGray;">   
                    <div class="row ">
                        <div class="col-12 col-sm-4 text-truncate">
                            <p style="margin-bottom:0px;"> Your Username : </p>
                        </div>
                        <div class="col-12 col-sm-8 text-truncate">
                            <p style="font-weight: bold; margin-bottom:0px;"> <?php echo($_SESSION['username']); ?></p>
                        </div>
                    </div>   
                    <hr class="mt-1 mb-3"/>
                    <div class="row ">
                        <div class="col-12 col-sm-4 text-truncate" >
                            <p style="margin-bottom:0px;"> Your Firstname : </p>
                        </div>
                        <div class="col-12 col-sm-8 text-truncate" >
                            <p style="font-weight: bold; margin-bottom:0px;"> <?php echo($_SESSION['firstname']); ?></p>
                        </div>
                    </div>  
                    <hr class="mt-1 mb-3"/>
                    <div class="row ">
                        <div class="col-12 col-sm-4 text-truncate">
                        <p style="margin-bottom:0px;"> Your Lastname : </p>
                        </div>
                        <div class="col-12 col-sm-8 text-truncate">
                            <p style="font-weight: bold; margin-bottom:0px;"> <?php echo($_SESSION['lastname']); ?></p>
                        </div>
                    </div>   
                    <hr class="mt-1 mb-3"/>
                    <div class="row ">
                        <div class="col-12 col-sm-4 text-truncate">
                            <p style="margin-bottom:0px;"> Your E-mail : </p>
                        </div>
                        <div class="col-12 col-sm-8 text-truncate">
                            <p style="font-weight: bold; margin-bottom:0px;"> <?php echo($_SESSION['email']); ?></p>
                        </div>
                    </div>      
                    <hr class="mt-1 mb-3"/>
                </div>
            </div>
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
    
</body>
</html>