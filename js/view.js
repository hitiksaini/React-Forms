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

var myVar;

function myFunction() {
    myVar = setTimeout(showPage, 1000);
}

function myFocusOut() {
  document.getElementById("ring").style.display = "none";
}

function showPage() {
  document.getElementById("loader").style.display = "none";
  document.getElementById("con").style.display = "block";
  document.getElementById("tn").style.display = "block";
}
