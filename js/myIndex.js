var myVar;

// load css loader
function myFunction() {
    myVar = setTimeout(showPage, 1000);
}

// after loading display content
function showPage() {
  document.getElementById("loader").style.display = "none";
  document.getElementById("myDiv").style.display = "block";
}
