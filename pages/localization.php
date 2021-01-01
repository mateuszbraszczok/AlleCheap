<?php
    session_start();
    if (!isset($_SESSION['login']))
    {
        header("location: ../index.php");
    }
    if(!isset($_GET['lat']) || !isset($_GET['lon']) )
    {
        header("location: ../index.php");
    }

    $lat= $_GET['lat'];
    $lng= $_GET['lon'];


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
        //is it email exist in db?
        $sql = $conn->query("SELECT Latitude FROM userlocalization WHERE userID='".$_SESSION['id']."'");
          
        if (!$sql) throw new Exception($conn->error);
          
        $how_many = $sql->num_rows;
        if($how_many>0)
        {
            $sql = $conn->query("UPDATE userlocalization SET Latitude='".$lat."',Longitude='".$lng."'  WHERE userID='".$_SESSION['id']."' ");
            if (!$sql) throw new Exception($conn->error); 
          $ProperData=false;
          $_SESSION['error_mail']="Account with this e-mail already exists!";
        }
        else
        {
            if ($conn->query("INSERT INTO userlocalization VALUES (NULL, '".$_SESSION['id']."', '".$lat."', '".$lng."')"))
            {
            
            }
          else
          {
            throw new Exception($conn->error);
          }   
        }		
        $_SESSION['lat'] = $lat;
        $_SESSION['lng'] = $lng;
          
        header('Location: editprofile.php');
		$conn->close();
				
      }
    }
	catch(Exception $e)
	{
		echo '<span style="color:red;">Server error! Please visit us later!</span>';
		echo '<br />Info for devs: '.$e;
	}
  

?>




