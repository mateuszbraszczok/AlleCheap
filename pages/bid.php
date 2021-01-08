<?php 
session_start();
  if (!isset($_SESSION['login']))
  {
    header("location: login");
  }
  if ( !isset($_POST['submit']))
  {
    header("location: ../");
  }

   $newPrice = $_POST['price'];
   $auctionID = $_POST['id'];
   require_once "dbconnect.php";

    try 
    {
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_errno!=0)
        {
        throw new Exception(mysqli_connect_errno());
        }
             
        $sql = "SELECT * FROM auctions WHERE EndDate > now() AND ID ='". $auctionID."'";      
        $result=$conn->query($sql);
        if (!$result) throw new Exception($conn->error);

                
        $row = mysqli_fetch_array($result);
        $currentPrice = $row['Price']; 
        if ($currentPrice < $newPrice)
        {
            $sql = "UPDATE auctions SET Price='".$newPrice."', WinnerID ='".$_SESSION['id']."' WHERE ID=".$auctionID."";
            $result=$conn->query($sql);
            if (!$result) throw new Exception($conn->error);
            $sql = "INSERT INTO bidding (auctionID, bidprice,buyerID) VALUES ('".$auctionID."', '".$newPrice."', '".$_SESSION['id']."')";
            $result=$conn->query($sql);
            if (!$result) throw new Exception($conn->error);
            header("location: auction?id=".$auctionID);
        }
        else
        header("location: 'auction?id=".$auctionID."'");
        $conn->close();
    }
    catch(Exception $e)
    {
        echo '<span style="color:red;">Server error! Please visit us later!</span>';
        echo '<br />Info for devs: '.$e;
    }  
 ?> 