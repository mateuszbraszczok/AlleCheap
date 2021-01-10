<?php 
session_start();
  if (!isset($_SESSION['login']))
  {
    header("location: login");
  }
  if (!isset($_GET['id']) )
  {
    header("location: ../");
  }
  
 ?> 
 <?php
              require_once "dbconnect.php";
              $product_id = $_GET['id'];
              try 
              {
                $conn = new mysqli($servername, $username, $password, $dbname);
                if ($conn->connect_errno!=0)
                {
                  throw new Exception(mysqli_connect_errno());
                }
                else
                {      
                  $sql = "SELECT * FROM auctions WHERE ID= '$product_id'";      
                  $result=$conn->query($sql);
                  if (!$result) throw new Exception($conn->error);
                  $row = mysqli_fetch_array($result);
                  $date1 = new DateTime();
                  $date2 = DateTime::createFromFormat('Y-m-d H:i:s',$row['EndDate']);
                  if ( $date2 < $date1 )
                  {
                  // header_remove("location"); 

                  header("location: ../");
                  
                  }
                  $diff = $date1->diff($date2);
                  $sql2 = "SELECT * FROM auctionimg WHERE auctionID= '$product_id'";      
                  $result2=$conn->query($sql2);
                  if (!$result2) throw new Exception($conn->error);
                  $row2 = mysqli_fetch_array($result2);
                  //echo($row2['Directory']);

                }	
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
    * {
      box-sizing: border-box;
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
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="sellingdashboard">Selling Products</a>
          <a class="dropdown-item" href="buyingdashboard">Buying Products</a>
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
    <div class="container" style="border-style: solid; border-width: 1px; padding:30px; margin-bottom:50px; ">   
        <div class="row " >
          <div class="col-md-6">
            
                <img class="img-thumbnail img img-responsive " src="<?php echo($row2['Directory']);?>" alt="product_picture" style="width:100%;">
                  <br> 
                  <br>               
          </div>
            
          <div class="col-md-6">
              <div>
                <h7>Title</h7>
                <h4><?php echo($row['Title']);?></h4>
                <br><br><small>To End</small><br>
                <strong><?php echo($diff->format('%d days, %h hours %i min'));?> </strong>
                <br><br><small>Actual Price</small><br>
                <strong><?php echo($row['Price']);?> PLN</strong>
                <br><br>
              </div>
              <?php if ($_SESSION['id'] != $row['SellerID']) { ?>
              <form method="post" action="bid" enctype="multipart/form-data">
                <div class="form-group ">
                <label for="price" >Your Bid [PLN]</label>
                  <div>
                  <input type="hidden" id="id" name="id" value="<?php echo($_GET['id']);?>">
                  <input class="form-control col-md-2" type="number" value="<?php echo($row['Price']+0.50);?>" data-decimals="2" max="999999" id="price" name="price" step=".1" min="<?php echo($row['Price']+0.5);?>" required pattern="^\d+(?:\.\d{1,2})?$" onkeypress="return isNumeric(event)" > 
                  </div>
                </div>
                <button type="submit" name="submit" class="btn btn-outline-info">Make a Bid</button>

              </form>
              <?php } ?>
            </div>
            </div>
            <div class="row " style="margin-top:50px;">
              <div class="col-md-12">
                <h3>Description</h3><br>
                <p style="white-space:pre-wrap; background-color:#e6e6e6; padding :30px;"><?php echo($row['Descript']);?></p>
              </div>
            </div>
            <hr>
            <div class="row " style="margin-top:50px;">
              <div class="col-md-12">
                <div style="margin:auto;
                    vertical-align:middle;">
                    <h3>Bid history</h3>
                    <?php
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
                        $sql = "SELECT * FROM bidding WHERE auctionID ='". $_GET['id']."' ORDER BY time DESC";      
                        $result=$conn->query($sql);
                        if (!$result) throw new Exception($conn->error);
                        echo '<div style="overflow-x:auto;"><table class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                <th scope="col">DateTime</th>
                                <th scope="col">Bidder</th>
                                <th scope="col">Price [PLN]</th>
                                </tr>
                            </thead>
                            <tbody>';
                                
                        while($row = mysqli_fetch_array($result))
                        {
                            echo "<tr  onclick='window.location'=login>";
                            $sql = "SELECT username FROM users WHERE ID='". $row['buyerID']."'";   
                            //echo $sql   ;
                            $result2=$conn->query($sql);
                            if (!$result2) throw new Exception($conn->error);
                            $row2 = mysqli_fetch_array($result2);

                            echo "<td style='white-space:nowrap;'>" . $row['time'] . "</td>";
                            echo "<td>" . $row2['username'] . "</td>";
                            echo "<td>" . $row['bidprice'] . "</td>";
                            echo "</a></tr>";
                        }
                        echo "</tbody></table> </div>";                                         
                      }	
                    }
                    catch(Exception $e)
                    {
                      echo '<span style="color:red;">Server error! Please visit us later!</span>';
                      echo '<br />Info for devs: '.$e;
                    }               
                ?>               
                </div>
              </div>
            </div>
        </div>
    </div>

             
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