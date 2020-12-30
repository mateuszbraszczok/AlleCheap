<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-Ua-Compatible" content="IE=edge">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
  <script src='https://kit.fontawesome.com/a076d05399.js'></script>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <link rel="stylesheet" href="styles.css" type="text/css"/>  
  <link rel="icon" type="image/png" sizes="16x16" href="../favicon.png">
  <title>AlleCheap</title>
</head>

<body>

  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="../index.php"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-basket" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1v4.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 13.5V9a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h1.217L5.07 1.243a.5.5 0 0 1 .686-.172zM2 9v4.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V9H2zM1 7v1h14V7H1zm3 3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 4 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 6 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5z"/>
      </svg> AlleCheap</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item active">
            <a class="nav-link" href="../index.php">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Auctions</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Pricing</a>
          </li>

        </ul>
        <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="login.php"><button type="button" class="btn btn-primary">Login</button></a>
        </li>
        <li class="nav-item">
          <a class="nav-link"><button type="button" class="btn btn-success">Sign In</button></a>
        </li>
      </ul>
      </div>
    </nav>
  </header>


  <main>
    <br>
    <div class="container">
    <div class="row ">
        <div class="col-sm-1 col-lg-2"></div>      
        <div class="col-sm-8 col-lg-6" >
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
          <fieldset style=" padding:20px; border: 1px solid lightgray;">
          <form>
          <div class="form-row">
              <div class="form-group col-md-6">
              <label for="FirstName">First Name</label>
              <input type="text" class="form-control" id="FirstName" placeholder="First Name" required >
              </div>        
              <div class="form-group col-md-6">
              <label for="LastName">Last Name</label>
              <input type="text" class="form-control" id="LastName" placeholder="Last Name" required pattern="[A-BD-Za-z0-9()._-‘]+">
              </div>
          </div>
          <div class="form-group ">
              <label for="inputUserName">Username (4 to 20 characters)</label>
              <input type="text" class="form-control" id="inputUserName" placeholder="Username" required pattern=".{4,20}"  title="4 to 20 characters">
          </div>
          <div class="form-group ">
              <label for="inputEmail">Your e-mail</label>
              <input type="email" class="form-control" id="inputEmail" placeholder="Email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
              <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
          </div>
          <div class="form-row">
              <div class="form-group col-md-6">
                <label for="inputPassword">Password (8 to 20 characters)</label>
                <input type="password" class="form-control" id="inputPassword" placeholder="Password" required pattern="(?=^.{8,20}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$"   title="UpperCase, LowerCase, Number/SpecialChar and 8 to 20 Chars">
              </div>
              <div class="form-group col-md-6">
              <label for="confirmPassword" id="labpass">Confirm Password</label>
              <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm Password" required pattern="(?=^.{8,20}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$"  title="UpperCase, LowerCase, Number/SpecialChar and 8 to 20 Chars">
              </div>
          </div>

          <div class="form-group">
              <div class="form-check">
              <input class="form-check-input" type="checkbox" id="gridCheck" required>
              <label class="form-check-label" for="gridCheck">
                  I am over 18 years old
              </label>
              </div>
          </div>
          <div class="g-recaptcha" data-sitekey="6LeHYRoaAAAAAHP8fKuA3s448k98-uyxNyiDKehU"></div>
          <br>
          <button type="submit" class="btn btn-success" id="submbutt">Sign in</button>
          </form>
          </fieldset>
        </form>
        </div>
        <div class="col-sm-3 col-lg-2"></div>
    </div>
    </div>
    <br>
  </main>



  <footer class="bg-light text-center text-lg-start">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2)">
      © 2021 Copyright:
      <span class="text-dark">Braszczok & Wojciechowski</span>
    </div>
  </footer>


  <script>
    const email = document.getElementById("inputEmail");
    const pass1 = document.getElementById("confirmPassword");
    const pass2 = document.getElementById("inputPassword");

    email.addEventListener("input", function (event) {
      if (email.validity.typeMismatch) {
        email.setCustomValidity("I am expecting an valid e-mail address");
        
      } else {
        email.setCustomValidity("");
        
      }
    });
    pass1.addEventListener("input", function (event) {
      if (pass1.value == pass2.value) {
        pass1.setCustomValidity("");
        document.getElementById("labpass").style.color = "black";
        
        
      } else {
        pass1.setCustomValidity("Passwords vary");   
        document.getElementById("labpass").style.color = "red";     
      }
    });
</script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    
</body>
</html>