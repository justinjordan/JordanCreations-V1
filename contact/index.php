<?php
	DEFINE('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
	
	require_once(ROOT_PATH . '/classes/connection.php');
	$link = new Connection();
	
	require_once(ROOT_PATH . '/classes/login.php');
	$login = new Login($link);
	
	require_once('../classes/contact.php');
	$contact = new Contact($link);
	
	$success = false;
	$incomplete = true;
	
	if (isset($_GET['submit']))
	{
		$submit = true;
		
		if ($_POST['name'] != '' && $_POST['email'] != '' && $_POST['comments'] != '')
		{
			$incomplete = false;
			
			$success = $contact->sendMessage($_POST['name'], $_POST['email'], $_POST['comments']);
		}
		else
		{
			$incomplete = true;
		}
	}
	else
	{
		$submit = false;
	}
	
	
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Contact | JordanCreations</title>
		<link rel="stylesheet" type="text/css" href="/css/reset.css"/>
		<link rel="stylesheet" type="text/css" href="/css/style.css"/>
		<link rel="stylesheet" type="text/css" href="/css/typography.css"/>
		<link rel="stylesheet" type="text/css" href="/css/contact.css"/>
		
		<link rel="stylesheet" type="text/css" href="/fancybox/jquery.fancybox.css" media="screen"/>
		
		<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico?v=1"/>
		<!--[if IE]>
		<link rel="stylesheet" href="css/iecompat.css" type="text/css" />
		<![endif]-->
		<!--[if lt IE 9]>
		<script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
	</head>
	<body>
		<div id="wrapper">
			<section id="top-bar">
				<div id="top-bar-wrapper">
					<nav>
							<?php
								if ($login->logged_in):
								
									include_once(ROOT_PATH . '/user_nav.php');
								
								else:
							?>
							<ul>
								<li>
									<a id="sign-in"><span>[sign in]</span></a>
									<div id="login-content"  style="display:none">
										<form method="post" action="/ajax/ajax.login.php">
											<span id="login-input">
												<input type="text" name="user" placeholder="Username" autofocus required/>
												<input type="password" name="pass" placeholder="Password" required/>
											</span>  <!-- login-text -->
											<div id="login-spinner"></div>
											<span id="login-action">
												<input type="submit" value="sign in"/>
											</span>  <!-- login-button -->
										</form>
									</div>  <!-- login-content -->
								</li>
							</ul>
							<?php
								endif;
							?>
					</nav>
				</div>
			</section>  <!-- top-bar -->
			<section id="container">
				<header id="site-header">
					<figure id="logo">
						<a href="/"><img src="/images/logo.gif"/></a>
					</figure>
				</header>
				<section id="main-nav" class="drop-shadow">
					<nav>
						<ul>
							<li><a href="/">home</a></li>
							<li><a href="/photo">photo</a></li>
							<li><a class="selected">contact</a></li>
						</ul>
					</nav>
				</section>
				<section id="main-section" class="drop-shadow">
					<div id="contactInfo">
						<figure id="portrait">
							<img src="/images/portrait.jpg" style="width: 200px; height: 200px;"/>
							<p>Justin Jordan</p>
						</figure>
						<div id="info">
							<p class="contact-heading">JordanCreations</p>
							<p class="contact-info">Minneapolis, MN</p>
							<p class="contact-info"><a href="tel:16125841315">+1 612-584-1315</a></p>
							<p class="contact-info"><a href="mailto:justin@jordancreations.com" target="_blank">justin@jordancreations.com</a></p>
						</div>
					</div>
					<div id="contactForm">
						<?php 
							if ($submit):
								if ($success):
							?>
								<p class="success_msg">Thank you for your message!</p>
							<?php
								else:
									if ($incomplete):
							?>
								<p class="incomplete_msg">Please complete the form!</p>
								<form method="post" action="?submit">
									<p>
										<label for="name">Name:</label>
										<input type="text" name="name" id="name"/>
									</p>
									<p>
										<label for="email">Email:</label>
										<input type="text" name="email" id="email"/>
									</p>
									<p>
										<label for="comments">Comments:</label>
										<textarea name="comments" id="comments"></textarea>
									</p>
									<p style="text-align: center;">
										<input type="submit" id="sendButton" value="Send"/>
									</p>
								</form>
								<?php
									else:
								?>
								<p class="failure_msg">Your message was rejected.  If you've sent a message recently, please wait 24 hours and then try again.</p>
							<?php
									endif;
								endif;
							?>
						<?php
							else:
						?>
						<form method="post" action="?submit">
							<p>
								<label for="name">Name:</label>
								<input type="text" name="name" id="name"/>
							</p>
							<p>
								<label for="email">Email:</label>
								<input type="text" name="email" id="email"/>
							</p>
							<p>
								<label for="comments">Comments:</label>
								<textarea name="comments" id="comments"></textarea>
							</p>
							<p style="text-align: center;">
								<input type="submit" id="sendButton" value="Send"/>
							</p>
						</form>
						<?php endif; ?>
					</div>
				</section>
			</section>
			<footer id="footer">
				<nav>
					<ul>
						<li><a href="/">home</a></li>
						<li><a class="/photo">photo</a></li>
						<li><a class="selected">contact</a></li>
						</ul>
				</nav>
				<aside>
					<span>&copy;</span> Copyright 2013 - Justin Jordan
				</aside>
			</footer>
		</div>  <!-- wrapper -->
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
		<script type="text/javascript" src="/javascript/jquery.login.js"></script>
		<script type="text/javascript" src="/fancybox/jquery.fancybox.pack.js"></script>
	</body>
</html>
