<?php 
session_start();
  if (isset($_SESSION['login']))
  {
    if ($_SESSION['login'] != true)
      unset($_SESSION['login']);
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
</head>

<body  style="background-image: url(background.jpg); background-position: center center; background-repeat: no-repeat; background-attachment: fixed; background-size: cover;">
  <header> 
    <nav class="navbar navbar-expand-md navbar-light bg-light">
      <a style="margin-left:15px;" class="navbar-brand" href=""><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-basket" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1v4.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 13.5V9a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h1.217L5.07 1.243a.5.5 0 0 1 .686-.172zM2 9v4.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V9H2zM1 7v1h14V7H1zm3 3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 4 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 6 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5z"/>
      </svg> AlleCheap</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item active">
            <a class="nav-link" href="">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/auction_list">Auctions</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/sellform">Sell product</a>
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
        echo('<li class="nav-item dropdown"><a style="margin-right:50px; margin-top:auto; margin-bottom:auto;" class="nav-link dropdown-toggle" href="pages/profile" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <img  src="pages/'.$_SESSION['imgstatus'].'" style="width:40px; height:40px;"/>     Your Profile</a>  
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="pages/profile">My Account</a>
          <a class="dropdown-item" href="pages/editprofile">Edit Profile</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="pages/sellingdashboard">Selling Products</a>
          <a class="dropdown-item" href="pages/buyingdashboard">Buying Products</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="pages/changeaccount">Change Account</a>
        </div></li>
        <li class="nav-item">
          <a class="nav-link" href="pages/logout"><button type="button" class="btn btn-danger">Logout</button></a>
        </li>');?>
      </ul>
      </div>
    </nav>
  </header>
  
  <main>
    <h1 style="text-align: center;">Welcome on our auction webpage</h1>
    <div style="text-align: center;"> <a class="btn btn-secondary" href="pages/sellform" role="button">Sell a product</a></div>
    <br>
    <div style="text-align: center;"> <a class="btn btn-secondary" href="pages/auction_list" role="button">Buy a product</a></div>
    <div class="wrapper flex-grow-1"></div>
  </main>

  <footer class="bg-light text-center text-lg-start " style="position: absolute; bottom: 0; width: 100%;" >
    <div class=" p-3" style="background-color: rgba(0, 0, 0, 0.5)">
      <div class="container-fluid">   
        <div class="row ">
          <div class="col-sm-4 col-lg-4"></div>
          <div class="col-sm-4 col-lg-4" >© 2021 Copyright:<span class="text-dark">Braszczok & Wojciechowski</span></div>
          <div class="col-sm-4 col-lg-4 " style="text-align: center;" >Image by <a href="https://pixabay.com/users/mediamodifier-1567646/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=2140604">Mediamodifier</a> from <a href="https://pixabay.com/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=2140604">Pixabay</a>
        </div>
      </div>
    </div>
  </footer>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    
</body>
</html>