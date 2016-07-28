<?php
	require('../classes/connection.php');
	$link = new Connection();
	
	require('../classes/login.php');
	$login = new Login($link);
	
	require('../classes/photo.php');
	$photo = new Photo($link);
	
	if (isset($_GET['id']))
	{
		if (is_numeric($_GET['id']))
		{
			$id = $_GET['id'];
			
			$result = $photo->getPhotos($id, false);
		}
	}
	else if (isset($_GET['offset'], $_GET['amount']))
	{
		if (is_numeric($_GET['offset']) && is_numeric($_GET['amount']))
		{
			$offset = $_GET['offset'];
			$amount = $_GET['amount'];
			
			$result = $photo->getPhotos($offset, $amount);
		}
	}
	else
	{
		exit();
	}
	

	/* Cycle through results and then display */
	
	while($row = $result->fetch_assoc()):
		$id = $row['id'];
		$file = $row['file_name'];
		$size = getimagesize('../images/photos/thumbs/' . $file);
		$share = $row['share'];
?>
<div class="photo-thumb" id="photo-<?php echo $id; ?>" style="opacity: 0;">
	<?php
		if ($login->logged_in && $login->user == $row['user']):
	?>
		<div class="shareSetting thumb-hover">
			<input type="checkbox" <?php if ($share) : ?>checked="yes"<?php endif; ?>"/>Share
		</div>
		<div class="deleteButton thumb-hover"></div>
	<?php
		endif;
	?>
	<div class="thumb-social thumb-hover" <?php if (!$share): ?>style="display: none"<?php endif; ?>>

		<a class="tw-button" href="#" onclick="
    window.open(
      'https://www.twitter.com/share?text=Go check out this photo by @jordancreations: http://www.jordancreations.com/images/photos/<?php echo $file; ?> or to see more visit ', 
      'twitter-share-dialog', 
      'width=626,height=436'); 
    return false;"></a>
		<a class="pin-button" href="#" onclick="
			window.open(
				'http://pinterest.com/pin/create/button/?url=http://www.jordancreations.com/photo&media=http://www.jordancreations.com/images/photos/<?php echo $file; ?>&description=Check out this photo by JordanCreations:  http://www.jordancreations.com/images/photos/<?php echo $file; ?> and also check out JordanCreations.com',
				'pinterest-share-dialog',
				'width=626, height=436');
				return false;"></a>
		<a class="gp-button" href="#" onclick="
    window.open(
      'https://plus.google.com/share?url=http://www.jordancreations.com/images/photos/<?php echo $file; ?>&hl=en', 
      'google-share-dialog', 
      'width=626,height=436'); 
    return false;"></a>
		<a class="fb-button" href="#" onclick="
    window.open(
      'https://www.facebook.com/sharer/sharer.php?u=http://www.jordancreations.com/images/photos/<?php echo $file; ?>', 
      'facebook-share-dialog', 
      'width=626,height=436'); 
    return false;"></a>
	</div>
	<div class="share-disabled thumb-hover" <?php if ($share): ?>style="display: none"<?php endif; ?>>
			<p>Sharing Disabled</p>
	</div>
	<a class="thumb-link" href="../images/photos/<?php echo $file; ?>" rel="album">
		<div class="overlay thumb-hover"></div>
		<img src="../images/photos/thumbs/<?php echo $file; ?>" width="<?php echo $size[0]; ?>" height="<?php echo $size[1]; ?>"/>
	</a>
</div>
<?php
	endwhile;
	
	$result->close();
?>
