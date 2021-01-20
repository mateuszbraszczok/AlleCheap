<?php 
session_start();

  if (!isset($_SESSION['login'])) 
  {
    $_SESSION['from']="sellform";
    header("location: login");
  }
  
  $Title = $Description = $Category = $Price =$Days = "";
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $ProperData = true;
    $Title = test_input($_POST["Title"]);
    $Description = test_input($_POST["Description"]);
   // echo('<p style="white-space:pre-wrap;">'.  $_POST["Description"].' </p>');
    
   // exit();
    $Category = test_input($_POST["Category"]);
    $Price = test_input($_POST["price"]);
    $Days = test_input($_POST["duration"]);

    //$sql = "INSERT INTO auctions(ID, SellerID, Title, rg, Category, Price, EndDate) VALUES (NULL, '$Title', '$Title', '$Description', '$Category', '$Price', now() + INTERVAL ".$Days." DAY)";    
    //echo($sql);
    

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
          $id = $_SESSION['id'];
          $sql = "INSERT INTO auctions(ID, SellerID, Title, Descript, Category, Price, EndDate) VALUES (NULL, '$id', '$Title', '$Description', '$Category', '$Price', now() + INTERVAL ".$Days." DAY)";    
          //echo $sql;
          if (!$conn->query($sql)) throw new Exception($conn->error);
          else $latest_id = $conn->insert_id;        
          $conn->close();
        }	
      }
      catch(Exception $e)
      {
        echo '<span style="color:red;">Server error! Please visit us later!</span>';
        echo '<br />Info for devs: '.$e;
      }
    

    if(isset($_FILES['productimg']))
    {
    $file = $_FILES['productimg'];
    $fileName = $_FILES['productimg']['name'];
    $fileTmpName = $_FILES['productimg']['tmp_name'];
    $fileSize = $_FILES['productimg']['size'];
    $fileError = $_FILES['productimg']['error'];
    $fileType = $_FILES['productimg']['type'];

    $fileExt = explode('.',$fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg','jpeg','png');

    if(in_array($fileActualExt,$allowed)) {
      if($fileActualExt =='jpeg')
        $fileActualExt='jpg';
        if ($fileError === 0){
          $fileNameNew = "auction".$latest_id.".".$fileActualExt;
          $fileDestination = 'products_pictures/'.$fileNameNew ;
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
              $sql = "INSERT INTO auctionimg VALUES (NULL, '$latest_id', '$fileDestination')";      
              if (!$conn->query($sql)) throw new Exception($conn->error);
              
                
              $wmax = 640;
              $hmax = 480; 
              img_resize($fileDestination, $fileDestination, $wmax, $hmax, $fileActualExt);    
              $conn->close();
              //echo('<script>alert("Dodano przedmiot");</script>');
              header("location: sellingdashboard");

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
  <meta http-equiv="Cache-Control" content="no-store" />
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
      width: 640px;
      height: 480px;
    }

    @media screen and (max-width: 992px) {
      #output {
        width: 400px;
        height: 300px;
      }
    }

    @media screen and (max-width: 600px) {
      #output {
        width: 320px;
        height: 240px;
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
    .center {
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 50%;
    }
    input[type="file"] {
     display: none;
    }
    .custom-file-upload {
        border: 1px solid #ccc;
        text-align:center;
        padding: 6px 12px;
        cursor: pointer;
    }
    
</style>

</head>

<body class="d-flex flex-column min-vh-100">

  <?php require_once('header.php'); ?>


  <main>
    <br>
    <div class="container" >   
        <div class="row " style="border-style: solid; border-width: 1px; padding:15px; margin:1px; border-radius: 5px;">
            <div class="col-md">
                <?php $PHP_SELF = htmlspecialchars($_SERVER['PHP_SELF']); ?>
                <form method="post" action="<?php echo basename($PHP_SELF, '.php');?>" enctype="multipart/form-data">
                    <div class="row justify-content-center">
                        <div class="col align-self-center">
                            <div class="image" >
                                <img id="output" class="img-thumbnail img img-responsive full-width center"  src="products_pictures/default_picture.png" alt="photo" style="  vertical-align:middle" /> <br><br>
                            </div>
                            <label class="custom-file-upload ">
                                <input  type="file" accept="image/*" id="productimg" name="productimg" onchange="loadFile(event)" hidden required>
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-cloud-arrow-up" viewBox="0 0 16 16">
                                  <path fill-rule="evenodd" d="M7.646 5.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708l2-2z"/>
                                  <path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z"/>
                                </svg> Choose Picture
                            </label>
  
                        </div>
                    </div>
                    <hr>
                    <div class="row ">
                        <div class="col-md-12">
                        
                            <label for="Title">Title</label>
                            <input type="text" class="form-control" id="Title" name="Title" placeholder="Title" required pattern=".{4,100}" size="100" title="4 to 100 characters">
                            <hr>

                            <label for="Description">Description</label>
                            <textarea class="form-control" id="Description" name="Description" rows="6" placeholder="Description" required pattern=".{10,1000}" title="10 to 1000 characters"></textarea>
                            <hr>
                            <div class="form-group">
                              <label for="Category">Category of product</label>
                              <select class="form-control col-md-2" id="Category" name="Category" required>
                                <option value="Clothes">Clothes</option>
                                <option value="Electronics">Electronics</option>
                                <option value="Books">Books</option>
                                <option value="Sport">Sport</option>
                                <option value="Other">Other</option>
                              </select>
                            </div>
                            <hr> 
                            <div class="form-group ">
                              <label for="price" >Starting price [PLN]</label>
                              <div >
                                <input class="form-control col-md-2" type="number" placeholder="0.00" data-decimals="2" max="999999" id="price" name="price" step=".01" min="0" required pattern="^\d+(?:\.\d{1,2})?$" onkeypress="return isNumeric(event)" > 
                              </div>
                            </div>
                            <hr> 
                            <div class="form-group">
                              <label for="duration">Duration of the auction in days</label>
                              <select class="form-control col-md-1" id="duration" name="duration" required>
                                <option value="3">3</option>
                                <option value="5">5</option>
                                <option value="7">7</option>
                                <option value="10">10</option>
                                <option value="14" selected>14</option>
                              </select>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">Add the product</button>
                        </div>
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
    
  function isNumeric (evt) {
    var theEvent = evt || window.event;
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode (key);
    var regex = /[0-9]|\./;
    if ( !regex.test(key) ) {
      theEvent.returnValue = false;
      if(theEvent.preventDefault) theEvent.preventDefault();
    }
  }
</script>

</body>
</html>