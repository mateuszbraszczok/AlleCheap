<?php 
session_start();
  if (!isset($_SESSION['login']))
  {
    header("location: ../");
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
          $_SESSION['street_number'] = $row['street_number'];
          $_SESSION['route'] = $row['street'];
          $_SESSION['city'] = $row['city'];
          $_SESSION['state'] = $row['region'];
          $_SESSION['country'] = $row['country'];
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
  <meta http-equiv="Cache-Control" content="no-store" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
  <script src='https://kit.fontawesome.com/a076d05399.js'></script>
  <link rel="stylesheet" href="styles.css" type="text/css"/>  
  <link rel="icon" type="image/png" sizes="16x16" href="../favicon.png">
  <title>AlleCheap</title>
  <style>
    hr.hr-text {
      position: relative;
      border: none;
      height: 1px;
      background: #999;
    }
    hr.hr-text::before {
      content: attr(data-content);
      display: inline-block;
      background: #fff;
      font-weight: bold;
      font-size: 1rem;
      color: #999;
      border-radius: 30rem;
      padding: 0.2rem 1rem;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }
  </style>
</head>

<body class="d-flex flex-column min-vh-100">

  <?php require_once('header.php'); ?>


  <main>
    <br>
    <div class="container" >   
        <div class="row " style="border-style: solid; border-width: 1px; padding:15px; margin:1px; border-radius: 5px;">
            <div class="col-md-4 col-lg-5">
                <img class="img-thumbnail img img-responsive full-width" src="<?php echo($_SESSION['imgstatus']);?>" alt="" style="width:100%;">
                <br><br>
                <p style="text-align: center; color:gray;"> ID : <?php echo($_SESSION['id']); ?></p>
                <section class="mt-4 px-lg-3 " >
                    <a href="editprofile" class="btn btn-outline-secondary btn-lg btn-block text-truncate">
                        <i class="fas fa-pencil-alt" aria-hidden="true"></i>
                        Edit my profile
                    </a>
                    <br>
                </section>
            </div>
            <div class="col-md-8 col-lg-7">
                <div class="container" style="border-style: solid; border-width: 1px; padding:20px; border-color: DarkGray; border-radius: 5px;">   
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
                    <br>  
                    <hr data-content="Your address" class="hr-text"> 
                    <div class="row ">
                        <div class="col-12 col-sm-4 text-truncate">
                            <p style="margin-bottom:0px;"> Your Street : </p>
                        </div>
                        <div class="col-12 col-sm-8 text-truncate">
                            <p style="font-weight: bold; margin-bottom:0px;"> <?php if(isset($_SESSION['route'])) echo($_SESSION['route']. "  ".$_SESSION['street_number']); ?></p>
                        </div>
                    </div>      
                    <hr class="mt-1 mb-3"/>
                    <div class="row ">
                        <div class="col-12 col-sm-4 text-truncate">
                            <p style="margin-bottom:0px;"> Your City : </p>
                        </div>
                        <div class="col-12 col-sm-8 text-truncate">
                            <p style="font-weight: bold; margin-bottom:0px;"> <?php if(isset($_SESSION['city'])) echo($_SESSION['city']); ?></p>
                        </div>
                    </div>      
                    <hr class="mt-1 mb-3"/>
                    <div class="row ">
                        <div class="col-12 col-sm-4 text-truncate">
                            <p style="margin-bottom:0px;"> Your State : </p>
                        </div>
                        <div class="col-12 col-sm-8 text-truncate">
                            <p style="font-weight: bold; margin-bottom:0px;"> <?php if(isset($_SESSION['state'])) echo($_SESSION['state']); ?></p>
                        </div>
                    </div>      
                    <hr class="mt-1 mb-3"/>
                    <div class="row ">
                        <div class="col-12 col-sm-4 text-truncate">
                            <p style="margin-bottom:0px;"> Your Country : </p>
                        </div>
                        <div class="col-12 col-sm-8 text-truncate">
                            <p style="font-weight: bold; margin-bottom:0px;"> <?php if(isset($_SESSION['country'])) echo($_SESSION['country']); ?></p>
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