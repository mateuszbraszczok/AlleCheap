<?php 
session_start();
  if (!isset($_SESSION['login']))
  {
    $_SESSION['from']="auction_list";
    header("location: login");
  }
  if (isset($_POST['Clothes']))
    $Clothes=true;
  if (isset($_POST['Electronics']))
    $Electronics=true;
  if (isset($_POST['Books']))
    $Books=true;
  if (isset($_POST['Sport']))
    $Sport=true;
  if (isset($_POST['Other']))
    $Other=true;                
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
    </style>

</head>

<body class="d-flex flex-column min-vh-100">

<?php require_once('header.php'); ?>


  <main>
    <br>
    <div class="container-fluid" >   
      <div class="row " style="border-style: solid; border-width: 1px;  padding:15px; margin:1px; border-radius: 5px;" >
        <div class="col-md-2" style="border-style: solid; border-width: 1px; border-color: lightgray; border-radius: 5px;">
        <h4>Choose Category:</h4>
        <?php $PHP_SELF = htmlspecialchars($_SERVER['PHP_SELF']); ?>
        <form method="post" action="<?php echo basename($PHP_SELF, '.php');?>">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="Clothes" name="Clothes" id="Check1" <?php if( isset($Clothes)) echo "checked"; ?>>
            <label class="form-check-label" for="Check1">
            Clothes
            </label>
          </div><div class="form-check">
            <input class="form-check-input" type="checkbox" value="Electronics" name="Electronics" id="Check2"<?php if( isset($Electronics)) echo "checked"; ?>>
            <label class="form-check-label" for="Check2">
            Electronics
            </label>
          </div><div class="form-check">
            <input class="form-check-input" type="checkbox" value="Books" name="Books" id="Check3"<?php if( isset($Books)) echo "checked"; ?>>
            <label class="form-check-label" for="Check3">
            Books
            </label>
          </div><div class="form-check">
            <input class="form-check-input" type="checkbox" value="Sport" name="Sport" id="Check4"<?php if( isset($Sport)) echo "checked"; ?>>
            <label class="form-check-label" for="Check4">
            Sport 
            </label>
          </div><div class="form-check">
            <input class="form-check-input" type="checkbox" value="Other" name="Other" id="Check5"<?php if( isset($Other)) echo "checked"; ?>>
            <label class="form-check-label" for="Check5">
            Other
            </label>
            <br>
            <button type="submit" class="btn btn-secondary">Filter</button>
          </form>
        </div>
        </div>
        <div class="col-md-10">
        
          <h1>Selling Items</h1>
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

                $sql = "SELECT auctions.*, auctionimg.Directory, users.username FROM auctions INNER JOIN auctionimg ON auctions.ID=auctionimg.auctionID INNER JOIN users ON users.ID=auctions.SellerID
                WHERE EndDate > now() AND SellerID <>'". $_SESSION['id']."'";
                
                if (isset($Clothes) || isset($Electronics) || isset($Books) || isset($Sport) || isset($Other) )
                {
                  $sql.= "AND Category in(";
                  if (isset($Clothes))
                    $sql.= " 'Clothes',";
                  if (isset($Electronics))
                    $sql.= " 'Electronics',";
                  if (isset($Books))
                    $sql.= " 'Books',";
                  if (isset($Sport))
                    $sql.= " 'Sport',";
                  if (isset($Other))
                    $sql.= " 'Other',";
                  $sql = substr($sql, 0, -1);
                  $sql.=")";
                }
                
                //echo ($sql);
                        
                $result=$conn->query($sql);
                if (!$result) throw new Exception($conn->error);
                echo '<div style="overflow-x:auto;"><table class="table table-hover" style="width:100%">
                  <thead>
                    <tr>
                    <th scope="col">Picture</th>
                    <th scope="col">Title</th>
                    <th scope="col">Seller</th>
                    <th scope="col">End Time</th>
                    <th scope="col">Price [PLN]</th>
                    </tr>
                  </thead>
                  <tbody>';
                        
                while($row = mysqli_fetch_array($result))
                {
                  echo "<tr  onclick='window.location'=login>";
                  echo "<td scope='row'><a href='auction?id=".$row['ID']."'><img src='" . $row['Directory'] . "' width=120></a></td>";
                  echo "<td><a href='auction?id=".$row['ID']."'>" . $row['Title'] . "</a></td>";
                  echo "<td><a href='user?id=". $row['SellerID'] ."'>" .$row['username']. "</a></td>";
                  echo "<td style='white-space:nowrap;'>" . $row['EndDate'] . "</td>";
                  echo "<td>" . $row['Price'] . "</td>";
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