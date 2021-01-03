<?php 
session_start();
  if (!isset($_SESSION['login']))
  {
    header("location: ../index.php");
  }
  $FirstName = $LastName = $Username = "";
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $ProperData = true;
    $FirstName = test_input($_POST["FirstName"]);
    $LastName = test_input($_POST["LastName"]);
    $UserName = test_input($_POST["UserName"]);
    

    if($FirstName !== $_SESSION['firstname'])
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
          $sql = "UPDATE users SET firstname='".$FirstName."' WHERE id=".$_SESSION['id'];        
          if (!$conn->query($sql)) throw new Exception($conn->error);
          else $_SESSION['firstname']=$FirstName;        
          $conn->close();
        }	
      }
      catch(Exception $e)
      {
        echo '<span style="color:red;">Server error! Please visit us later!</span>';
        echo '<br />Info for devs: '.$e;
      }
    }
    if($LastName !== $_SESSION['lastname'])
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
          $sql = "UPDATE users SET lastname='".$LastName."' WHERE id=".$_SESSION['id'];        
          if (!$conn->query($sql)) throw new Exception($conn->error);
          else $_SESSION['lastname']=$LastName;        
          $conn->close();
        }	
      }
      catch(Exception $e)
      {
        echo '<span style="color:red;">Server error! Please visit us later!</span>';
        echo '<br />Info for devs: '.$e;
      }
    }
    if($UserName !== $_SESSION['username'])
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
          $sql = "UPDATE users SET username='".$UserName."' WHERE id=".$_SESSION['id'];        
          if (!$conn->query($sql)) throw new Exception($conn->error);
          else $_SESSION['username']=$UserName;        
          $conn->close();
        }	
      }
      catch(Exception $e)
      {
        echo '<span style="color:red;">Server error! Please visit us later!</span>';
        echo '<br />Info for devs: '.$e;
      }
    }
    if(isset($_FILES['img']))
    {
    $file = $_FILES['img'];
    $fileName = $_FILES['img']['name'];
    $fileTmpName = $_FILES['img']['tmp_name'];
    $fileSize = $_FILES['img']['size'];
    $fileError = $_FILES['img']['error'];
    $fileType = $_FILES['img']['type'];

    $fileExt = explode('.',$fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg','jpeg','png');

    if(in_array($fileActualExt,$allowed)) {
      if($fileActualExt =='jpeg')
        $fileActualExt='jpg';
      if ($fileError === 0){
        $fileNameNew = "profile".$_SESSION['id'].".".$fileActualExt;
        $fileDestination = 'profile_pictures/'.$fileNameNew ;
        move_uploaded_file($fileTmpName, $fileDestination);
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
            $sql = "UPDATE userimg SET status='".$fileDestination."' WHERE userID=".$_SESSION['id'];      
            if (!$conn->query($sql)) throw new Exception($conn->error);
            else{
              $_SESSION['imgstatus']=$fileDestination;  
            }
              
            $wmax = 640;
          	$hmax = 640; 
            img_resize($fileDestination, $fileDestination, $wmax, $hmax, $fileActualExt);    
            $conn->close();
          }	
        }
        catch(Exception $e)
        {
          echo '<span style="color:red;">Server error! Please visit us later!</span>';
          echo '<br />Info for devs: '.$e;
        }
      }
      else {
        echo "There was an error uploading your file!";
      }
    }
    else {
      echo "You cannot upload files of this type!";
    }
  }
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
  
  function img_resize($target, $newcopy, $w, $h, $ext) {
    list($w_orig, $h_orig) = getimagesize($target);

    $img = "";
    if ($ext == "gif"){ 
      $img = imagecreatefromgif($target);
    } else if($ext =="png"){ 
      $img = imagecreatefrompng($target);
    } else { 
      $img = imagecreatefromjpeg($target);
    }
    $tci = imagecreatetruecolor($w, $h);
    // imagecopyresampled(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
    imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
    imagejpeg($tci, $newcopy, 84);
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
  <META HTTP-EQUIV='Pragma' CONTENT='no-cache'>
  <META HTTP-EQUIV="Expires" CONTENT="-1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
  <script src='https://kit.fontawesome.com/a076d05399.js'></script>
  <link rel="stylesheet" href="styles.css" type="text/css"/>  
  <link rel="icon" type="image/png" sizes="16x16" href="../favicon.png">
  <title>AlleCheap</title>
  <style>
    * {
      box-sizing: border-box;
    }

    #output {
      width: 440px;
      height: 440px;
    }

    @media screen and (max-width: 992px) {
      #output {
        width: 400px;
        height: 400px;
      }
    }

    @media screen and (max-width: 600px) {
      #output {
        width: 300px;
        height: 300px;
      }
    }
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
      padding: 0.2rem 2rem;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }
    .custom-file-upload {
        border: 1px solid #ccc;
        display: inline-block;
        padding: 6px 12px;
        cursor: pointer;
    }
    
