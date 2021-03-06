<?php
session_start();
require_once __DIR__ . '/src/Facebook/autoload.php';
$fb = new Facebook\Facebook([
  'app_id' => '1731687233731694',
  'app_secret' => 'c7e71aecbb66f66d8ee37594c12fafc5',
  'default_graph_version' => 'v2.5',
  ]);
$helper = $fb->getRedirectLoginHelper();
$name="";
$permissions = ['email']; // optional
	
try {
	if (isset($_SESSION['facebook_access_token'])) {
		$accessToken = $_SESSION['facebook_access_token'];
	} else {
  		$accessToken = $helper->getAccessToken();
	}
} catch(Facebook\Exceptions\FacebookResponseException $e) {
 	// When Graph returns an error
 	echo 'Graph returned an error: ' . $e->getMessage();
  	exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
 	// When validation fails or other local issues
	echo 'Facebook SDK returned an error: ' . $e->getMessage();
  	exit;
 }
if (isset($accessToken)) {
	if (isset($_SESSION['facebook_access_token'])) {
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
	} else {
		// getting short-lived access token
		$_SESSION['facebook_access_token'] = (string) $accessToken;
	  	// OAuth 2.0 client handler
		$oAuth2Client = $fb->getOAuth2Client();
		// Exchanges a short-lived access token for a long-lived one
		$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
		$_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
		// setting default access token to be used in script
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
	}
	// redirect the user back to the same page if it has "code" GET variable
	if (isset($_GET['code'])) {
		header('Location: ./');
	}
	// getting basic info about user
	try {
		$profile_request = $fb->get('/me?fields=name,first_name,last_name,email');
		$profile = $profile_request->getGraphNode()->asArray();
		$name="<a href=\"#\">".$profile["name"]."</a>";
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		// When Graph returns an error
		echo 'Graph returned an error: ' . $e->getMessage();
		session_destroy();
		// redirecting user back to app login page
		header("Location: ./");
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		// When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}
	
	// printing $profile array on the screen which holds the basic info about user
  	// Now you can redirect to another page and use the access token from $_SESSION['facebook_access_token']
} else {
	// replace your website URL same as added in the developers.facebook.com/apps e.g. if you used http instead of https and you used non-www version or www version of your website then you must add the same here
	$loginUrl = $helper->getLoginUrl('http://localhost:1337/fake/index.php', $permissions);
	$name= '<a href="' . $loginUrl . '"><img src="img/facebook-login-button.png"></a>';
}

//if ($logout = "true" set $name = hide);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title>UniSearch</title>
		<meta name="generator" content="Bootply" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<!--[if lt IE 9]>
			<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link href="css/styles.css" rel="stylesheet">
	</head>
	<body>

<!-- FACEBOOK LOGOUT BUTTON SCRIPT
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/sv_SE/sdk.js#xfbml=1&version=v2.5&appId=1731687233731694";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
 	END FACEBOOK LOGOUT BUTTON SCRIPT -->

<!-- begin template -->
<div class="navbar navbar-custom navbar-fixed-top">
	<img src="img/logo_placeholder.png" style="position: absolute; width: 150px; height: 80px; margin-top: -15px;">
<form class="col-sm-12 col-md-12 col-xs-12 col-lg-12" role="search" method="get" action="results.php" id="serach-form" style="position: absolute; left: -20%; margin-top: 2px;">
    <div class="input-group input-group-lg col-sm-offset-4 col-sm-4 col-md-4 col-lg-4">
      <input type="text" class="center-block form-control input-lg form-control" id="search-field" name="search" title="enter your search key words" placeholder="begin your search..">
      <span class="input-group-btn"><button class="btn btn-lg btn-primary" id="search-button" type="button">Search!</button></span>
    </div>
</form>
 <div class="navbar-header"><a class="navbar-brand" href="#"></a>
      <a class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
    </div>
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav pull-right">
        <li class="active">
        	<?php
				echo $name;
			?>
			<!-- FACEBOOK <center><div class="fb-login-button" data-max-rows="1" data-size="medium" data-show-faces="false" data-auto-logout-link="true"></div></center> -->
		</li>
        <li>&nbsp;</li>
      </ul>
      <form class="navbar-form">
        <div class="form-group pull-right" style="display:inline;">
          <div class="input-group">
            <div class="input-group-btn">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-chevron-down"></span></button>
              <ul class="dropdown-menu">
                <li><a href="#">Action 1</a></li>
                <li><a href="#">Action 2</a></li>
                <li><a href="#">Action 3</a></li>
                <li><a href="#">Action 4</a></li>
                <li><a href="#">Action 5</a></li> 
              </ul>
            </div>
          </div>
        </div>
      </form>
    </div>
</div>

<div class="container-fluid" id="main">
  <div class="row">
  	   
      <div class="container-full">

      <div class="row">
       
        <div class="text-center v-center">
          
          <br>
          <br />
          <form class="container" id="search-form" style="border: 1px solid red; ">
	          <?php
				mysql_connect("localhost", "root", "") or die(mysql_error());
				mysql_select_db("search") or die(mysql_error());

				$clean = mysql_real_escape_string($_GET['search']);
				$hello = mysql_query("SELECT * FROM items WHERE title = '$clean'") or die(mysql_error());
				if(mysql_num_rows($hello) >= 1) {
					while ($i = mysql_fetch_array($hello)) {
						echo '<br><a href="'.$i['url'].'" target="_blank">'.$i['title'].'</a><p>'. $i['url'].'</p><p>'. $i['description'].'</p>';
					}
				}
				else {
					echo "no results were found. please try a different search";
				}


				?>
			</form>
        </div>
        
      </div> <!-- /row -->
  
  	  
  	<br><br><br><br><br>

</div> <!-- /container full -->

<div class="container" style="position: absolute; top: 95%;">
      <!-- /item list -->
      <p>
      <a href="http://www.facebook.com">facebook</a> | <a href="http://www.twitter.com">twitter</a> | <a href="http://www.twitter.com">UniSearch</a>
      </p>
   
    </div>
    <div class="col-xs-4"><!--map-canvas will be postioned here--></div>
    
  </div>
</div>
<!-- end template -->

	<!-- script references -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="http://maps.googleapis.com/maps/api/js?sensor=false&extension=.js&output=embed"></script>
		<script src="js/scripts.js"></script>
	</body>
</html>