<!DOCTYPE html>
<html>
<head>
<title>Twitter Timeline challenge</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/png" href="images/twitter.png"/>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="css/view.css?v=<?php echo time(); ?>">

</head>

<body onload="myFunction()">
  <!-- loader -->
  <div id="loader"></div>

  <!-- header -->
<div class="col-md-2 p1">
  <div class="topnav" id="tn">
    <br>
    <a href="#" id="download" class="download">Download Tweet </a>
    <br>
    <a href="controller.php?logout=true" class="logout">LogOut <i class="fas fa-sign-out-alt"></i></a>
    <br>
    <div class="search-container">
      <input type="text" class="search_follower search-box" placeholder="Download Follower" name="key" id="search-box" onfocusout="myFocusOut()"/>
      <button type="submit" id="downloadFollower" name="search_public_user" class="download"><i class="fas fa-file-download"></i></button>
      <div class="lds-dual-ring" id="ring"></div>
    </div>
  </div>
  <div class="">
    <div class="user_detail">
      <div class="user_image">
        <img id="user_pic" src="" style="border-radius: 45%" />
      </div>
      <div class="user_name">
        <a id="name_user"></a>
      </div>
    </div>
    <div></div>
    <div class="">
      <form>
        <input type="text" class="search_follower" placeholder="Search your follower" id="searchbox" name="followers_search" autocomplete="off" />
        <div></div>
      </form>
      </div>
      <div class="" id="search"></div>
        <div class="">
          <div id="hr_line"></div>
          <br />
        </div>
      <div id="followers"></div>
  </div>
</div>



  <!-- main content -->
  <div class="row" id="con">


    <!-- carousel -->
   <div class="width75">
      <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner" role="listbox">
          <div class="active carousel-item " >
            <div><center>Loading . . . </center></div>
          </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
          <i class="carousel-control-prev-icon"></i>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
          <i class="carousel-control-next-icon"></i>
          <span class="sr-only">Next</span>
        </a>
      </div>
   </div>
</div>

  <!-- custom css for modalbox -->
  <!-- download tweet modalbox -->
  <div id="myModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <span class="closeT">&times;</span>
    <h3>Choose your format</h3><hr>
    <div class="dropdown">
      <button class="dropbtn">Select format</button>
      <div class="dropdown-content">
        <a role="menuitem" tabindex="-1" class="download"  href="./controller.php?download=true&format=google-spread-sheet">Google SpreadSheet</a>
        <a role="menuitem" tabindex="-1" class="download"  href="./controller.php?download=true&format=json">Json</a>
        <a role="menuitem" tabindex="-1" class="download"  href="./controller.php?download=true&format=csv">CSV</a>
        <!-- <a role="menuitem" tabindex="-1" class="download"  href="./controller.php?download=true&format=xls">XLS</a> -->
      </div>
    </div>
    </div>
  </div>


  <!-- download follower modalbox -->
  <div id="myModalFollower" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <span class="closeD">&times;</span>
    <h3 id="nameOfFollower">Download Followers</h3><hr>
    <form method="get" action="controller.php" class="form-group">
    <input class="form-control" type="text" id="myText" name="uName" value="" readonly>
    <br>
    <div >
      <select class="form-control" id="sel1" name="format" style="font-size: 1.5rem;">
        <option value="0">Select format</option>
        <option value="google-spread-sheet">Google Spreadsheet</option>
        <option value="xml">XML</option>
        <option value="pdf">PDF</option></option>
      </select>
    </div><br>
    <button type="submit" id="downloadFile" style="font-size: 1.5rem" class="btn btn-primary" name="downloadFile">Download File</button>
    </form>
  </div>
  </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bxslider/4.2.15/jquery.bxslider.js"></script>
<script src="https://kit.fontawesome.com/d64b5afa4a.js" crossorigin="anonymous"></script>

<script src="js/view.js"></script>
<script src="js/myScript.js"></script>

</body>
</html>
