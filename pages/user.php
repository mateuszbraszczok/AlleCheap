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
  if ($_GET['id'] === $_SESSION['id'])
  {
    header("location: profile");
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
          $sql = $conn->query("SELECT users.*, userimg.status, userlocalization.*  FROM users INNER JOIN userimg ON users.ID=userimg.userID 
          LEFT JOIN userlocalization ON users.ID=userlocalization.userID WHERE users.ID='".$_GET['id']."'");
          if (!$sql) throw new Exception($conn->error);
          $row = $sql->fetch_assoc();
          
          $firstname =$row['firstname'];
          $lastname =$row['lastname'];
          $username =$row['username'];
          $email =$row['email'];
          $img =$row['status'];
          if (isset($row['Longitude'])){
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

  <?php require_once('header.php'); ?>


  <main>
    <br>
    <div class="container" style="border-style: solid; border-width: 1px; padding:15px; border-radius: 5px;">   
        <div class="row " >
            <div class="col-md-4 col-lg-5">
                <img class="img-thumbnail img img-responsive full-width" src="<?php echo($img);?>" alt="" style="width:100%;">
                <br><br>
                <p style="text-align: center; color:gray;"> ID : <?php echo($_GET['id']); ?></p>
            
            </div>
            <div class="col-md-8 col-lg-7">
                <div class="container" style="border-style: solid; border-width: 1px; padding:20px; border-color: DarkGray; border-radius: 5px;">   
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
          zoom: <?php echo(isset($lat) ? "11": "6"); ?>,
          center: { lat: <?php echo($lat ?? "51.327"); ?>, lng: <?php echo($lng ?? "19.067"); ?> },
        });
        marker = new google.maps.Marker({
          map,
          draggable: false,
          animation: google.maps.Animation.DROP,
          position: { lat: <?php echo($lat ?? "51.327"); ?>, lng: <?php echo($lng ?? "19.067"); ?> },
        });
        
      }
    </script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBvMgbRpn3ebemcufEZEVIjTyeJZAWn6WY&callback=initMap&libraries=&v=weekly" defer> </script>
</body>
</html>