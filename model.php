<?php
    require "./lib/twitteroauth/autoload.php";
    require "./lib/tcpdf/tcpdf.php";
    use Abraham\TwitterOAuth\TwitterOAuth;
    include('config.php');

    class Model {

        // connect using twitter
        public function connection() {
            if (!isset($_SESSION['access_token'])) {
                $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
                $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
                $_SESSION['oauth_token'] = $request_token['oauth_token'];
                $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
                $url = $connection->url('oauth/authorize', array('oauth_token' => $_SESSION['oauth_token']));
                header('location:' . $url);
            }
            else {
                header('Location: ./view.php');
            }
        }

        // redirect user back to index page
        public function callback(){
            $request_token = [];
            $request_token['oauth_token'] = $_REQUEST['oauth_token'];
            $request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];
            $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $request_token['oauth_token'], $request_token['oauth_token_secret']);
            $access_token = $connection->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));
            $_SESSION['access_token'] = $access_token;
            header('Location: ./view.php');
        }

        // To get connection from access_token
        public function getConnection() {
            $access_token = $_SESSION['access_token'];
            $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
            return $connection;
        }

        // get current logged in user
        public function getUser($connection) {
            $user = $connection->get("account/verify_credentials");
            return $user;
        }

        // search follower
        public function searchFollower() {
            $connection = $this->getConnection();
            if (isset($_GET['term'])) {
                if (!isset($_SESSION['data'])) {
                    //include_once "getfollowerslist_ajax.php";
                    $connection = $this->getConnection();
                    $myprofile_value = $_SESSION['my_profile'];
                    $my_scrren_name = $myprofile_value['screen_name'];
                    $followerslist = $connection->get("followers/ids", array('screen_name' => $my_scrren_name, 'count' => 5000));
                    $cnt = 0;
                    $var_assign = 0;
                    $loop_cnt = '';
                    foreach ($followerslist->ids as $followr_id) {
                        if ($cnt % 100 == 0) {
                            $var_assign = $var_assign + 1;
                            $loop_cnt = $var_assign;
                            ${"var$var_assign"} = '';
                        }
                        ${"var$var_assign"} = ${"var$var_assign"} . "," . $followr_id;

                        $cnt = $cnt + 1;
                    }
                    $response_array = array();
                    $new = 1;
                    for ($i = 1; $i <= $loop_cnt; $i++) {
                        $id_lookup = $connection->get("users/lookup", array('user_id' => ${"var$i"}));
                        foreach ($id_lookup as $key => $value) {
                            $response_array[$new]['id'] = $value->id;
                            $response_array[$new]['name'] = $value->screen_name;
                            $new = $new + 1;
                        }
                    }
                    $_SESSION['data'] = $response_array;
                }
                $keyword = $_GET['term'];
                $my_search = array();
                $my_search = $_SESSION['data'];
                $public_user = array();
                for ($i = 1; $i <= 3; $i++) {
                    $followerslist = $connection->get("users/search", array('q' => $keyword, 'count' => 20, 'page' => $i));
                    foreach ($followerslist as $key => $value) {
                        $public_user[] = $value->screen_name;
                    }
                }
                $follower_session = $my_search;
                $followername_array = array();
                foreach ($follower_session as $key => $follw_value) {
                    $followername_array[$key] = $follw_value['name'];
                }
                $input = preg_quote($keyword, '~');
                $result1 = preg_grep('~' . $input . '~', $followername_array);
                $final_result = array_merge($result1, $public_user);
                if (empty($final_result)) {
                    $final_result = array("No user found");
                }
                echo json_encode($final_result);
            }
        }

        // get 10 tweet of user
        public function getUserTweets($screen_name) {
            $connection = $this->getConnection();
            $tweets = $connection->get("statuses/user_timeline",["count" => 10, "exclude_replies" => true,"screen_name" => $screen_name]);
            foreach( $tweets as $val ) {
                $t[] = array(
                    'text' => $val->text
                );
            }
            if( count($tweets) == 0 )
                $t[] = array(
                    'text' => 'No Tweets Found'
                );
            return $t;
        }

        // get tweet of logged in user
        public function getUserAllTweets($screen_name) {
            $connection = $this->getConnection();
            $tweets = $connection->get("statuses/user_timeline",["count" => 200, "exclude_replies" => true,"include_rts"=>true,"screen_name" => $screen_name]);
            if( count($tweets) == 1 ) {
                $user_tweets[] = 'Soory, No Tweets Found';
                return $user_tweets;
            }
            $totalTweets[] = $tweets;
            $page = 0;
            for ($count = 200; $count <= 3200; $count += 200) {
                $max = count($totalTweets[$page]) - 1;
                $tweets = $connection->get('statuses/user_timeline', ["count" => 200, "exclude_replies" => true,"include_rts"=>true,"screen_name" => $screen_name, 'max_id' => $totalTweets[$page][$max]->id_str]);
                if( count($tweets) == 1 ) {
                    break;
                }
                $totalTweets[] = $tweets;
                $page += 1;
            }
            $start = 1;
            $index = 0;
            foreach ($totalTweets as $page) {
                foreach ($page as $key) {
                    $user_tweets[$index++] = $key->text;
                    $start++;
                }
            }
            return $user_tweets;
        }

        // get follower of logged in user
        public function getFollowers($screen_name) {
            $connection = $this->getConnection();
            $next = -1;
            $max = 0;
            while( $next != 0 ) {
                $friends = $connection->get("followers/list", ["screen_name"=>$screen_name,"next_cursor"=>$next]);
                $followers[] = $friends;
                $next = $friends->next_cursor;
                if($max==0)
                    break;
                $max++;
            }
            foreach( $followers as $val ) {
                foreach( $val->users as $usr ) {
                    $f[] = array(
                        'name' => $usr->name,
                        'screen_name' => $usr->screen_name,
                        'propic' => $usr->profile_image_url_https
                    );
                }
            }
            $json = array(
                'followers' => $f
            );
            echo json_encode($json);
        }

        // to get detail of user
        public function getFollowerDetail($id) {
            $connection = $this->getConnection();
            $user = $connection->get("users/show",['screen_name'=>$id]);
            $name = $user->name;
            $propic = $user->profile_image_url_https;
            $screen_name = $user->screen_name;
            $tweets = $this->getUserTweets($screen_name);
            $res = array(
                'name' => $name,
                'propic' => $propic,
                'tweets' => $tweets
            );
            $json = json_encode($res);
            echo $json;
        }

        // to get detail of logged in user
        public function getLoggedInUserDetail() {
            $connection = $this->getConnection();
            $user = $this->getUser($connection);
            $tweets = $this->getUserTweets($user->screen_name);
            $screen_name = $user->screen_name;
            $res = array(
                'id' => $user->id,
                'name' => $user->name,
                'screen_name' => $user->screen_name,
                'propic' => $user->profile_image_url_https,
                'tweets' => $tweets,
            );
            $json = json_encode($res);
            echo $json;
        }

		// download tweet in csv format
        public function downloadCSV() {
            $connection = $this->getConnection();
            $user = $this->getUser($connection);
            $tweets[] = $this->getUserAllTweets($user->screen_name);
            header("Content-type: text/csv");
            header("Content-Disposition: attachment; filename=tweets.csv");
            header("Pragma: no-cache");
            header("Expires: 0");
            $count = count($tweets);
            for($i=0;$i<$count;$i++) {
                $c = count($tweets[$i]);
                for($j=0;$j<$c;$j++) {
                    echo $tweets[$i][$j].' , ' ;
                }
            }
        }

        // download tweet in json format
        public function downloadJSON() {
            $connection = $this->getConnection();
            $user = $this->getUser($connection);
            $tweets[] = $this->getUserAllTweets($user->screen_name);
            header('Content-disposition: attachment; filename=tweets.json');
            header('Content-type: application/json');
            header("Pragma: no-cache");
            header("Expires: 0");
            $arr = array(
                'tweets' => $tweets[0]
            );
            $arr = json_encode($arr);
            print_r($arr);
        }

        // upload spreedsheet to google drive
        public function uploadGoogleDrive() {
            $connection = $this->getConnection();
            $user = $this->getUser($connection);
            $tweets = $this->getUserAllTweets($user->screen_name);
            return $tweets;
        }

        // download follower in xml format
        public function dowloadXML($key) {
            $val="follower";
            $connection = $this->getConnection();
            $user = $this->getUser($connection);
            $followerslistX = $this->getAllFollower($key);
            header("Content-type: text/xml");
            header("Content-Disposition: attachment; filename=follower.xml");
            header("Pragma: no-cache");
            header("Expires: 0");
            foreach($followerslistX as $aa) {
                echo "<". $val .">". $aa ."</". $val .">";
            }
        }

        // upload spreedsheet to google drive
        public function uploadGoogleDriveFollower($key) {
            $connection = $this->getConnection();
            $user = $this->getUser($connection);
            $followerslistD = $this->getAllFollower($key);
            return $followerslistD;
        }

        // download follower list of user (pagignation)
        // using this method you can get follower upTo 5000, after download follower you are not able to perform other action so i create other function that download follower upto 200.
        public function getAllFollower($key) {
            $connection = $this->getConnection();
            $cursor = -1;
            $max = 0;
            while( $cursor != 0 ) {
                $followerArray = $connection->get("followers/list", ["count" => 200,"screen_name"=>$key,"next_cursor"=> $cursor]);
                $followers[] = $followerArray;
                $cursor = $followerArray->next_cursor;
                if($max==0)
                    break;
                $max++;
            }
        	$list=[];
			foreach( $followers as $val ) {
                foreach( $val->users as $usr ) {
                    $name = $usr->name;
					$list[]=$name;
                }
            }
        	return $list;

        }

        // download follower in pdf format
        public function downloadPDF($screen_name) {
            $tweets = $this->getFollowersuser($screen_name);

            $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $obj_pdf->SetCreator(PDF_CREATOR);
			$obj_pdf->SetTitle("Follower List");
            $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
			$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
			$obj_pdf->SetDefaultMonospacedFont('helvetica');
			$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
			$obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);
			$obj_pdf->setPrintHeader(false);
			$obj_pdf->setPrintFooter(false);
			$obj_pdf->SetAutoPageBreak(TRUE, 10);
			$obj_pdf->SetFont('helvetica', '', 12);
			$obj_pdf->AddPage();
			$obj_pdf->writeHTML($tweets);

            $obj_pdf->Output('follower.pdf', 'D');
        }

        public function getFollowersuser($screen_name) {
            $connection = $this->getConnection();
				$count = 1;
				$cursor = -1;
				while($count != 0)
				{
					$follower = $connection->get('followers/ids',array('count'=>200,'screen_name'=>$screen_name,'cursor'=>$cursor));
					$cursor = $follower->next_cursor;
					if(!isset($cursor))
					{
						break;
					}

					$namearrays= array_chunk($follower->ids, 100);
					foreach($namearrays as $implode) {
						$data = $connection->get('users/lookup', array('user_id' => implode(',', $implode)));
						foreach($data as $users) {
							$name = $users->name;
							$htmltext .= '<h5>'.$name.'</h5><br>';
							$count++;
						}
					}
				}
				return $htmltext;

        }

        // logout
        public function logout() {
            session_unset();
            session_destroy();
            header("location:https://rtcampttn.herokuapp.com/index.php");
            exit();
        }
    }
?>
