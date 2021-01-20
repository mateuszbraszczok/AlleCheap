<?php 
session_start();
  if (!isset($_SESSION['login']))
  {
    header("location: login");
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
    </style>

</head>

<body class="d-flex flex-column min-vh-100">

<?php require_once('header.php'); ?>

  <main>
    <br>
    <div class="container-fluid" >   
        <div class="row " style="border-style: solid; border-width: 1px; padding:15px; margin:1px; border-radius: 5px;">
            <div class="col-md">
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
                        $sql = "SELECT auctions.* ,auctionimg.Directory, users.username FROM auctions INNER JOIN auctionimg ON auctions.ID=auctionimg.auctionID LEFT JOIN users ON users.ID=auctions.WinnerID WHERE EndDate > now() AND SellerID ='". $_SESSION['id']."'";
                        //echo $sql;     
                        $result=$conn->query($sql);
                        if (!$result) throw new Exception($conn->error);
                        echo '<div style="overflow-x:auto;"><table class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                <th scope="col">Picture</th>
                                <th scope="col">Title</th>
                                <th scope="col">Current Winner</th>
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
                            if(isset($row['username']))
                             // echo "<td>"  .$row['username'].  "</td>";
                              echo "<td><a href='user?id=". $row['WinnerID'] ."'>" .$row['username']. "</a></td>";
                              
                            else
                              echo "<td>.......</td>";
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
        <br>
        <div class="row " style="border-style: solid; border-width: 1px; padding:15px; margin:1px; border-radius: 5px;">
            <div class="col-md">
                <h1>Sold Items</h1>
                <?php
                    try 
                    {    
                        $sql = "SELECT auctions.* ,auctionimg.Directory, users.username FROM auctions INNER JOIN auctionimg ON auctions.ID=auctionimg.auctionID INNER JOIN users ON users.ID=auctions.WinnerID WHERE EndDate < now() AND WinnerID <>'0' AND SellerID ='". $_SESSION['id']."'";      
                        $result=$conn->query($sql);
                        if (!$result) throw new Exception($conn->error);
                        echo '<div style="overflow-x:auto;"><table class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                <th scope="col">Picture</th>
                                <th scope="col">Title</th>
                                <th scope="col">Winner</th>
                                <th scope="col">End Time</th>
                                <th scope="col">Price [PLN]</th>
                                </tr>
                            </thead>
                            <tbody>';

                            while($row = mysqli_fetch_array($result))
                            {
                                echo "<tr  onclick='window.location'=login>";
                                echo "<td scope='row'><a href='sold?id=".$row['ID']."'><img src='" . $row['Directory'] . "' width=120></a></td>";
                                echo "<td><a href='sold?id=".$row['ID']."'>" . $row['Title'] . "</a></td>";
                                if(isset($row['username']))
                                 // echo "<td>"  .$row['username'].  "</td>";
                                  echo "<td><a href='user?id=". $row['WinnerID'] ."'>" .$row['username']. "</a></td>";
                                else
                                  echo "<td>.......</td>";
                                echo "<td style='white-space:nowrap;'>" . $row['EndDate'] . "</td>";
                                echo "<td>" . $row['Price'] . "</td>";
                                echo "</a></tr>";
                            }
                            echo "</tbody></table> </div>";                                         
                          }	           
                    catch(Exception $e)
                    {
                      echo '<span style="color:red;">Server error! Please visit us later!</span>';
                      echo '<br />Info for devs: '.$e;
                    }       
                ?>
            </div>       
        </div>
        <br>
        <div class="row " style="border-style: solid; border-width: 1px; padding:15px; margin:1px; border-radius: 5px;">
            <div class="col-md">
                <h1>Unsold Items</h1>
                <?php
                    try 
                    {        
                        $sql = "SELECT auctions.* ,auctionimg.Directory FROM auctions INNER JOIN auctionimg ON auctions.ID=auctionimg.auctionID WHERE EndDate < now() AND WinnerID ='0' AND SellerID ='". $_SESSION['id']."'";     
                        $result=$conn->query($sql);
                        if (!$result) throw new Exception($conn->error);
                        echo '<div style="overflow-x:auto;"><table class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                <th scope="col">Picture</th>
                                <th scope="col">Title</th>
                                <th scope="col">End Time</th>
                                <th scope="col">Price [PLN]</th>
                                </tr>
                            </thead>
                            <tbody>';

                        while($row = mysqli_fetch_array($result))
                        {
                            echo "<tr  onclick='window.location'=login>";
                            echo "<td scope='row'><a href='login'><img src='" . $row['Directory'] . "' width=120></a></td>";
                            echo "<td><a href='login'>" . $row['Title'] . "</a></td>";
                            echo "<td style='white-space:nowrap;'>" . $row['EndDate'] . "</td>";
                            echo "<td>" . $row['Price'] . "</td>";
                            echo "</a></tr>";
                        }
                        echo "</tbody></table> </div>";               
                      	
                    }
                    catch(Exception $e)
                    {
                      echo '<span style="color:red;">Server error! Please visit us later!</span>';
                      echo '<br />Info for devs: '.$e;
                    }
                    $conn->close();
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