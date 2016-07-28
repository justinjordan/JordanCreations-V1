<?php
	DEFINE('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
	
	require_once(ROOT_PATH . '/classes/connection.php');
	$link = new Connection();
	
	require_once(ROOT_PATH . '/classes/login.php');
	$login = new Login($link);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Photo | JordanCreations</title>
		<link rel="stylesheet" type="text/css" href="/css/reset.css"/>
		<link rel="stylesheet" type="text/css" href="/css/style.css"/>
		<link rel="stylesheet" type="text/css" href="/css/typography.css"/>
		<link rel="stylesheet" type="text/css" href="/css/photo.css"/>
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
										<form method="post">
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
						<a href="../"><img src="../images/logo.gif"/></a>
					</figure>
				</header>
				<section id="main-nav" class="drop-shadow">
					<nav>
						<ul>
							<li><a href="../">home</a></li>
							<li><a class="selected">photo</a></li>
							<li><a href="/contact">contact</a></li>
						</ul>
					</nav>
				</section>
				<section id="main-section" class="drop-shadow">
					
					<!-- PHOTO SECTION -->
					<?php
						if ($login->logged_in && $login->rights >= 2):
					?>
					<form id="upload" enctype="multipart/form-data" method="post" action="../ajax/ajax.uploadphoto.php" target="upload-frame">
						<input type="hidden" value="upload" name="<?php echo ini_get("session.upload_progress.name"); ?>"/>
						<input type="file" name="file" id="file" style="display: none"/>
						<span id="file-name"></span>
						<span id="add-button" class="jquery-link">[add]</span>
						<button type="button" id="file-button" style="display: none">...</button>
						<input type="submit" id="upload-button" style="display: none" value="Upload"/>
						<div id="upload-loader" style="display:none"></div>
					</form>
					<iframe id="upload-frame" style="display:none"></iframe>
					<?php
						endif;
					?>
					
					<section id="thumbs">
					</section>
					<div id="show-more-loader">
						<img src="../images/show-more-loader.gif" id="show-more-loader"/>
					</div>
					
					<!-- END OF PHOTO SECTION -->
					
				</section>
			</section>
			<footer id="footer">
				<nav>
					<ul>
						<li><a href="../">home</a></li>
						<li><a class="selected">photo</a></li>
						<li><a href="/contact">contact</a></li>
						</ul>
				</nav>
				<aside>
					<span>&copy;</span> Copyright 2013 - Justin Jordan
				</aside>
			</footer>
		</div>  <!-- wrapper -->
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
		<script type="text/javascript" src="/javascript/jquery.imagesloaded.min.js"></script>
		<script type="text/javascript" src="/javascript/jquery.masonry.min.js"></script>
		<script type="text/javascript" src="/javascript/jquery.photo.js"></script>
		<script type="text/javascript" src="/javascript/jquery.login.js"></script>
		<script type="text/javascript" src="/fancybox/jquery.mousewheel-3.0.6.pack.js"></script>
		<script type="text/javascript" src="/fancybox/jquery.fancybox.pack.js"></script>
		<script type="text/javascript">
			$(function ()
			{
				$(".photo-thumb > a").fancybox();
			});
		</script>
	</body>
</html>
