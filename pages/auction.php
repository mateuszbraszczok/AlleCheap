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
      $sql = "SELECT auctions.* ,auctionimg.Directory FROM auctions INNER JOIN auctionimg ON auctions.ID=auctionimg.auctionID WHERE auctions.ID= '$product_id'";     
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

  <?php require_once('header.php'); ?>


  <main>
    <br>
    <div class="container" style="border-style: solid; border-width: 1px; padding:30px; margin-bottom:50px; border-radius: 5px; ">   
        <div class="row " >
          <div class="col-md-6">
            <img class="img-thumbnail img img-responsive " src="<?php echo($row['Directory']);?>" alt="product_picture" style="width:100%;">
            <br> 
            <br>               
          </div>
            
          <div class="col-md-6">
              <div>
                <h7>Title</h7>
                <h4><?php echo($row['Title']);?></h4>
                <small>To End</small><br>
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
              <?php echo "<br><h4><a href='user?id=". $row['SellerID'] ."'>Seller Profile</a><h4>"; ?>
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
                        $sql = "SELECT bidding.*, users.username FROM bidding INNER JOIN users ON bidding.buyerID = users.ID WHERE bidding.auctionID ='". $_GET['id']."' ORDER BY time DESC";      
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
                            echo "<td style='white-space:nowrap;'>" . $row['time'] . "</td>";
                            echo "<td><a href='user?id=". $row['buyerID'] ."'>" . $row['username'] . "</a></td>";
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