</style>
<style type="text/css">
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 500px;
        width: 100%;
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
            <a class="nav-link" href="sellform.php">Sell product</a>
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
        echo('<li class="nav-item dropdown"><a style="margin-right:50px; margin-top:auto; margin-bottom:auto;" class="nav-link dropdown-toggle" href="profile.php" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <img  src="'.$_SESSION['imgstatus'].'" style="width:40px; height:40px;"/>     Your Profile</a>  
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="profile.php">My Account</a>
          <a class="dropdown-item" href="editprofile.php">Edit Profile</a>
          <a class="dropdown-item" href="editprofile.php">Selling Products</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="changeaccount.php">Change Account</a>
        </div></li>
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
            <div class="col-md">
            
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
                  <div class="row ">
                  <div class="col-md-6">
                    <div class="image">
                    <img id="output" class="img-thumbnail img img-responsive full-width"  src="<?php echo($_SESSION['imgstatus']);?>" style="  vertical-align:middle" /> <br><br>
                    </div>

                    <label class="custom-file-upload ">
                                <input class="center" type="file" accept="image/*" id="img" name="img" onchange="loadFile(event)" hidden>
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-cloud-arrow-up" viewBox="0 0 16 16">
                                  <path fill-rule="evenodd" d="M7.646 5.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708l2-2z"/>
                                  <path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z"/>
                                </svg> Choose Picture
                            </label>
                    </div>
                    <div class="col-md-6">
                     
                    <label for="UserName">User Name</label>
                    <input type="text" class="form-control" id="UserName" name="UserName" placeholder="User Name" required pattern=".{4,20}"  title="4 to 20 characters"value="<?php
                      if (isset($_SESSION['username']))
                      {
                        echo $_SESSION['username'];
                      }
                    ?>">
                    <hr>

                    <label for="FirstName">First Name</label>
                    <input type="text" class="form-control" id="FirstName" name="FirstName" placeholder="First Name" required value="<?php
                      if (isset($_SESSION['firstname']))
                      {
                        echo $_SESSION['firstname'];
                      }
                    ?>">     
                    <hr>

                    <label for="LastName">Last Name</label>
                    <input type="text" class="form-control" id="LastName" name="LastName" placeholder="Last Name" required pattern="[A-BD-Za-z0-9()._-‘]+" value="<?php
                      if (isset($_SESSION['lastname']))
                      {
                        echo $_SESSION['lastname'];
                      }
                    ?>">
                    <br> 

                    <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>   
                <section class="mt-4 px-lg-3">
                            
              <a href="profile.php" class="btn btn-outline-secondary btn-lg btn-block text-wrap">
                <i class="fas fa-eye" aria-hidden="true"></i> View my profile
              </a>

            </section>   
            </div>
            <br><br>
            <hr data-content="Your address" class="hr-text"> 
            <div class="container" style="border-style: solid; border-width: 1px; padding:20px; border-color: DarkGray;">  
                
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
                    <form id="coordform" method="get" action="localization.php" >     
                <!---<hr style="height:4px;border-width:0;color:gray;background-color:gray">  -->
                      <div id='map'></div>
                      <input id="lat" name="lat" type="hidden" value="<?php if(isset($_SESSION['lat'])) echo $_SESSION['lat'];  else echo"51.327"; ?>">
                      <input id="lon" name="lon" type="hidden" value="<?php if(isset($_SESSION['lng'])) echo $_SESSION['lng'];  else echo"19.067"; ?>">
                      <br>
                      <button type="submit" name="submitcoord" class="btn btn-primary">Save your localization</button>
                    <form>
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
  <?php if(isset($_SESSION['maperror'])){ echo('<script>alert("'.$_SESSION['maperror'].'");</script>'); unset($_SESSION['maperror']);}?>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>

  <script>

  var loadFile = function(event) {
    var output = document.getElementById('output');
    if(event.target.files[0].size > 10485760){
       alert("File is too big!");
       event.target.value = "";
    }
    else {
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
        //URL.revokeObjectURL(output.src) // free memory
        }
    }
  };
</script>
<script>
      function initMap() {
        const map = new google.maps.Map(document.getElementById("map"), {
          zoom: <?php if(isset($_SESSION['lat'])) echo "10";  else echo"6"; ?>,
          center: { lat: <?php if(isset($_SESSION['lat'])) echo $_SESSION['lat'];  else echo"51.327"; ?>, lng: <?php if(isset($_SESSION['lng'])) echo $_SESSION['lng'];  else echo"19.067"; ?> },
        });
        marker = new google.maps.Marker({
          map,
          draggable: true,
          animation: google.maps.Animation.DROP,
          position: { lat: <?php if(isset($_SESSION['lat'])) echo $_SESSION['lat'];  else echo"51.327"; ?>, lng: <?php if(isset($_SESSION['lng'])) echo $_SESSION['lng'];  else echo"19.067"; ?> },
        });
        marker.addListener("click", toggleBounce);
      }
        
      function toggleBounce() {
        var lat = marker.getPosition().lat();
        var lng = marker.getPosition().lng();

        document.getElementById("lat").value=lat;
        document.getElementById("lon").value=lng;
        if (marker.getAnimation() !== null) {
          marker.setAnimation(null);
        } else {
          marker.setAnimation(google.maps.Animation.BOUNCE);
        }
      }

      google.maps.event.addListener(marker, 'dragend', function (event) {
    document.getElementById("lat").value = this.getPosition().lat();
 
});

    </script>
      <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBvMgbRpn3ebemcufEZEVIjTyeJZAWn6WY&callback=initMap&libraries=&v=weekly"
      defer>
    </script>
</body>
</html>