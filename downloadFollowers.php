<?php
    use Abraham\TwitterOAuth\TwitterOAuth;
    require "./lib/twitteroauth/autoload.php";
    include('config.php');
    session_start();
    $access_token = $_SESSION['access_token'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

    if( !isset($_SESSION['cursor']) ) {
        $_SESSION['cursor']=-1;
        $cursor = $_SESSION['cursor'];
        $max = 0;
    } else {
        $cursor=$_SESSION['cursor'];
        if( $_SESSION['cursor'] != 0 ) {
            $followerArray = $connection->get("followers/list", ["count" => 200,"screen_name"=>$key,"next_cursor"=> $cursor]);
            $followers[] = $followerArray;
            foreach( $followers as $val ) {
                foreach( $val->users as $usr ) {
                    $name = $usr->name;
                    $list[]=$name;
                }
            }
            $old=$_SESSION['list'];
            $_SESSION['list']=array_merge($old,$list);
            $_SESSION['cursor'] = $followerArray->next_cursor;
            if($max==0)
                break;
            $max++;
        }else{
            $val="follower";
            $followerslistX = $_SESSION['list'];
            header("Content-type: text/xml");
            header("Content-Disposition: attachment; filename=follower.xml");
            header("Pragma: no-cache");
            header("Expires: 0");
            foreach($followerslistX as $aa) {
                echo "<". $val .">". $aa ."</". $val .">";
            }
        }     
    }
       
?>