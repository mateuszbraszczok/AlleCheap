<?php 
session_start();
  if (!isset($_SESSION['login']))
  {
    header("location: ../");
  }
  if (!isset($_GET['id']))
  {
    header("location: ../");
  }
  
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
            $sql = $conn->query("SELECT * FROM users WHERE ID='".$_GET['id']."'");
            if (!$sql) throw new Exception($conn->error);
            $row = $sql->fetch_assoc();
            $firstname =$row['firstname'];
            $lastname =$row['lastname'];
            $username =$row['username'];
            $email =$row['email'];

            $sql = $conn->query("SELECT * FROM userimg WHERE ID='".$_GET['id']."'");
            if (!$sql) throw new Exception($conn->error);
            $row = $sql->fetch_assoc();
            $img =$row['status'];

            $sql = $conn->query("SELECT * FROM userlocalization WHERE userID='".$_GET['id']."'");
            if (!$sql) throw new Exception($conn->error);

            $num_rows = $sql->num_rows; 
            if ($num_rows == 1)
            {
                $row = $sql->fetch_assoc();
                $lat = $row['Latitude'];
                $lng = $row['Longitude'];
                $street_number = $row['street_number'];
                $route = $row['street'];
                $city = $row['city'];
                $state = $row['region'];
                $country = $row['country'];
            }
    }  
    
    $conn->close();
				
    }
    catch(Exception $e)
    {
      echo '<span style="color:red;">Server error! Please visit us later!</span>';
      echo '<br />Info for devs: '.$e;
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
  <style type="text/css">
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 500px;
        width: 100%;
        margin-top:50px;
      }

      /* Optional: Makes the sample page fill the window. */
      html,
      body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

  <header> 
    <nav class="navbar navbar-expand-md navbar-light bg-light">
      <a style="margin-left:15px;" class="navbar-brand" href="../"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-basket" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1v4.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 13.5V9a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h1.217L5.07 1.243a.5.5 0 0 1 .686-.172zM2 9v4.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V9H2zM1 7v1h14V7H1zm3 3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 4 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 6 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5z"/>
      </svg> AlleCheap</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item active">
            <a class="nav-link" href="../">Home </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="auction_list">Auctions</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="sellform">Sell product</a>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto">
        <?php 
        if (!isset($_SESSION['login']))
          echo('<li class="nav-item">
            <a class="nav-link" href="pages/login"><button type="button" class="btn btn-primary">Login</button></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/register"><button type="button" class="btn btn-success">Sign In</button></a>
          </li>'); 
        else
        echo('<li class="nav-item dropdown"><a style="margin-right:50px; margin-top:auto; margin-bottom:auto;" class="nav-link dropdown-toggle" href="profile" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <img  src="'.$_SESSION['imgstatus'].'" style="width:40px; height:40px;"/>     Your Profile</a>  
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="profile">My Account</a>
          <a class="dropdown-item" href="editprofile">Edit Profile</a>
          <a class="dropdown-item" href="sellingdashboard">Selling Products</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="changeaccount">Change Account</a>
        </div></li>
        
        <li class="nav-item">
          <a class="nav-link" href="logout"><button type="button" class="btn btn-danger">Logout</button></a>
        </li>');?>
      </ul>
      </div>
    </nav>
  </header>


  <main>
    <br>
    <div class="container" style="border-style: solid; border-width: 1px; padding:15px; ">   
        <div class="row " >
            <div class="col-md-4 col-lg-5">
                <img class="img-thumbnail img img-responsive full-width" src="<?php echo($img);?>" alt="" style="width:100%;">
                <br><br>
                <p style="text-align: center; color:gray;"> ID : <?php echo($_GET['id']); ?></p>
            
            </div>
            <div class="col-md-8 col-lg-7">
                <div class="container" style="border-style: solid; border-width: 1px; padding:20px; border-color: DarkGray;">   
                    <div class="row ">
                        <div class="col-12 col-sm-4 text-truncate">
                            <p style="margin-bottom:0px;"> Your Username : </p>
                        </div>
                        <div class="col-12 col-sm-8 text-truncate">
                            <p style="font-weight: bold; margin-bottom:0px;"> <?php echo($username); ?></p>
                        </div>
                    </div>   
                    <hr class="mt-1 mb-3"/>
                    <div class="row ">
                        <div class="col-12 col-sm-4 text-truncate" >
                            <p style="margin-bottom:0px;"> Your Firstname : </p>
                        </div>
                        <div class="col-12 col-sm-8 text-truncate" >
                            <p style="font-weight: bold; margin-bottom:0px;"> <?php echo($firstname); ?></p>
                        </div>
                    </div>  
                    <hr class="mt-1 mb-3"/>
                    <div class="row ">
                        <div class="col-12 col-sm-4 text-truncate">
                        <p style="margin-bottom:0px;"> Your Lastname : </p>
                        </div>
                        <div class="col-12 col-sm-8 text-truncate">
                            <p style="font-weight: bold; margin-bottom:0px;"> <?php echo($lastname); ?></p>
                        </div>
                    </div>   
                    <hr class="mt-1 mb-3"/>
                    <div class="row ">
                        <div class="col-12 col-sm-4 text-truncate">
                            <p style="margin-bottom:0px;"> Your E-mail : </p>
                        </div>
                        <div class="col-12 col-sm-8 text-truncate">
                            <p style="font-weight: bold; margin-bottom:0px;"> <?php echo($email); ?></p>
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
                            <p style="font-weight: bold; margin-bottom:0px;"> <?php if(isset($route)) echo($route. "  ".$street_number); ?></p>
                        </div>
                    </div>      
                    <hr class="mt-1 mb-3"/>
                    <div class="row ">
                        <div class="col-12 col-sm-4 text-truncate">
                            <p style="margin-bottom:0px;"> Your City : </p>
                        </div>
                        <div class="col-12 col-sm-8 text-truncate">
                            <p style="font-weight: bold; margin-bottom:0px;"> <?php if(isset($city)) echo($city); ?></p>
                        </div>
                    </div>      
                    <hr class="mt-1 mb-3"/>
                    <div class="row ">
                        <div class="col-12 col-sm-4 text-truncate">
                            <p style="margin-bottom:0px;"> Your State : </p>
                        </div>
                        <div class="col-12 col-sm-8 text-truncate">
                            <p style="font-weight: bold; margin-bottom:0px;"> <?php if(isset($state)) echo($state); ?></p>
                        </div>
                    </div>      
                    <hr class="mt-1 mb-3"/>
                    <div class="row ">
                        <div class="col-12 col-sm-4 text-truncate">
                            <p style="margin-bottom:0px;"> Your Country : </p>
                        </div>
                        <div class="col-12 col-sm-8 text-truncate">
                            <p style="font-weight: bold; margin-bottom:0px;"> <?php if(isset($country)) echo($country); ?></p>
                        </div>
                    </div>      
                    <hr class="mt-1 mb-3"/>
                    
                </div>
            </div>
        </div>
        <hr>
        <?php if (isset($lat)) {?>
        <div id='map'></div>
        <?php } ?>
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
      function initMap() {
        const map = new google.maps.Map(document.getElementById("map"), {
          zoom: <?php if(isset($lat)) echo "10";  else echo"6"; ?>,
          center: { lat: <?php if(isset($lat)) echo $lat;  else echo"51.327"; ?>, lng: <?php if(isset($lng)) echo $lng;  else echo"19.067"; ?> },
        });
        marker = new google.maps.Marker({
          map,
          draggable: false,
          animation: google.maps.Animation.DROP,
          position: { lat: <?php if(isset($lat)) echo $lat;  else echo"51.327"; ?>, lng: <?php if(isset($lng)) echo $lng;  else echo"19.067"; ?> },
        });
        
      }
    </script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBvMgbRpn3ebemcufEZEVIjTyeJZAWn6WY&callback=initMap&libraries=&v=weekly" defer> </script>
</body>
</html>