<?php
	DEFINE('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
	
	require (ROOT_PATH . '/classes/connection.php');
	$connection = new Connection();
	
	require (ROOT_PATH . '/classes/login.php');
	$login = new Login($connection);
	
	require (ROOT_PATH . '/classes/blog.php');
	$blog = new Blog($connection);
	
	require (ROOT_PATH . '/classes/bbcode.php');
	$bb = new BBCode();
	
	$page = 1;  // DEFAULT
	
	if (isset($_GET['page']))
	{
		if (is_numeric($_GET['page']))
			$page = $_GET['page'];
	}
	
	$postTotal = $blog->getPostTotal();
	
	$postsDisplayed = 5 * $page;
	
	$result = $blog->getMultiplePosts(0, $postsDisplayed);

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>JordanCreations</title>
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<link rel="stylesheet" type="text/css" href="css/reset.css"/>
		<link rel="stylesheet" type="text/css" href="css/style.css"/>
		<link rel="stylesheet" type="text/css" href="css/typography.css"/>
		<link rel="stylesheet" type="text/css" href="css/home.css"/>
		<link rel="shortcut icon" type="image/x-icon" href="favicon.ico?v=1"/>
		<link rel="stylesheet" type="text/css" href="/fancybox/jquery.fancybox.css" media="screen"/>
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
						<a href="#"><img src="images/logo.gif"/></a>
					</figure>
				</header>
				<section id="main-nav" class="drop-shadow">
					<nav>
						<ul>
							<li><a href="#" class="selected">home</a></li>
							<li><a href="/photo/">photo</a></li>
							<li><a href="/contact">contact</a></li>
						</ul>
					</nav>
				</section>
				<section id="main-section" class="drop-shadow">
					<?php
						if ($login->logged_in && $login->rights >= 2):
					?>
					<div id="blog-edit">
						<span id="new-post" class="jquery-link">[new post]</span>
					</div>
					<?php
						endif;
					?>
					<aside id="twitterfeed">
						<a class="twitter-timeline" href="https://twitter.com/jordancreations" data-widget-id="249748223395250176"></a>
						<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
					</aside>
					<section id="blog">
						<?php
							if ($login->logged_in):
						?>
						<div id="post-form" style="display: none">
							<form class="form" method="post">
								<fieldset class="inputs">
									<input type="text" name="title" placeholder="Title" maxlength="64" required/><br/>
									<textarea name="post" placeholder="Type your message!" required></textarea>
								</fieldset>
								<fieldset class="actions">
									<button id="post-submit" class="post-submit" type="submit">post</button>
								</fieldset>
							</form>
						</div>
						<div id="edit-form" style="display: none">
							<form class="form" method="post">
								<fieldset class="inputs">
									<input type="text" name="title" placeholder="Title" maxlength="64" required/><br/>
									<textarea name="post" placeholder="Type your message!" required></textarea>
									<input type="hidden" name="edit-id"/>
								</fieldset>
								<fieldset class="actions">
									<button id="edit-submit" class="post-submit" type="submit">edit</button>
								</fieldset>
							</form>
						</div>
						<?php endif; ?>
						
						
						<div id="blog-postings">
							<?php
								while($row = $result->fetch_assoc()):
									$id = $row['id'];
									$title = $row['title'];
									$user = $row['user'];
									$date = new DateTime($row['date']);
									$dayLapse = $row['dayLapse'];
									$secLapse = $row['secLapse'];
									$post = $row['post'];
									$userInfo = $login->getUserInfo($user);
							?>
							
							<div class="blog-post" id="blog-<?php echo $id; ?>">
								<figure>
									<img src="images/users/<?php echo $user; ?>/thumbnail.jpg" style="width: 50; height: 50;"/>
								</figure>
								<article>
									<header><h1><?php echo $title; ?></h1></header>
									<aside>
										by <span><?php echo $userInfo['first_name'] . " " . $userInfo['last_name']; ?></span> [<?php echo $blog->humanizeDate($dayLapse, $secLapse); ?>]
									</aside>
									<section>
										<p>
											<?php echo $bb->filter($post); ?>
										</p>
									</section>
								</article>
								<?php
									if ($login->logged_in && $user == $login->user):
								?>
								<footer>
									<input type="hidden" class="hidden-id" value="<?php echo $id; ?>"/>
									<a class="edit">[edit]</a>
									<a class="delete">[delete]</a>
								</footer>
								<?php
									endif;
								?>
							</div>
							
							<?php
								endwhile;
								
								$result->close();
							?>
						</div>  <!-- blog-postings -->
						<?php
							if ($postTotal > $postsDisplayed):
						?>
						<footer id="blog-actions">
							<img src="images/show-more-loader.gif" class="show-more-loader" style="visibility:hidden"/>
						</footer>  <!-- blog-actions -->
						<?php
							endif;
						?>
					</section>  <!-- blog -->
					
				</section>
			</section>
			<footer id="footer">
				<nav>
					<ul>
						<li><a class="selected">home</a></li>
						<li><a href="photo">photo</a></li>
						<li><a href="/contact">contact</a></li>
						</ul>
				</nav>
				<aside>
					<span>&copy;</span> Copyright 2013 - Justin Jordan
				</aside>
			</footer>
		</div>  <!-- wrapper -->
		<script type="text/javascript" src="/javascript/jquery.login.js"></script>
		<script type="text/javascript" src="/fancybox/jquery.fancybox.pack.js"></script>
		<script type="text/javascript">
			$(function() {
				
				var post_submitable = true;
				var edit_submitable = true;
				var edit_on = false;
				var showmore_enabled = true;
				
				var postTotal = getPostTotal();
				
				/*  EVENTS  */
				
				$("#new-post").click(function()  /* NEW POST CLICK */
				{
					if ($("#edit-form").is(":hidden"))
					{
						showPostForm();
					}
					else
					{
						$("#edit-form").slideUp({duration: "normal", queue: false, complete: function()
						{
							showPostForm();
						}});
					}
				});
				
				$(document).on("click", ".edit", function ()  /* EDIT LINK CLICK */
				{
					var selector = $(this).parent().parent();
					var id = selector.find(".hidden-id").val();
					var title = selector.find("article > header > h1").text();
					var post = '';
					selector.find("article > section > p").each(function (index)
					{
						post += replaceAll('\t', '', $(this).text()).replace('\n', '');
						
						if (index < selector.find("article > section > p").length - 1)
						{
							post += '\n';
						}
					});
					
					if ($("#post-form").is(":hidden"))
					{
						showEditForm(selector, id, title, post);
					}
					else
					{
						$("#post-form").slideUp({duration: "normal", queue: false, complete: function()
						{
							showEditForm(selector, id, title, post);
						}});
					}
				});
				
				$(document).on("click", ".delete", function ()  /* DELETE LINK CLICK */
				{
					if (confirm("Are you sure you would like to DELETE this post?"))
					{
						var selector = $(this).parent().parent();
						var id = selector.find(".hidden-id").val();
						
						deletePost(selector, id);
					
					}
				});
				
				$("#post-form > form").submit(function(e)  /* NEW POST SUBMIT */
				{
					e.preventDefault();
					
					$("#post-submit").prepend("<img src=\"images/ajax-loader2.gif\" class=\"ajax-loader\"/>");
					
					newPost();
				});
				
				$("#edit-form > form").submit(function(e)  /* EDIT POST SUBMIT */
				{
					e.preventDefault();
					
					$("#edit-submit").prepend("<img src=\"images/ajax-loader2.gif\" class=\"ajax-loader\"/>");
					
					editPost();
				});
				
				$(window).scroll(function ()
				{
					testScrollPosition();
				});
				
				/*  FUNCTIONS  */
				
				function replaceAll(find, replace, str) {
					return str.replace(new RegExp(find, 'g'), replace);
				}
				
				function testScrollPosition()
				{
					var scrollPos = $(window).scrollTop();
					var pageHeight = $(window).height();
					var offset = $("#blog-actions").offset();
					var bottomPos = offset.top - pageHeight;
					
					
					if (scrollPos > bottomPos)
					{
						showMore();
					}
				}
				
				function filterBB(string)
				{
					var newString = string;
					
					$.ajax({
						  url:  "ajax/ajax.filterbb.php",
						  type: "POST",
						  async: false,
						  dataType: 'json',
						  data: {string: string},
						  success: function(data)
						  {
							 newString = data.string;
						  }
					   }
					);
					
					return newString;
				}
				
				function getPostTotal()
				{
					var total = 0;
					
					$.ajax({
						  url:  "ajax/ajax.getposttotal.php",
						  type: "GET",
						  async: false,
						  dataType: 'json',
						  success: function(data)
						  {
							 total = data.total;
						  }
					   }
					);

					return total;
				}
				
				function showMore()
				{
					if (showmore_enabled)
					{
						showmore_enabled = false;
						
						var amountShown = $(".blog-post").length;
						
						$("#blog-actions > img").css('visibility', 'visible');
						
						$.ajax({
							  url:  "ajax/ajax.displayposts.php",
							  data: {offset: amountShown, amount: <?php echo $postsDisplayed; ?>},
							  type: "GET",
							  async: true,
							  success: function(data)
							  {
								 $(data).appendTo("#blog-postings");
								
								if ($(".blog-post").length != postTotal)
								{
									showmore_enabled = true;
								}
								
								$("#blog-actions > img").css('visibility', 'hidden');
								
							  }
						   }
						);
					}
				}
				
				function closePostForm()
				{
					$("#post-form").slideUp({duration: "normal", queue: false, complete: function()
					{
						$("#post-form > form > .inputs > [name=title], #post-form > form > .inputs > [name=post]").val("");
						$("#post-submit > .ajax-loader").remove();
						post_submitable = true;
					}});
				}
				
				function closeEditForm(selector)
				{
					$("#edit-form").slideUp({duration: "normal", queue: false, complete: function()
					{
						$("#edit-form > [name=title], #edit-form > [name=post]").val("");
						$("#edit-submit > img").remove();
						edit_submitable = true;
					}});
				}
				
				function showPostForm()
				{
					if ($("#post-form").is(":hidden")) {
						$("#post-form").slideDown({duration: "normal", queue: false});
						$("#post-form > form > .inputs > [name=title]").focus();
					} else {
						$("#post-form").slideUp({duration: "normal", queue: false});
					}
				}
				
				function showEditForm(selector, id, title, post)
				{
					if ($("#edit-form").is(":hidden")) {
					
						$("#edit-form > form > .inputs > [name=title]").val(title);
						$("#edit-form > form > .inputs > [name=post]").val(post);
						$("#edit-form > form > .inputs > [name=edit-id]").val(id);
						
						$("#edit-form")
							.insertAfter("#blog-" + id)
							.slideDown({duration: "normal", queue: false});
					}
					else
					{
						var last_id = $("[name=edit-id]").val();
						
						if (id == last_id)
						{
							$("#edit-form").slideUp({duration: "normal", queue: false});
						}
						else
						{
							$("#edit-form").slideUp({duration: "normal", queue: false, complete: function()
							{
								$("#edit-form > form > .inputs > [name=title]").val(title);
								$("#edit-form > form > .inputs > [name=post]").val(post);
								$("#edit-form > form > .inputs > [name=edit-id]").val(id);
								
								$("#edit-form")
									.insertAfter("#blog-" + id)
									.hide()
									.slideDown({duration: "normal", queue: false});
							}});
						}
					}
					
				}
				
				function editPost()
				{
					
					if (edit_submitable)
					{
						edit_submitable = false;
						
						var id = $('#edit-form > form > .inputs > [name=edit-id]').val();
						var title = $('#edit-form > form > .inputs > [name=title]').val();
						var post = $('#edit-form > form > .inputs > [name=post]').val();
						
						$.post("ajax/ajax.editpost.php", {
							id: id,
							title: title,
							post: post},
							function (data)
							{
								if (data.success)
								{
											
									$.get("ajax/ajax.displayposts.php", {
									id: id},
									function(data) {
										$("#blog-" + id).slideUp({duration: "normal", queue: false, complete: function()
										{
											$("#blog-" + id).replaceWith(data);
											$("#blog-" + id).hide();
											$("#blog-" + id).slideDown({duration: "normal", queue: false});
										}});
										
									});
									
									closeEditForm();
								}
							}, 'json');
					}
				}
				
				function deletePost(selector, id)
				{
					postTotal--;
					
					selector.slideUp("normal", function () 
					{
						selector.remove();
					});
					
					$.post("ajax/ajax.deletepost.php", {
						id: id},
						function (data)
						{

						}, 'json');
				}
				
				function newPost()
				{
					postTotal++;
					
					if (post_submitable)
					{
						post_submitable = false;
						
						$.post("ajax/ajax.submitpost.php", {
							title: $('[name=title]').val(),
							post: $('[name=post]').val()},
							function (data)
							{
								if (data.success)
								{
											
									$.get("ajax/ajax.displayposts.php", {
									offset: 0, amount: 1},
									function(data) {
										$(data).prependTo("#blog-postings");
										$(".blog-post:first").hide();
										$(".blog-post:first").slideDown({duration: "normal", queue: false});
									});
									
									closePostForm();
								}
							}, 'json');
					}
				}
				
				/*  END OF FUNCTIONS  */
				
			});
		</script>
	</body>
</html>
