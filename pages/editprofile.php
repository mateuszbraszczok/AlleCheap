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
            else $_SESSION['imgstatus']=$fileDestination;   
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
  
  function img_resize($target, $newcopy, $w, $h, $ext) {
    list($w_orig, $h_orig) = getimagesize($target);
   /* $scale_ratio = $w_orig / $h_orig;
    if (($w / $h) > $scale_ratio) {
           $w = $h * $scale_ratio;
    } else {
           $h = $w / $scale_ratio;
    }*/
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
  <script src='https://kit.fontawesome.com/a076d05399.js'></script>
  <link rel="stylesheet" href="styles.css" type="text/css"/>  
  <link rel="icon" type="image/png" sizes="16x16" href="favicon.png">
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
            <div class="col-md">
            
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
                  <div class="row ">
                  <div class="col-md-7">
                    <div class="image">
                    <img id="output" class="img-thumbnail img img-responsive full-width"  src="<?php echo($_SESSION['imgstatus']);?>" style="  vertical-align:middle" /> <br><br>
                    </div>
                    <br>
                    <label for="img">Change profile picture:</label><br>
                    <input type="file" accept="image/*" id="img" name="img" onchange="loadFile(event)">
                    </div>
                    <div class="col-md-5">
                     
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

</body>
</html>