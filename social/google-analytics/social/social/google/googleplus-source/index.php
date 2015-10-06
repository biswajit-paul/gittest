<?php
    include_once "google-plus-access.php";

		if( isset( $_SESSION['access_token'] ) )
		{
				//Get user info
				$url = 'https://www.googleapis.com/oauth2/v1/userinfo?access_token='.$_SESSION['access_token'];
				$gplus_user = json_decode(file_get_contents($url)); //This gives me personal information about the user.
		
				echo '<pre>'; print_r($gplus_user); echo '</pre>';
		}
		else
		{
				echo 'two';
		}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>25 labs Google+ API Demo</title>
        <link rel='stylesheet' href='style.css' />
    </head>
<body>
<div id="bar">
	<div class="top-area" >
		<div class="logo" ><a href="http://25labs.com" ><img src="25-lab-logo.png" /></a></div>
		<div class="link" ><a href="http://25labs.com/tutorial-implementing-google-api-using-oauth-2-0-in-php/" ><h4>Click here to read the tutorial</h4></a></div>
		<div class="login" >
		<?php if(isset($me) && isset($activities)) { ?>
				<a href="?logout" ><h5>Logout</h5></a>
		<?php } else { ?>
				<a href="<?php echo($authUrl); ?>" ><h5>Login</h5></a>
		<?php } ?>
		</div>
	</div>
</div>
<?php if(isset($me) && isset($activities)) { ?>
<div class="big-container" >
	<div class="profile" >
		<div class="profile-pic" ><a href="<?php echo(substr($me['image']['url'],0,stripos($me['image']['url'],'?sz='))); ?>" ><img src="<?php echo(substr($me['image']['url'],0,stripos($me['image']['url'],'?sz='))); ?>?sz=200" /></a>
		</div>
		<div class="profile-info" >
			<div class="name" ><a href="<?php echo($me['url']) ; ?>" ><?php if(isset($me['displayName'])) echo(strtoupper($me['displayName'])); else echo "Not set or private"; ?></a></div>
			<div class="about" ><?php if(isset($me['aboutMe'])) echo($me['aboutMe']); else echo "Not set or private"; ?></div>
			<div class="details" >
				<ul>
					<li><b>GENDER :  </b><?php if(isset($me['gender'])) echo($me['gender']); else echo "Not set or private"; ?></li>
					<li><b>ORGANISATION :  </b><?php if(isset($me['organizations']['0']['name'])) echo($me['organizations']['0']['name']); else echo "Not set or private"; ?></li>
					<li><b>PLACE :  </b><?php if(isset($me['placesLived']['0']['value'])) echo($me['placesLived']['0']['value']); else echo "Not set or private"; ?></li>
				</ul>
			</div>
		</div>
	</div>
	<?php foreach($activities['items'] as $activity): ?>
	<div class="activity" >
		<div class="title" ><a href="<?php echo($activity['object']['url']) ; ?>" ><?php echo($activity['object']['content']); ?></a></div>
		<p>Published at <?php echo($activity['published']); ?></p>
		<p>
			<?php echo($activity['object']['replies']['totalItems']); ?> Replys . 
			<?php echo($activity['object']['plusoners']['totalItems']); ?> Plusoners . 
			<?php echo($activity['object']['resharers']['totalItems']); ?> Reshares
		</p>
	</div>
	<?php endforeach ?>
</div>
<?php } else {?>
<div class="login-box">
	<div id="connect-button"><a href="<?php echo($authUrl); ?>" ><img src="connect-button.png" alt="Connect to your Google+ Account"/></a><div>
	This demo is purely read-only. It will <b>NOT</b> post anything to your profile.
</div>
<?php } ?>
</body>
</html>