<?php
	require ('../classes/connection.php');
	$connection = new Connection();
	
	require ('../classes/login.php');
	$login = new Login($connection);
	
	require ('../classes/blog.php');
	$blog = new Blog($connection);
	
	require ('../classes/bbcode.php');
	$bb = new BBCode();
	
	$byId = false;
	
	if (isset($_GET['id']))
	{
		$byId = true;
		
		if (is_numeric($_GET['id']))
		{
			$id = $_GET['id'];
		}
	}
	else if (isset($_GET['offset'], $_GET['amount']))
	{
		if (is_numeric($_GET['offset']) && is_numeric($_GET['amount']))
		{
			$offset = $_GET['offset'];
			$amount = $_GET['amount'];
		}
	}
	else
	{
		exit();
	}
	
	if ($byId)
	{
		/* Retrieves single blog post by ID */
		$result = $blog->getSinglePost($id);
	}
	else
	{
		/* Retrieves blog posts between $firstPost and $lastPost ordered by ID */
		$result = $blog->getMultiplePosts($offset, $amount);
	}

	/* Cycle through results and then display */
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
			by <span><?php echo $userInfo['first_name'] . " " . $userInfo['last_name']; ?></span> [<?php 
											
											if ($secLapse < 60)
											{
												if ($secLapse > 1)
													echo $secLapse . ' seconds ago';
												else
													echo 'a second ago';
											}
											else if ($secLapse < 3600)
											{
												$t = floor($secLapse / 60);
												
												if ($t > 1)
													echo $t . ' minutes ago';
												else
													echo 'a minute ago';
											}
											else if ($secLapse < 86400)
											{
												$t = floor($secLapse / 3600);
												
												if ($t > 1)
													echo $t . ' hours ago';
												else
													echo 'an hour ago';
											}
											else if ($dayLapse < 7)
											{
												if ($dayLapse > 1)
													echo $dayLapse . ' days ago';
												else
													echo 'a day ago';
											}
											else if ($dayLapse < 30)
											{
												$t = floor($dayLapse / 7);
												
												if ($t > 1)
													echo $t . ' weeks ago';
												else
													echo 'a week ago';
											}
											else if ($dayLapse < 365)
											{
												$t = floor($dayLapse / 30);
												$w = floor(($dayLapse - ($t * 30)) / 7);
												
												if ($w == 4)
												{
													echo 'almost ' . ($t + 1) . ' months ago';
												}
												else
												{
													if ($t > 1)
														echo $t . ' months';
													else
														echo 'a month';
													
													if ($w > 1)
														echo ' and ' . $w . ' weeks';
													else
														echo ' and a week';
													
													echo ' ago';
												}
											}
											else
											{
												$t = floor($dayLapse / 365);
												
												if ($t > 1)
													echo $t . ' years ago';
												else
													echo 'a year ago';
											}
											
										?>]
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