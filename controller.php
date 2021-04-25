<!-- Consider it as a Driver code for the entire project -->

<?php
    session_start();
    include('./model.php');
    $model = new Model();

    // login
    if( isset($_POST['login']) ) {
        $model->connection();
    }

    // redirect user back to index page
    if ( isset($_REQUEST['oauth_verifier'], $_REQUEST['oauth_token']) ) {
        $model->callback();
    }

    // get follower detail
    if(isset($_GET['followers']) ) {
        $id = $_GET['usr_id'];
        $model->getFollowerDetail($id);
    }

    // get detail of user
    if( isset($_GET['fetchFollowers']) ) {
        $screen_name = $_GET['fetchFollowers'];
        $model->getFollowers($screen_name);
    }

    // get detail of logged in user
    if( isset($_GET['userdata']) && $_GET['userdata']==true ) {
        $model->getLoggedInUserDetail();
    }

    // download tweet
    if($_GET['download']==true ) {
        $format=$_GET['format'];
        if($format == "csv") {
            $model->downloadCSV();
        } else if($format =="json") {
            $model->downloadJSON();
        } else if($format == "google-spread-sheet") {
            $_SESSION['downloadFormat'] = $model->uploadGoogleDrive();
            header('location:lib\google-drive-api\index.php');
        }
    }

     // download follower
     if(isset($_GET['uName']) ) {
        $format=$_GET['format'];
        $uName = $_GET['uName'];
        if($format == "xml") {
            $model->dowloadXML($uName);
        } else if($format =="pdf") {
            $model->downloadPDF($uName);
        } else if($format == "google-spread-sheet") {
            $_SESSION['downloadFormat'] = $model->uploadGoogleDriveFollower($uName);
            header('location:lib\google-drive-api\index.php');
        }
    }

    //  search public follower
    if( isset($_POST['search_public_user']) ) {
        $_SESSION['downloadUserName']=$_POST['search_public_user'];
    }


    // autosearch follower of logged in user
    if( isset($_GET['autosearch']) && $_GET['autosearch']==true ) {
        $model->searchFollower();
    }

    // logout
    if( isset($_GET['logout']) && $_GET['logout']==true ) {
        $model->logout();
    }
?>
