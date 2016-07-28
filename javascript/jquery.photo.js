$(function ()
{
	var mouseX = 0;
	var mouseY = 0;
	
	var $ratingSelector = false;
	var $ratingWidth = 0;
	
	var $container = $("#thumbs");
	
	var showMoreEnabled = true;
	
	var $photoOffset = 0;
	var $photoAmount = 36;
	
	var photosDisplayed = 0;
	var photosTotal = getPhotosTotal();
	
	if (photosTotal > 0)
		loadPhotos($photoOffset, $photoAmount);
	
	$container.masonry({
		itemSelector: '.photo-thumb',
		columnWidth: 244
		});
	
	/*  FUNCTIONS  */
	
	function loadPhotos(offset, amount)
	{
		if (showMoreEnabled)
		{
			
			showMoreEnabled = false;
			
			showLoader();
			
			$.ajax({
				url: "../ajax/ajax.getphotos.php",
				type: "GET",
				data: {offset: offset, amount: amount},
				dataType: "text",
				async: true,
				success: function (data)
				{
					
					hideLoader();
					
					photosDisplayed += $(data).filter(".photo-thumb").length;
					
					if (photosDisplayed < photosTotal)
					{
						showMoreEnabled = true;
					}
					
					$container.append(data).masonry('reload');
					var $brick = $container.find(".photo-thumb");
					
					$brick.each(function () {
							var img = $(this).find("img");
							$(this).css({height: img.height()});
						});
					
					$container.imagesLoaded(function ()
						{
							$container.find(".photo-thumb").animate({opacity: 1});
							$container.find(".photo-thumb > .thumb-link > .gallery").parent.colorbox();


						});
				}
			});
			
			$photoOffset += $photoAmount;

		}
		
	}
	
	function getPhotosTotal()
	{
		var total = 0;
		
		$.ajax({
			  url:  "../ajax/ajax.getphotostotal.php",
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
	
	function testScrollPosition()
	{
		var $content = $("#main-section");
		var pageBottomPos = $(window).scrollTop() + $(window).height();
		var contentOffset = $content.offset();
		var contentBottomPos = contentOffset.top + $content.height();
		
		if (pageBottomPos > contentBottomPos)
		{
			loadPhotos($photoOffset, $photoAmount);
		}
		
	}
	
	function displayNewPhoto()
	{
		photosTotal++;
		
		$.ajax({
				url: "../ajax/ajax.getphotos.php",
				type: "GET",
				data: {offset: 0, amount: 1},
				dataType: "html",
				async: false,
				success: function (data)
				{
					photosDisplayed++;
					
					$container.prepend(data).masonry('reload');
					var $brick = $container.find(".photo-thumb");
					
					$brick.each(function () {
							var img = $(this).find("img");
							$(this).css({height: img.height()});
						});
					
					$container.imagesLoaded(function ()
						{
							$container.find(".photo-thumb > .thumb-link > img").animate({opacity: 1});
						});
				}
			});
			
			$photoOffset++;
	}
	
	function showLoader()
	{
		$("#show-more-loader > img").css('visibility', 'visible');
	}
	function hideLoader()
	{
		$("#show-more-loader > img").css('visibility', 'hidden');
	}
	
	function toggleShare($selector)
	{
		var id = $selector.attr("id").replace("photo-", "");
		if ($selector.find(".shareSetting > input").is(':checked'))
		{
			var setting = 1;
			
			$selector.find(".thumb-social").show();
			$selector.find(".share-disabled").hide();
		}
		else
		{
			var setting = 0;
			
			$selector.find(".thumb-social").hide();
			$selector.find(".share-disabled").show();
		}
		
		$.ajax({
				url: "../ajax/ajax.photosetsharing.php",
				type: "GET",
				data: {id: id, setting: setting},
				dataType: "json",
				async: true,
				success: function (data)
				{
					
				},
				error: function ()
				{
					alert("Ajax error!");
				}
			});
	}
	
	function deleteClicked(id)
	{
		if (confirm("Are you sure you want to DELETE this photo?"))
		{
			deletePhoto(id);
		}
	}
	
	function deletePhoto(id)
	{
		
		photosTotal--;
		
		$.ajax({
				url: "../ajax/ajax.deletephoto.php",
				type: "GET",
				data: {id: id},
				dataType: "json",
				async: true,
				success: function (data)
				{
					if (data.success)
					{
						photosDisplayed--;
						
						$("#photo-" + id).remove();
		
						$container.masonry('reload');
						
					}
					else
					{
						alert("Error:  Unable to delete photo!");
					}
					
				},
				error: function ()
				{
					alert("Ajax error!");
				}
			});
			
			$photoOffset--;
	}
	
	/*  END OF FUNCTIONS  */
	
	
	/*  EVENTS  */
	
	$(document).on('mouseenter', ".photo-thumb", function()
		{
			$(this).find(".thumb-hover").animate({opacity: 1}, 150);
		}).on('mouseleave', ".photo-thumb", function()
		{
			$(this).find(".thumb-hover").animate({opacity: 0}, 150);
		});
	
	$(document).on('click', ".shareSetting", function ()
		{
			toggleShare($(this).parent());
		});
	
	$(document).on('click', ".deleteButton", function()
		{
			deleteClicked($(this).parent().attr("id").replace("photo-", ""));
		});
	
	$(window).scroll(function ()
	{
		testScrollPosition();
	});
	
	$("#add-button, #file-button").click(function()
	{
		$("#file").trigger('click');
	});
	$("#file").change(function ()
	{
		var value = $("#file").val().split('/').pop().split('\\').pop();
		$("#file-name").replaceWith("<span id=\"file-name\">" + value + "</span>");
		$("#add-button").hide();
		$("#file-button").show();
		$("#upload-button").show();
		
	});
	$("#upload-button").click(function ()
	{
		$("#upload-loader").show();
		$("#file-name, #file-button, #upload-button").hide();

		
		function resetPage()
		{
			$("#upload-loader").hide();
			$("#add-button").show();
			$("#upload-frame").attr('src', 'about:blank');
		}

		
		var timerId = setInterval(function ()
		{
			switch ($("#upload-frame").contents().text())
			{
				case "done":
					resetPage();
					clearInterval(timerId);
					
					displayNewPhoto();
					
					break;
				case "extension":
					resetPage();
					clearInterval(timerId);
					
					alert("Site only supports JPG/JPEG!");
					
					break;
				case "size":
					resetPage();
					clearInterval(timerId);
					
					alert("File must be under 20mb!");
					
					break;
				case "":
					break;
				default:
					resetPage();								
					clearInterval(timerId);
					
					alert("Upload Error");
					
					break;
			}
			
		}, 500);
	});
	
	$(".overlay").show();
	
	$(".photo-thumb").on('mouseover', function ()
	{
		alert("bing");
		//$(this).find(".overlay").show();
	});
	
	/*  END OF EVENTS  */
});