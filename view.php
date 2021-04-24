<!DOCTYPE html>
<html>
<head>
<title>Twitter Timeline challenge</title>
<link rel="icon" type="image/png" href="images/twitter.png"/>

<link rel="stylesheet" href="css/myUI.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" integrity="sha384-O8whS3fhG2OnA5Kas0Y9l3cfpmYjapjI0E4theH4iuMD+pLhbf6JI0jIMfYcK3yZ" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="js/myScript.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="js/jquery-3.2.1.js"></script>
<script src="js/jquery.bxslider.js"></script>
<script src="js/jquery-1.10.2.js"></script>
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="css/full-slider.css" rel="stylesheet">
<link rel="stylesheet" href="css/myView.css">
</head>
<body onload="myFunction()">

  <!-- loader -->
  <div id="loader"></div>

  <!-- header -->
  <div class="topnav" id="tn">
    <a class="active" href="#home"><i class="fab fa-twitter"></i></a>
    <a href="#" id="download" class="download">Download Tweet&nbsp<i class="fas fa-file-download"></i></a>
    <a href="controller.php?logout=true" class="logout">LogOut &nbsp<i class="fas fa-sign-out-alt"></i></a>
    <div class="search-container" style="float:left;">
      <input type="text" class="search_follower search-box" placeholder="Download Follower" name="key" id="search-box" style="display: inline-block;" onfocusout="myFocusOut()"/>
      <button type="submit" id="downloadFollower" name="search_public_user" class="download" style="display: inline-block;"><i class="fas fa-file-download"></i></button> 
      <div class="lds-dual-ring" id="ring"></div>
    </div>    
  </div>

  <!-- main content -->
  <div class="row" id="con">
    <div class="width20">
      <div class="user_detail">
        <div class="user_image">
          <img id="user_pic" src="" style="border-radius: 45%" />
        </div>
        <div class="user_name">
          <a id="name_user"></a>
        </div>
      </div>
      <div></div>
      <div class="width100">
				<form>
					<input type="text" class="search_follower" placeholder="Search your follower" style="width:100%;" id="searchbox" name="followers_search" autocomplete="off" />
          <div></div>
      	</form>
				</div>
				<div class="width100" id="search"></div>
					<div class="width100">
						<div id="hr_line"></div>
						<br />
					</div>
				<div id="followers"></div>
    </div>

    <!-- carousel slider plugin -->
   <div class="width75">
      <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner" role="listbox" style="background-color:grey;">
          <div class="carousel-item " >
            <div><center>Wait for few minute</center></div>
          </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
          <i class="carousel-control-prev-icon" style="color:black;"></i>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
          <i class="carousel-control-next-icon" style="color:black;"></i>
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

 
 

<script>
    // open the download tweet modal 
    document.getElementById("download").onclick = function() {
        document.getElementById("myModal").style.display = "block";
    }
    // close the download tweet modal
    document.getElementsByClassName("closeT")[0].onclick = function() {
        document.getElementById("myModal").style.display = "none";
    }
    // open the download tweet modal 
    document.getElementById("downloadFollower").onclick = function() {
      document.getElementById("myText").value = document.getElementById("search-box").value;
        document.getElementById("myModalFollower").style.display = "block";
    }
    // close the download tweet modal
    document.getElementsByClassName("closeD")[0].onclick = function() {
        document.getElementById("myModalFollower").style.display = "none";
    }
    window.onclick = function(event) {
        if (event.target == document.getElementById("myModal")) {
            document.getElementById("myModal").style.display = "none";
        }
    }
</script>
 
<script src="vendor/jquery/jquery.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- css loader script -->

<script>
var myVar;

function myFunction() {
    myVar = setTimeout(showPage, 3000);
}

function myFocusOut() {
  document.getElementById("ring").style.display = "none";
}

function showPage() {
  document.getElementById("loader").style.display = "none";
  document.getElementById("con").style.display = "block";
  document.getElementById("tn").style.display = "block";
}
</script>
</body>
</html>
