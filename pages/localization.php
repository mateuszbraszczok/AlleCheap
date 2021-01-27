<?php
    session_start();
    if (!isset($_SESSION['login']))
    {
        header("location: ../");
    }
    if(!isset($_GET['lat']) || !isset($_GET['lon']) )
    {
        header("location: ../");
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
        $addres = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$lng.'&key=AIzaSyBvMgbRpn3ebemcufEZEVIjTyeJZAWn6WY');
        $json_result = json_decode($addres);
       //var_dump($json_result->results[0]->address_components[5]);
        //exit();
        if($json_result->results[0]->address_components[0]->types[0] !="street_number")
        {
          $_SESSION['maperror']="nie ma takiego adresu";
          header('Location: editprofile');
          die();
        }
        $street_number = $json_result->results[0]->address_components[0]->long_name;
        
        $route = $json_result->results[0]->address_components[1]->long_name;
        $route = mb_convert_encoding($route, "UTF-8");
        $i =2;
        while (($json_result->results[0]->address_components[$i]->types[0] !="locality") && ($json_result->results[0]->address_components[$i]->types[0] !="administrative_area_level_2"))  
        {
          $i++; 
          if($i>5)
          {
            $_SESSION['maperror']="nie ma takiego adresu";
            header('Location: editprofile'); 
            die();
         }
        }
        if($json_result->results[0]->address_components[$i+1]->types[0] =="administrative_area_level_2") $i++;
        $city = $json_result->results[0]->address_components[$i]->long_name;
        $state = $json_result->results[0]->address_components[$i+1]->long_name;
        $country = $json_result->results[0]->address_components[$i+2]->long_name;
        
  
       
        //echo ("<br>".$_SESSION['street_number']."<br>".$_SESSION['route']."<br>".$_SESSION['city']."<br>".$_SESSION['state']."<br>".$_SESSION['country']);
        //exit();

        //is it LOCATION FOR THIS USER exist in db?
        $sql = $conn->query("SELECT Latitude FROM userlocalization WHERE userID='".$_SESSION['id']."'");
          
        if (!$sql) throw new Exception($conn->error);
          
        $how_many = $sql->num_rows;
        if($how_many>0)
        {
            $sql = $conn->query("UPDATE userlocalization SET Latitude='".$lat."',Longitude='".$lng."',street_number='".$street_number."',street='".$route."',city='".$city."',region='".$state."',country='".$country."'  WHERE userID='".$_SESSION['id']."' ");
            if (!$sql) throw new Exception($conn->error); 
        }
        else
        {
            if ($conn->query("INSERT INTO userlocalization VALUES (NULL, '".$_SESSION['id']."', '".$lat."', '".$lng."', '".$street_number."', '".$route."', '".$city."', '".$state."', '".$country."')"))
            {
            
            }
            else
            {
              throw new Exception($conn->error);
            }   
        }		
        $_SESSION['lat'] = $lat;
        $_SESSION['lng'] = $lng;
        $_SESSION['street_number'] = $street_number;
        $_SESSION['route'] = $route;
        $_SESSION['city'] = $city;
        $_SESSION['state'] = $state;
        $_SESSION['country'] = $country;
        $conn->close();
        header('Location: editprofile');		
      }
    }
    catch(Exception $e)
    {
      echo '<span style="color:red;">Server error! Please visit us later!</span>';
      echo '<br />Info for devs: '.$e;
    }
  
?>
