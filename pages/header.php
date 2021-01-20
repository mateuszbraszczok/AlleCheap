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